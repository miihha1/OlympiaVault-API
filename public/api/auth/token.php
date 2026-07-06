<?php
require_once __DIR__ . '/../../../app/api.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_error('Povolená je iba metóda POST.', 405);
}

$pdo = db();
$payload = api_read_json_body();
$user = null;

if (is_logged_in() && empty($payload['email']) && empty($payload['password'])) {
    $user = current_user();
}

if (!$user) {
    $email = trim((string)($payload['email'] ?? ''));
    $password = (string)($payload['password'] ?? '');

    if ($email === '' || $password === '') {
        api_error('Pre získanie JWT tokenu zadajte e-mail a heslo alebo buďte prihlásený.', 400);
    }

    $st = $pdo->prepare(
        'SELECT id, first_name, last_name, email, password_hash
         FROM users
         WHERE email = :email
         LIMIT 1'
    );
    $st->execute([':email' => $email]);
    $candidate = $st->fetch();

    if (!$candidate || empty($candidate['password_hash']) || !password_verify($password, $candidate['password_hash'])) {
        api_error('Neplatné prihlasovacie údaje.', 401);
    }

    $user = $candidate;
}

$token = api_issue_jwt($user);
api_success([
    'token' => $token,
    'token_type' => 'Bearer',
    'expires_in' => api_jwt_config()['ttl'],
    'user' => [
        'id' => (int)$user['id'],
        'email' => $user['email'],
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
    ],
]);
