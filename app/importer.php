<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/db.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

function excelDateToYmd($value): ?string {
    if ($value === null || $value === '') return null;

    if (is_numeric($value)) {
        return ExcelDate::excelToDateTimeObject((float)$value)->format('Y-m-d');
    }

    $ts = strtotime((string)$value);
    return $ts ? date('Y-m-d', $ts) : null;
}

function parseDiscipline(string $raw): array {
    $raw = trim($raw);
    if (str_contains($raw, ' - ')) {
        [$cat, $name] = explode(' - ', $raw, 2);
        return [trim($cat), trim($name)];
    }
    $parts = explode(' ', $raw, 2);
    if (count($parts) === 2) return [trim($parts[0]), trim($parts[1])];
    return [$raw, $raw];
}

function getOrCreateCountry(PDO $pdo, string $name, ?string $code = null): int {
    $stmt = $pdo->prepare("SELECT id, code FROM countries WHERE name = :name LIMIT 1");
    $stmt->execute([':name' => $name]);
    $row = $stmt->fetch();
    if ($row) {
        if ($code && empty($row['code'])) {
            $u = $pdo->prepare("UPDATE countries SET code = :code WHERE id = :id");
            $u->execute([':code'=>$code, ':id'=>$row['id']]);
        }
        return (int)$row['id'];
    }
    $ins = $pdo->prepare("INSERT INTO countries(name, code) VALUES(:name, :code)");
    $ins->execute([':name'=>$name, ':code'=>$code]);
    return (int)$pdo->lastInsertId();
}

function getOrCreateGames(PDO $pdo, string $type, int $year, ?int $orderNo, string $city, int $countryId): int {
    $stmt = $pdo->prepare("SELECT id FROM olympic_games WHERE type=:type AND year=:year LIMIT 1");
    $stmt->execute([':type'=>$type, ':year'=>$year]);
    $id = $stmt->fetchColumn();
    if ($id) return (int)$id;

    $ins = $pdo->prepare("INSERT INTO olympic_games(type, year, order_no, city, country_id)
                          VALUES(:type,:year,:order_no,:city,:country_id)");
    $ins->execute([
        ':type'=>$type, ':year'=>$year, ':order_no'=>$orderNo,
        ':city'=>$city, ':country_id'=>$countryId
    ]);
    return (int)$pdo->lastInsertId();
}

function getOrCreateDiscipline(PDO $pdo, string $raw): int {
    [$cat, $name] = parseDiscipline($raw);
    $stmt = $pdo->prepare("SELECT id FROM disciplines WHERE category=:c AND name=:n LIMIT 1");
    $stmt->execute([':c'=>$cat, ':n'=>$name]);
    $id = $stmt->fetchColumn();
    if ($id) return (int)$id;

    $ins = $pdo->prepare("INSERT INTO disciplines(category, name) VALUES(:c,:n)");
    $ins->execute([':c'=>$cat, ':n'=>$name]);
    return (int)$pdo->lastInsertId();
}

function getOrCreateAthlete(PDO $pdo, array $r, ?int $birthCountryId, ?int $deathCountryId): int {
    $stmt = $pdo->prepare("SELECT id FROM athletes
        WHERE first_name=:fn AND last_name=:ln AND birth_date=:bd LIMIT 1");
    $stmt->execute([
        ':fn'=>$r['name'], ':ln'=>$r['surname'], ':bd'=>$r['birth_day']
    ]);
    $id = $stmt->fetchColumn();
    if ($id) return (int)$id;

    $ins = $pdo->prepare("INSERT INTO athletes(first_name,last_name,birth_date,birth_place,birth_country_id,
        death_date,death_place,death_country_id)
        VALUES(:fn,:ln,:bd,:bp,:bci,:dd,:dp,:dci)");
    $ins->execute([
        ':fn'=>$r['name'], ':ln'=>$r['surname'], ':bd'=>$r['birth_day'],
        ':bp'=>$r['birth_place'], ':bci'=>$birthCountryId,
        ':dd'=>$r['death_day'], ':dp'=>$r['death_place'], ':dci'=>$deathCountryId
    ]);
    return (int)$pdo->lastInsertId();
}

function getOrCreateMedalType(PDO $pdo, int $placing): ?int {
    $stmt = $pdo->prepare("SELECT id FROM medal_types WHERE placing=:p LIMIT 1");
    $stmt->execute([':p'=>$placing]);
    $id = $stmt->fetchColumn();
    if ($id) return (int)$id;

    $name = match($placing) {
        1 => 'Gold', 2 => 'Silver', 3 => 'Bronze',
        default => $placing . '. miesto'
    };
    $desc = ($placing <= 3) ? 'Medaila' : 'Umiestnenie bez medaily';
    $ins = $pdo->prepare("INSERT INTO medal_types(placing,name,description) VALUES(:p,:n,:d)");
    $ins->execute([':p'=>$placing, ':n'=>$name, ':d'=>$desc]);
    return (int)$pdo->lastInsertId();
}

function pickCellByHeaderAliases(array $row, array $headerMap, array $aliases): string {
    foreach ($aliases as $alias) {
        if (!isset($headerMap[$alias])) {
            continue;
        }
        $value = trim((string)$row[$headerMap[$alias]]);
        if ($value !== '') {
            return $value;
        }
    }
    return '';
}

function importXlsx(string $tmpPath): array {
    $pdo = db();
    $spreadsheet = IOFactory::load($tmpPath);

    $pdo->beginTransaction();
    try {
        $ohSheet = $spreadsheet->getSheetByName('OH');
        $ohRows = $ohSheet->toArray(null, true, true, true);

        $header = array_map('trim', $ohRows[1]);
        $map = [];
        foreach ($header as $col => $name) $map[$name] = $col;

        $gamesCount = 0;
        for ($i=2; $i<=count($ohRows); $i++) {
            $row = $ohRows[$i];
            if (empty($row[$map['type']]) || empty($row[$map['year']])) continue;

            $type = trim((string)$row[$map['type']]);
            $year = (int)$row[$map['year']];
            $orderNo = (int)$row[$map['order']];
            $city = trim((string)$row[$map['city']]);
            $country = trim((string)$row[$map['country']]);
            $code = trim((string)$row[$map['code']]);

            $countryId = getOrCreateCountry($pdo, $country, $code);
            getOrCreateGames($pdo, $type, $year, $orderNo, $city, $countryId);
            $gamesCount++;
        }

        $pSheet = $spreadsheet->getSheetByName('people');
        $pRows = $pSheet->toArray(null, true, true, true);
        $pHeader = array_map('trim', $pRows[1]);
        $pm = [];
        foreach ($pHeader as $col => $name) $pm[$name] = $col;

        $medalsCount = 0;
        for ($i=2; $i<=count($pRows); $i++) {
            $row = $pRows[$i];
            if (empty($row[$pm['surname']]) || empty($row[$pm['oh_year']])) continue;

            $placing = (int)$row[$pm['placing']];
            $disciplineRaw = trim((string)$row[$pm['discipline']]);

            $birthCountryName = trim((string)$row[$pm['birth_country']]);
            $deathCountryName = trim((string)$row[$pm['death_country']]);

            $birthCountryId = $birthCountryName ? getOrCreateCountry($pdo, $birthCountryName, null) : null;
            $deathCountryId = $deathCountryName ? getOrCreateCountry($pdo, $deathCountryName, null) : null;

            $r = [
                'name' => trim((string)$row[$pm['name']]),
                'surname' => trim((string)$row[$pm['surname']]),
                'birth_day' => excelDateToYmd($row[$pm['birth_day']]),
                'birth_place' => trim((string)$row[$pm['birth_place']]),
                'death_day' => excelDateToYmd($row[$pm['death_day']]),
                'death_place' => trim((string)$row[$pm['death_place']]),
            ];

            $athleteId = getOrCreateAthlete($pdo, $r, $birthCountryId, $deathCountryId);

            $ohType = trim((string)$row[$pm['oh_type']]);
            $ohYear = (int)$row[$pm['oh_year']];
            $ohCity = trim((string)$row[$pm['oh_city']]);
            $ohCountry = trim((string)$row[$pm['oh_country']]);
            $hostCountryId = getOrCreateCountry($pdo, $ohCountry, null);
            $gamesId = getOrCreateGames($pdo, $ohType, $ohYear, null, $ohCity, $hostCountryId);

            $disciplineId = getOrCreateDiscipline($pdo, $disciplineRaw);
            $medalTypeId = getOrCreateMedalType($pdo, $placing);

            $representedCountryName = pickCellByHeaderAliases($row, $pm, [
                'represented_country',
                'country',
                'rep_country',
                'nation',
                'team',
            ]);
            if ($representedCountryName === '') {
                $representedCountryName = $birthCountryName;
            }
            $representedCountryId = $representedCountryName !== ''
                ? getOrCreateCountry($pdo, $representedCountryName, null)
                : null;

            $ins = $pdo->prepare("INSERT IGNORE INTO athlete_medals
                (athlete_id, olympic_games_id, discipline_id, represented_country_id, medal_type_id, placing)
                VALUES(:a,:g,:d,:rc,:m,:p)");
            $ins->execute([
                ':a'=>$athleteId, ':g'=>$gamesId, ':d'=>$disciplineId,
                ':rc'=>$representedCountryId, ':m'=>$medalTypeId, ':p'=>$placing
            ]);
            $medalsCount++;
        }

        $pdo->commit();
        return ['ok'=>true, 'games_rows'=>$gamesCount, 'people_rows'=>$medalsCount];
    } catch (Throwable $e) {
        $pdo->rollBack();
        return ['ok'=>false, 'error'=>$e->getMessage()];
    }
}
