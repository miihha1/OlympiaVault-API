<?php
require_once __DIR__ . '/../../app/auth.php';
require_once __DIR__ . '/../../vendor/autoload.php';

if (!is_google_oauth_configured()) {
    set_flash('err', 'Google OAuth2 nie je nakonfigurovaný.');
    redirect_to('/login.php');
}

$cfg = app_config()['google'];
$provider = new League\OAuth2\Client\Provider\Google([
    'clientId' => $cfg['client_id'],
    'clientSecret' => $cfg['client_secret'],
    'redirectUri' => $cfg['redirect_uri'],
]);

if (empty($_GET['state']) || ($_GET['state'] !== ($_SESSION['oauth2state'] ?? null))) {
    unset($_SESSION['oauth2state']);
    set_flash('err', 'OAuth2 stav nie je platný. Skúste prihlásenie znova.');
    redirect_to('/login.php');
}
unset($_SESSION['oauth2state']);

try {
    $token = $provider->getAccessToken('authorization_code', ['code' => $_GET['code'] ?? '']);
    $owner = $provider->getResourceOwner($token);

    $email = (string)$owner->getEmail();
    $sub = (string)$owner->getId();
    $firstName = $owner->getFirstName() ?: 'Google';
    $lastName = $owner->getLastName() ?: 'User';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $sub === '') {
        throw new RuntimeException('Google neposkytol platné identifikačné údaje.');
    }

    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, email FROM users WHERE oauth_provider = "google" AND oauth_sub = :sub');
    $stmt->execute([':sub' => $sub]);
    $user = $stmt->fetch();

    if (!$user) {
        $byEmail = $pdo->prepare('SELECT id, email FROM users WHERE email = :email');
        $byEmail->execute([':email' => $email]);
        $user = $byEmail->fetch();

        if ($user) {
            $pdo->prepare('UPDATE users SET oauth_provider = "google", oauth_sub = :sub WHERE id = :id')
                ->execute([':sub' => $sub, ':id' => $user['id']]);
        } else {
            $insert = $pdo->prepare(
                'INSERT INTO users (first_name, last_name, email, oauth_provider, oauth_sub, totp_enabled)
                 VALUES (:first_name, :last_name, :email, "google", :oauth_sub, 0)'
            );
            $insert->execute([
                ':first_name' => $firstName,
                ':last_name' => $lastName,
                ':email' => $email,
                ':oauth_sub' => $sub,
            ]);
            $user = ['id' => (int)$pdo->lastInsertId(), 'email' => $email];
        }
    }

    login_user((int)$user['id'], 'google');
    record_login((int)$user['id'], $email, 'google');
    redirect_to('/profile.php');
} catch (Throwable $e) {
    set_flash('err', 'Prihlásenie cez Google zlyhalo. Skontrolujte konfiguráciu OAuth2.');
    redirect_to('/login.php');
}