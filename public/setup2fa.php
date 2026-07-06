<?php
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/totp.php';

if (empty($_SESSION['setup_2fa_user'])) {
    redirect_to('/login.php');
}

$uid = (int)$_SESSION['setup_2fa_user'];
$pdo = db();

$u = $pdo->prepare("SELECT id, email, totp_secret FROM users WHERE id = :id");
$u->execute([':id' => $uid]);
$user = $u->fetch();

if (!$user) {
    unset($_SESSION['setup_2fa_user']);
    redirect_to('/login.php');
}

$errors = [];

if (!preg_match('/^[A-Z2-7]+$/', (string)$user['totp_secret'])) {
    $newSecret = totp_generate_secret();

    $pdo->prepare("UPDATE users SET totp_secret = :s, totp_enabled = 0 WHERE id = :id")
        ->execute([
            ':s' => $newSecret,
            ':id' => $uid
        ]);

    $user['totp_secret'] = $newSecret;
}

$otpauth = totp_make($user['totp_secret'], $user['email'] ?: 'WEBTE2')->getProvisioningUri();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code'] ?? '');

    if (!totp_verify($user['totp_secret'], $code)) {
        $errors[] = "Kód nie je správny.";
    } else {
        $pdo->prepare("UPDATE users SET totp_enabled = 1 WHERE id = :id")
            ->execute([':id' => $uid]);

        unset($_SESSION['setup_2fa_user']);
        login_user($uid);
        record_login($uid, $user['email'], 'local');

        redirect_to('/profile.php');
    }
}
?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <title>Nastavenie 2FA</title>
    <link rel="stylesheet" href="assets/styles.css">
    <script defer src="assets/app.js"></script>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div class="brand">
            <h1>2FA<span class="dot">.</span></h1>
            <div class="sub">Nastavenie TOTP pre lokálne konto</div>
        </div>
    </div>

    <div class="card">
        <?php if ($errors): ?>
            <div class="msg err"><?= htmlspecialchars(implode(' ', $errors)) ?></div>
        <?php endif; ?>

        <p class="muted">V aplikácii typu Google Authenticator pridajte nový účet.</p>

        <div class="pills">
            <span class="pill">Secret: <?= htmlspecialchars($user['totp_secret']) ?></span>
        </div>

        <p class="muted">Provisioning URI:</p>
        <div class="msg ok" style="word-break: break-all;">
            <?= htmlspecialchars($otpauth) ?>
        </div>

        <form method="post" class="filters" style="margin-top:14px">
            <div class="field">
                <label>6-miestny kód</label>
                <input name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required>
            </div>
            <div class="actions">
                <button class="btn primary" type="submit">Potvrdiť a aktivovať 2FA</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
