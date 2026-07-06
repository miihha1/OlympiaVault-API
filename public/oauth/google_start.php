<?php
require_once __DIR__ . '/../../app/auth.php';
require_once __DIR__ . '/../../vendor/autoload.php';

if (!is_google_oauth_configured()) {
    set_flash('err', 'Google OAuth2 nie je nakonfigurovaný. Doplňte client_id, client_secret a redirect_uri v config.php.');
    redirect_to('/login.php');
}

$cfg = app_config()['google'];
$provider = new League\OAuth2\Client\Provider\Google([
    'clientId' => $cfg['client_id'],
    'clientSecret' => $cfg['client_secret'],
    'redirectUri' => $cfg['redirect_uri'],
]);

$authUrl = $provider->getAuthorizationUrl([
    'prompt' => 'select_account',
    'scope' => ['email', 'profile'],
]);

$_SESSION['oauth2state'] = $provider->getState();
header('Location: ' . $authUrl);
exit;
