<?php
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/totp.php';
require_once __DIR__ . '/../app/validation.php';

if (empty($_SESSION['pending_2fa_user'])) {
    redirect_to('/login.php');
}

$uid = (int)$_SESSION['pending_2fa_user'];
$pdo = db();
$stmt = $pdo->prepare('SELECT id, email, totp_secret, totp_enabled FROM users WHERE id = :id');
$stmt->execute([':id' => $uid]);
$user = $stmt->fetch();
if (!$user) {
    unset($_SESSION['pending_2fa_user']);
    redirect_to('/login.php');
}

if ((int)$user['totp_enabled'] !== 1) {
    unset($_SESSION['pending_2fa_user']);
    $_SESSION['setup_2fa_user'] = $uid;
    set_flash('info', 'Pred prihlásením je potrebné dokončiť nastavenie 2FA.');
    redirect_to('/setup2fa.php');
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim((string)($_POST['code'] ?? ''));

    if ($error = validate_totp_value($code)) {
        $errors[] = $error;
    } elseif (!totp_verify($user['totp_secret'], $code)) {
        $errors[] = 'Kód nie je správny.';
    } else {
        unset($_SESSION['pending_2fa_user']);
        login_user($uid, 'local');
        record_login($uid, $user['email'], 'local');
        redirect_to('/profile.php');
    }
}
?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Overenie 2FA</title>
    <link rel="stylesheet" href="assets/styles.css">
    <script defer src="assets/app.js"></script>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div class="brand">
            <h1>2FA<span class="dot">.</span></h1>
            <div class="sub">Zadajte kód z autentifikátora</div>
        </div>
    </div>

    <div class="card">
        <?php if ($errors): ?><div class="msg err"><?= h(implode(' ', $errors)) ?></div><?php endif; ?>
        <form method="post" class="filters" novalidate>
            <div class="field">
                <label for="totp_code">6-miestny kód</label>
                <input id="totp_code" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required>
            </div>
            <div class="actions">
                <button class="btn primary" type="submit">Overiť</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>