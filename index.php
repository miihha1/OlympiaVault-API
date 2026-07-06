<?php
$cfg = require __DIR__ . '/config.php';
$basePath = trim((string)($cfg['app']['base_path'] ?? '/public'));
if ($basePath === '/' || $basePath === '.') {
    $basePath = '';
}
$basePath = rtrim($basePath, '/');

header('Location: ' . $basePath . '/index.php', true, 302);
exit;
