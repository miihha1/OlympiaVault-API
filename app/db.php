<?php
function fail_dependency(string $message): void {
    if (PHP_SAPI === 'cli') {
        throw new RuntimeException($message);
    }

    http_response_code(503);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!doctype html><html lang="sk"><head><meta charset="utf-8"><title>Konfigurácia servera</title></head><body>';
    echo '<h1>Server nie je nakonfigurovaný</h1>';
    echo '<p>' . htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p>';
    echo '<p>Nainštalujte a povoľte PHP rozšírenie <code>pdo_mysql</code>, potom reštartujte webserver.</p>';
    echo '</body></html>';
    exit;
}

function db(): PDO {
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    if (!extension_loaded('pdo_mysql')) {
        fail_dependency('PHP rozšírenie pdo_mysql nie je nainštalované alebo povolené.');
    }

    $cfg = require __DIR__ . '/../config.php';
    $db = $cfg['db'];

    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $db['host'],
        $db['name'],
        $db['charset']
    );

    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        fail_dependency('Pripojenie k databáze zlyhalo: ' . $e->getMessage());
    }

    return $pdo;
}
