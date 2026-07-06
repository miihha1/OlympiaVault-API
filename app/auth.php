<?php
require_once __DIR__ . '/db.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function app_config(): array {
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require __DIR__ . '/../config.php';
    }
    return $cfg;
}

function app_base_path(): string {
    $cfg = app_config();
    $base = (string)($cfg['app']['base_path'] ?? '');
    $base = trim($base);

    if ($base === '/' || $base === '.') {
        return '';
    }

    return rtrim($base, '/');
}

function url(string $path = ''): string {
    return app_base_path() . $path;
}

function redirect_to(string $path): void {
    header('Location: ' . url($path));
    exit;
}

function h(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function is_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

function require_login(): void {
    if (!is_logged_in()) {
        redirect_to('/login.php');
    }
}

function current_user(): ?array {
    if (!is_logged_in()) {
        return null;
    }

    $pdo = db();
    $st = $pdo->prepare(
        'SELECT id, first_name, last_name, email, oauth_provider,
                CASE WHEN password_hash IS NULL OR password_hash = "" THEN 0 ELSE 1 END AS has_local_password
         FROM users
         WHERE id = :id'
    );
    $st->execute([':id' => (int)$_SESSION['user_id']]);
    $user = $st->fetch();

    return $user ?: null;
}

function current_login_method(): ?string {
    return $_SESSION['login_method'] ?? null;
}

function current_account_label(): string {
    return current_login_method() === 'google' ? 'Google konto' : 'Lokálne konto';
}

function login_user(int $userId, string $method = 'local'): void {
    $_SESSION['user_id'] = $userId;
    $_SESSION['login_method'] = $method;
    unset($_SESSION['pending_2fa_user'], $_SESSION['setup_2fa_user']);
    session_regenerate_id(true);
}

function logout_user(): void {
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

function record_login(int $userId, string $identifier, string $method): void {
    $pdo = db();
    $st = $pdo->prepare(
        'INSERT INTO login_history (user_id, identifier, method, ip, user_agent)
         VALUES (:uid, :ident, :method, :ip, :ua)'
    );
    $st->execute([
        ':uid' => $userId,
        ':ident' => $identifier,
        ':method' => $method,
        ':ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ':ua' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
    ]);
}

function is_google_oauth_configured(): bool {
    $cfg = app_config()['google'] ?? [];
    $clientId = (string)($cfg['client_id'] ?? '');
    $clientSecret = (string)($cfg['client_secret'] ?? '');
    $redirectUri = (string)($cfg['redirect_uri'] ?? '');

    return $clientId !== ''
        && $clientSecret !== ''
        && $redirectUri !== ''
        && !str_contains($clientId, 'PASTE_')
        && !str_contains($clientSecret, 'PASTE_');
}

function set_flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function pull_flash(): ?array {
    if (empty($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}