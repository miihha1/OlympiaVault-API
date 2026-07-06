<?php

$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbName = getenv('DB_NAME') ?: 'webte2_1';
$dbUser = getenv('DB_USER') ?: 'xadamenkom';
$dbPass = getenv('DB_PASS');
$dbCharset = getenv('DB_CHARSET') ?: 'utf8mb4';

$basePath = getenv('APP_BASE_PATH');
if ($basePath === false) {
    $basePath = '/olympians_project/public';
}

$jwtSecret = getenv('JWT_SECRET');
if ($jwtSecret === false || trim($jwtSecret) === '') {
    $jwtSecret = hash('sha256', $dbHost . '|' . $dbName . '|' . $dbUser . '|webte2-z2');
}

$googleClientId = getenv('GOOGLE_CLIENT_ID') ?: '';
$googleClientSecret = getenv('GOOGLE_CLIENT_SECRET') ?: '';
$googleRedirectUri = getenv('GOOGLE_REDIRECT_URI') ?: 'https://node21.webte.fei.stuba.sk/olympians_project/public/oauth/google_callback.php';

return [
    'db' => [
        'host' => $dbHost,
        'name' => $dbName,
        'user' => $dbUser,
        'pass' => ($dbPass === false ? '' : $dbPass),
        'charset' => $dbCharset,
    ],
    'app' => [
        'base_path' => $basePath,
    ],
    'jwt' => [
        'secret' => $jwtSecret,
        'issuer' => 'webte2-olympians-api',
        'ttl' => 7200,
    ],
    'google' => [
        'client_id' => $googleClientId,
        'client_secret' => $googleClientSecret,
        'redirect_uri' => $googleRedirectUri,
    ],
];
