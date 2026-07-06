<?php
require_once __DIR__ . '/auth.php';

function api_json(mixed $payload, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function api_error(string $message, int $status = 400, array $extra = []): void {
    api_json(array_merge([
        'ok' => false,
        'error' => $message,
    ], $extra), $status);
}

function api_success(array $payload = [], int $status = 200): void {
    api_json(array_merge(['ok' => true], $payload), $status);
}

function api_read_json_body(): array {
    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $decoded = json_decode($raw, true);
    if (!is_array($decoded)) {
        api_error('Neplatné JSON telo požiadavky.', 400);
    }

    return $decoded;
}

function api_base64url_encode(string $data): string {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function api_base64url_decode(string $data): string|false {
    $padding = strlen($data) % 4;
    if ($padding > 0) {
        $data .= str_repeat('=', 4 - $padding);
    }
    return base64_decode(strtr($data, '-_', '+/'), true);
}

function api_jwt_config(): array {
    $cfg = app_config()['jwt'] ?? [];
    return [
        'secret' => (string)($cfg['secret'] ?? ''),
        'issuer' => (string)($cfg['issuer'] ?? 'webte2-api'),
        'ttl' => (int)($cfg['ttl'] ?? 3600),
    ];
}

function api_issue_jwt(array $user): string {
    $cfg = api_jwt_config();
    $issuedAt = time();
    $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    $payload = [
        'iss' => $cfg['issuer'],
        'iat' => $issuedAt,
        'nbf' => $issuedAt,
        'exp' => $issuedAt + $cfg['ttl'],
        'sub' => (string)$user['id'],
        'email' => (string)$user['email'],
        'name' => trim(((string)$user['first_name']) . ' ' . ((string)$user['last_name'])),
    ];

    $encodedHeader = api_base64url_encode(json_encode($header, JSON_UNESCAPED_UNICODE));
    $encodedPayload = api_base64url_encode(json_encode($payload, JSON_UNESCAPED_UNICODE));
    $signature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $cfg['secret'], true);

    return $encodedHeader . '.' . $encodedPayload . '.' . api_base64url_encode($signature);
}

function api_decode_jwt(string $token): ?array {
    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        return null;
    }

    [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;
    $headerJson = api_base64url_decode($encodedHeader);
    $payloadJson = api_base64url_decode($encodedPayload);
    $signature = api_base64url_decode($encodedSignature);
    if ($headerJson === false || $payloadJson === false || $signature === false) {
        return null;
    }

    $header = json_decode($headerJson, true);
    $payload = json_decode($payloadJson, true);
    if (!is_array($header) || !is_array($payload) || ($header['alg'] ?? null) !== 'HS256') {
        return null;
    }

    $cfg = api_jwt_config();
    $expected = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $cfg['secret'], true);
    if (!hash_equals($expected, $signature)) {
        return null;
    }

    $now = time();
    if (($payload['nbf'] ?? 0) > $now || ($payload['exp'] ?? 0) < $now) {
        return null;
    }

    return $payload;
}

function api_bearer_token(): ?string {
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    if (preg_match('/Bearer\s+(.+)/i', $header, $matches)) {
        return trim($matches[1]);
    }
    return null;
}

function api_require_jwt_user(): array {
    $token = api_bearer_token();
    if (!$token) {
        api_error('Chýba Bearer token.', 401);
    }

    $payload = api_decode_jwt($token);
    if (!$payload || empty($payload['sub'])) {
        api_error('JWT token je neplatný alebo expirovaný.', 401);
    }

    $pdo = db();
    $st = $pdo->prepare('SELECT id, first_name, last_name, email FROM users WHERE id = :id');
    $st->execute([':id' => (int)$payload['sub']]);
    $user = $st->fetch();
    if (!$user) {
        api_error('Používateľ z JWT tokenu neexistuje.', 401);
    }

    return $user;
}
