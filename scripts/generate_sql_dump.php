<?php
require_once __DIR__ . '/../app/db.php';

$pdo = db();
$targetDir = __DIR__ . '/../database';

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$tables = [];
foreach ($pdo->query('SHOW TABLES') as $row) {
    $tables[] = array_values($row)[0];
}

$schema = "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n\n";
$dump = $schema;

foreach ($tables as $table) {
    $createTable = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_ASSOC)['Create Table'];

    $schema .= "DROP TABLE IF EXISTS `{$table}`;\n{$createTable};\n\n";
    $dump .= "DROP TABLE IF EXISTS `{$table}`;\n{$createTable};\n\n";

    $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
    if (!$rows) {
        continue;
    }

    $columns = array_map(static fn(string $column): string => "`{$column}`", array_keys($rows[0]));

    foreach ($rows as $row) {
        $values = [];
        foreach ($row as $value) {
            if ($value === null) {
                $values[] = 'NULL';
                continue;
            }

            if (is_int($value) || is_float($value)) {
                $values[] = (string)$value;
                continue;
            }

            $stringValue = (string)$value;
            if (preg_match('/^-?\d+(\.\d+)?$/', $stringValue) && !preg_match('/^0\d+$/', $stringValue)) {
                $values[] = $stringValue;
                continue;
            }

            $values[] = $pdo->quote($stringValue);
        }

        $dump .= sprintf(
            "INSERT INTO `%s` (%s) VALUES (%s);\n",
            $table,
            implode(', ', $columns),
            implode(', ', $values)
        );
    }

    $dump .= "\n";
}

$schema .= "SET FOREIGN_KEY_CHECKS=1;\n";
$dump .= "SET FOREIGN_KEY_CHECKS=1;\n";

file_put_contents($targetDir . '/schema.sql', $schema);
file_put_contents($targetDir . '/dump.sql', $dump);

echo "Generated database/schema.sql and database/dump.sql\n";
