<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/validation.php';

function olympians_trimmed(?string $value): ?string {
    $value = trim((string)$value);
    return $value === '' ? null : $value;
}

function olympians_validate_date(?string $value, string $label, array &$errors): ?string {
    $value = olympians_trimmed($value);
    if ($value === null) {
        return null;
    }

    $date = DateTimeImmutable::createFromFormat('Y-m-d', $value);
    $dateErrors = DateTimeImmutable::getLastErrors();
    $warningCount = is_array($dateErrors) ? (int)($dateErrors['warning_count'] ?? 0) : 0;
    $errorCount = is_array($dateErrors) ? (int)($dateErrors['error_count'] ?? 0) : 0;
    if (!$date || $warningCount > 0 || $errorCount > 0) {
        $errors[] = sprintf('%s musí byť vo formáte YYYY-MM-DD.', $label);
        return null;
    }

    return $date->format('Y-m-d');
}

function olympians_validate_short_text(?string $value, string $label, int $maxLength, array &$errors): ?string {
    $value = olympians_trimmed($value);
    if ($value === null) {
        return null;
    }
    if (app_strlen($value) > $maxLength) {
        $errors[] = sprintf('%s môže mať najviac %d znakov.', $label, $maxLength);
        return null;
    }
    return $value;
}

function olympians_normalize_person_payload(array $payload, bool $requireAwards = true): array {
    $errors = [];

    $firstName = trim((string)($payload['first_name'] ?? ''));
    $lastName = trim((string)($payload['last_name'] ?? ''));

    if ($message = validate_person_name($firstName, 'Meno')) {
        $errors[] = $message;
    }
    if ($message = validate_person_name($lastName, 'Priezvisko')) {
        $errors[] = $message;
    }

    $birthDate = olympians_validate_date($payload['birth_date'] ?? null, 'Dátum narodenia', $errors);
    $deathDate = olympians_validate_date($payload['death_date'] ?? null, 'Dátum úmrtia', $errors);
    $birthPlace = olympians_validate_short_text($payload['birth_place'] ?? null, 'Miesto narodenia', 191, $errors);
    $deathPlace = olympians_validate_short_text($payload['death_place'] ?? null, 'Miesto úmrtia', 191, $errors);
    $birthCountry = olympians_validate_short_text($payload['birth_country'] ?? null, 'Krajina narodenia', 191, $errors);
    $deathCountry = olympians_validate_short_text($payload['death_country'] ?? null, 'Krajina úmrtia', 191, $errors);

    $awardsPayload = $payload['awards'] ?? [];
    if (!is_array($awardsPayload)) {
        $errors[] = 'Ocenenia musia byť pole.';
        $awardsPayload = [];
    }

    $awards = [];
    $awardKeys = [];
    foreach ($awardsPayload as $index => $awardPayload) {
        if (!is_array($awardPayload)) {
            $errors[] = sprintf('Ocenenie #%d má neplatný formát.', $index + 1);
            continue;
        }

        $type = strtoupper(trim((string)($awardPayload['type'] ?? '')));
        if (!in_array($type, ['LOH', 'ZOH'], true)) {
            $errors[] = sprintf('Ocenenie #%d musí mať typ LOH alebo ZOH.', $index + 1);
        }

        $year = (int)($awardPayload['year'] ?? 0);
        if ($year < 1896 || $year > 2100) {
            $errors[] = sprintf('Ocenenie #%d má neplatný rok.', $index + 1);
        }

        $placing = (int)($awardPayload['placing'] ?? 0);
        if ($placing <= 0 || $placing > 999) {
            $errors[] = sprintf('Ocenenie #%d má neplatné umiestnenie.', $index + 1);
        }

        $category = olympians_validate_short_text($awardPayload['category'] ?? null, sprintf('Kategória ocenenia #%d', $index + 1), 191, $errors);
        $discipline = olympians_validate_short_text($awardPayload['discipline'] ?? null, sprintf('Disciplína ocenenia #%d', $index + 1), 191, $errors);
        $city = olympians_validate_short_text($awardPayload['city'] ?? null, sprintf('Mesto ocenenia #%d', $index + 1), 191, $errors);
        $hostCountry = olympians_validate_short_text($awardPayload['host_country'] ?? null, sprintf('Hostiteľská krajina ocenenia #%d', $index + 1), 191, $errors);
        $representedCountry = olympians_validate_short_text($awardPayload['represented_country'] ?? null, sprintf('Reprezentovaná krajina ocenenia #%d', $index + 1), 191, $errors);

        if ($category === null) {
            $errors[] = sprintf('Ocenenie #%d musí mať kategóriu.', $index + 1);
        }
        if ($discipline === null) {
            $errors[] = sprintf('Ocenenie #%d musí mať disciplínu.', $index + 1);
        }
        if ($city === null) {
            $errors[] = sprintf('Ocenenie #%d musí mať mesto.', $index + 1);
        }
        if ($hostCountry === null) {
            $errors[] = sprintf('Ocenenie #%d musí mať hostiteľskú krajinu.', $index + 1);
        }

        $awards[] = [
            'type' => $type,
            'year' => $year,
            'placing' => $placing,
            'category' => $category,
            'discipline' => $discipline,
            'city' => $city,
            'host_country' => $hostCountry,
            'represented_country' => $representedCountry,
        ];

        $awardKey = implode('|', [$type, $year, $placing, (string)$category, (string)$discipline]);
        if (isset($awardKeys[$awardKey])) {
            $errors[] = sprintf('Ocenenie #%d je v požiadavke duplicitné.', $index + 1);
        }
        $awardKeys[$awardKey] = true;
    }

    if ($requireAwards && count($awards) === 0) {
        $errors[] = 'Olympionik musí mať aspoň jedno ocenenie alebo výsledok.';
    }

    return [
        'errors' => $errors,
        'data' => [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'birth_date' => $birthDate,
            'birth_place' => $birthPlace,
            'birth_country' => $birthCountry,
            'death_date' => $deathDate,
            'death_place' => $deathPlace,
            'death_country' => $deathCountry,
            'awards' => $awards,
        ],
    ];
}

function olympians_find_country_id(PDO $pdo, ?string $name): ?int {
    if ($name === null) {
        return null;
    }

    $st = $pdo->prepare('SELECT id FROM countries WHERE name = :name LIMIT 1');
    $st->execute([':name' => $name]);
    $id = $st->fetchColumn();
    return $id ? (int)$id : null;
}

function olympians_get_or_create_country(PDO $pdo, ?string $name): ?int {
    if ($name === null) {
        return null;
    }

    $existingId = olympians_find_country_id($pdo, $name);
    if ($existingId !== null) {
        return $existingId;
    }

    $st = $pdo->prepare('INSERT INTO countries (name) VALUES (:name)');
    $st->execute([':name' => $name]);
    return (int)$pdo->lastInsertId();
}

function olympians_get_or_create_games(PDO $pdo, array $award): int {
    $hostCountryId = olympians_get_or_create_country($pdo, $award['host_country']);
    $st = $pdo->prepare('SELECT id FROM olympic_games WHERE type = :type AND year = :year LIMIT 1');
    $st->execute([
        ':type' => $award['type'],
        ':year' => $award['year'],
    ]);
    $id = $st->fetchColumn();
    if ($id) {
        $update = $pdo->prepare('UPDATE olympic_games SET city = :city, country_id = :country_id WHERE id = :id');
        $update->execute([
            ':city' => $award['city'],
            ':country_id' => $hostCountryId,
            ':id' => (int)$id,
        ]);
        return (int)$id;
    }

    $insert = $pdo->prepare('INSERT INTO olympic_games (type, year, city, country_id) VALUES (:type, :year, :city, :country_id)');
    $insert->execute([
        ':type' => $award['type'],
        ':year' => $award['year'],
        ':city' => $award['city'],
        ':country_id' => $hostCountryId,
    ]);

    return (int)$pdo->lastInsertId();
}

function olympians_get_or_create_discipline(PDO $pdo, array $award): int {
    $st = $pdo->prepare('SELECT id FROM disciplines WHERE category = :category AND name = :name LIMIT 1');
    $st->execute([
        ':category' => $award['category'],
        ':name' => $award['discipline'],
    ]);
    $id = $st->fetchColumn();
    if ($id) {
        return (int)$id;
    }

    $insert = $pdo->prepare('INSERT INTO disciplines (category, name) VALUES (:category, :name)');
    $insert->execute([
        ':category' => $award['category'],
        ':name' => $award['discipline'],
    ]);
    return (int)$pdo->lastInsertId();
}

function olympians_get_or_create_medal_type(PDO $pdo, int $placing): ?int {
    $st = $pdo->prepare('SELECT id FROM medal_types WHERE placing = :placing LIMIT 1');
    $st->execute([':placing' => $placing]);
    $id = $st->fetchColumn();
    if ($id) {
        return (int)$id;
    }

    $name = match ($placing) {
        1 => 'Gold',
        2 => 'Silver',
        3 => 'Bronze',
        default => $placing . '. miesto',
    };
    $description = $placing <= 3 ? 'Medailové umiestnenie' : 'Finálové umiestnenie';
    $insert = $pdo->prepare('INSERT INTO medal_types (placing, name, description) VALUES (:placing, :name, :description)');
    $insert->execute([
        ':placing' => $placing,
        ':name' => $name,
        ':description' => $description,
    ]);
    return (int)$pdo->lastInsertId();
}

function olympians_assert_unique_identity(PDO $pdo, array $data, ?int $ignoreId = null): void {
    $sql = 'SELECT id FROM athletes WHERE first_name = :first_name AND last_name = :last_name AND ';
    $sql .= $data['birth_date'] === null ? 'birth_date IS NULL' : 'birth_date = :birth_date';
    if ($ignoreId !== null) {
        $sql .= ' AND id <> :ignore_id';
    }

    $params = [
        ':first_name' => $data['first_name'],
        ':last_name' => $data['last_name'],
    ];
    if ($data['birth_date'] !== null) {
        $params[':birth_date'] = $data['birth_date'];
    }
    if ($ignoreId !== null) {
        $params[':ignore_id'] = $ignoreId;
    }

    $st = $pdo->prepare($sql . ' LIMIT 1');
    $st->execute($params);
    if ($st->fetchColumn()) {
        throw new InvalidArgumentException('Takýto olympionik už v databáze existuje.');
    }
}

function olympians_insert_awards(PDO $pdo, int $athleteId, array $awards, ?string $fallbackCountry = null): void {
    foreach ($awards as $award) {
        $gamesId = olympians_get_or_create_games($pdo, $award);
        $disciplineId = olympians_get_or_create_discipline($pdo, $award);
        $medalTypeId = olympians_get_or_create_medal_type($pdo, $award['placing']);
        $representedCountryId = olympians_get_or_create_country($pdo, $award['represented_country'] ?? $fallbackCountry);

        $insert = $pdo->prepare(
            'INSERT INTO athlete_medals (athlete_id, olympic_games_id, discipline_id, represented_country_id, medal_type_id, placing)
             VALUES (:athlete_id, :games_id, :discipline_id, :country_id, :medal_type_id, :placing)'
        );
        $insert->execute([
            ':athlete_id' => $athleteId,
            ':games_id' => $gamesId,
            ':discipline_id' => $disciplineId,
            ':country_id' => $representedCountryId,
            ':medal_type_id' => $medalTypeId,
            ':placing' => $award['placing'],
        ]);
    }
}

function olympians_get_full(PDO $pdo, int $id): ?array {
    $athleteSt = $pdo->prepare(
        'SELECT a.id, a.first_name, a.last_name, a.birth_date, a.birth_place, bc.name AS birth_country,
                a.death_date, a.death_place, dc.name AS death_country
         FROM athletes a
         LEFT JOIN countries bc ON bc.id = a.birth_country_id
         LEFT JOIN countries dc ON dc.id = a.death_country_id
         WHERE a.id = :id'
    );
    $athleteSt->execute([':id' => $id]);
    $athlete = $athleteSt->fetch();
    if (!$athlete) {
        return null;
    }

    $awardsSt = $pdo->prepare(
        'SELECT am.id, g.type, g.year, g.city, hc.name AS host_country, d.category, d.name AS discipline,
                am.placing, COALESCE(rc.name, "") AS represented_country
         FROM athlete_medals am
         JOIN olympic_games g ON g.id = am.olympic_games_id
         JOIN countries hc ON hc.id = g.country_id
         JOIN disciplines d ON d.id = am.discipline_id
         LEFT JOIN countries rc ON rc.id = am.represented_country_id
         WHERE am.athlete_id = :id
         ORDER BY g.year DESC, am.placing ASC, d.category ASC, d.name ASC'
    );
    $awardsSt->execute([':id' => $id]);
    $athlete['awards'] = $awardsSt->fetchAll();

    return $athlete;
}

function olympians_create(PDO $pdo, array $payload): array {
    $normalized = olympians_normalize_person_payload($payload, true);
    if ($normalized['errors']) {
        throw new InvalidArgumentException(implode(' ', $normalized['errors']));
    }

    $data = $normalized['data'];
    olympians_assert_unique_identity($pdo, $data);

    $pdo->beginTransaction();
    try {
        $birthCountryId = olympians_get_or_create_country($pdo, $data['birth_country']);
        $deathCountryId = olympians_get_or_create_country($pdo, $data['death_country']);

        $insert = $pdo->prepare(
            'INSERT INTO athletes (first_name, last_name, birth_date, birth_place, birth_country_id, death_date, death_place, death_country_id)
             VALUES (:first_name, :last_name, :birth_date, :birth_place, :birth_country_id, :death_date, :death_place, :death_country_id)'
        );
        $insert->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':birth_date' => $data['birth_date'],
            ':birth_place' => $data['birth_place'],
            ':birth_country_id' => $birthCountryId,
            ':death_date' => $data['death_date'],
            ':death_place' => $data['death_place'],
            ':death_country_id' => $deathCountryId,
        ]);

        $athleteId = (int)$pdo->lastInsertId();
        olympians_insert_awards($pdo, $athleteId, $data['awards'], $data['birth_country']);
        $pdo->commit();

        return olympians_get_full($pdo, $athleteId);
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

function olympians_update(PDO $pdo, int $id, array $payload): array {
    $existing = olympians_get_full($pdo, $id);
    if (!$existing) {
        throw new RuntimeException('Olympionik sa nenašiel.');
    }

    $normalized = olympians_normalize_person_payload($payload, true);
    if ($normalized['errors']) {
        throw new InvalidArgumentException(implode(' ', $normalized['errors']));
    }

    $data = $normalized['data'];
    olympians_assert_unique_identity($pdo, $data, $id);

    $pdo->beginTransaction();
    try {
        $birthCountryId = olympians_get_or_create_country($pdo, $data['birth_country']);
        $deathCountryId = olympians_get_or_create_country($pdo, $data['death_country']);

        $update = $pdo->prepare(
            'UPDATE athletes
             SET first_name = :first_name, last_name = :last_name, birth_date = :birth_date, birth_place = :birth_place,
                 birth_country_id = :birth_country_id, death_date = :death_date, death_place = :death_place,
                 death_country_id = :death_country_id
             WHERE id = :id'
        );
        $update->execute([
            ':id' => $id,
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':birth_date' => $data['birth_date'],
            ':birth_place' => $data['birth_place'],
            ':birth_country_id' => $birthCountryId,
            ':death_date' => $data['death_date'],
            ':death_place' => $data['death_place'],
            ':death_country_id' => $deathCountryId,
        ]);

        $deleteAwards = $pdo->prepare('DELETE FROM athlete_medals WHERE athlete_id = :id');
        $deleteAwards->execute([':id' => $id]);
        olympians_insert_awards($pdo, $id, $data['awards'], $data['birth_country']);
        $pdo->commit();

        return olympians_get_full($pdo, $id);
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

function olympians_delete(PDO $pdo, int $id): bool {
    $st = $pdo->prepare('DELETE FROM athletes WHERE id = :id');
    $st->execute([':id' => $id]);
    return $st->rowCount() > 0;
}

function olympians_list(PDO $pdo, array $filters = []): array {
    $where = [];
    $params = [];

    if (!empty($filters['type'])) {
        $where[] = 'g.type = :type';
        $params[':type'] = strtoupper((string)$filters['type']);
    }
    if (!empty($filters['year'])) {
        $where[] = 'g.year = :year';
        $params[':year'] = (int)$filters['year'];
    }
    if (!empty($filters['placing'])) {
        $where[] = 'am.placing = :placing';
        $params[':placing'] = (int)$filters['placing'];
    }
    if (!empty($filters['sport'])) {
        $where[] = '(d.category LIKE :sport OR d.name LIKE :sport)';
        $params[':sport'] = '%' . trim((string)$filters['sport']) . '%';
    }
    if (!empty($filters['q'])) {
        $where[] = '(a.first_name LIKE :q OR a.last_name LIKE :q)';
        $params[':q'] = '%' . trim((string)$filters['q']) . '%';
    }

    $sql = 'SELECT a.id AS athlete_id, a.first_name, a.last_name, a.birth_date, a.birth_place,
                   bc.name AS birth_country, a.death_date, a.death_place, dc.name AS death_country,
                   am.id AS award_id, g.type, g.year, g.city, hc.name AS host_country, d.category,
                   d.name AS discipline, am.placing, COALESCE(rc.name, "") AS represented_country
            FROM athlete_medals am
            JOIN athletes a ON a.id = am.athlete_id
            JOIN olympic_games g ON g.id = am.olympic_games_id
            JOIN countries hc ON hc.id = g.country_id
            JOIN disciplines d ON d.id = am.discipline_id
            LEFT JOIN countries rc ON rc.id = am.represented_country_id
            LEFT JOIN countries bc ON bc.id = a.birth_country_id
            LEFT JOIN countries dc ON dc.id = a.death_country_id';
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= ' ORDER BY g.year DESC, a.last_name ASC, a.first_name ASC, am.placing ASC';

    $st = $pdo->prepare($sql);
    $st->execute($params);
    $rows = $st->fetchAll();

    $grouped = [];
    foreach ($rows as $row) {
        $athleteId = (int)$row['athlete_id'];
        if (!isset($grouped[$athleteId])) {
            $grouped[$athleteId] = [
                'id' => $athleteId,
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'birth_date' => $row['birth_date'],
                'birth_place' => $row['birth_place'],
                'birth_country' => $row['birth_country'],
                'death_date' => $row['death_date'],
                'death_place' => $row['death_place'],
                'death_country' => $row['death_country'],
                'awards' => [],
            ];
        }

        $grouped[$athleteId]['awards'][] = [
            'id' => (int)$row['award_id'],
            'type' => $row['type'],
            'year' => (int)$row['year'],
            'city' => $row['city'],
            'host_country' => $row['host_country'],
            'category' => $row['category'],
            'discipline' => $row['discipline'],
            'placing' => (int)$row['placing'],
            'represented_country' => $row['represented_country'],
        ];
    }

    return array_values($grouped);
}

function olympians_import_json(PDO $pdo, array $items): array {
    $created = 0;
    $errors = [];

    foreach ($items as $index => $item) {
        if (!is_array($item)) {
            $errors[] = sprintf('Položka #%d nie je objekt.', $index + 1);
            continue;
        }

        try {
            olympians_create($pdo, $item);
            $created++;
        } catch (Throwable $e) {
            $errors[] = sprintf('Položka #%d: %s', $index + 1, $e->getMessage());
        }
    }

    return [
        'created' => $created,
        'errors' => $errors,
    ];
}
