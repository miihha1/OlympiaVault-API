<?php
require_once __DIR__ . '/../app/auth.php';

$errors = [];
$flash = pull_flash();
$googleReady = is_google_oauth_configured();

if (!$googleReady && $flash && ($flash['type'] ?? '') === 'err') {
    $msg = (string)($flash['message'] ?? '');
    if (str_contains($msg, 'Google OAuth2 nie je nakonfigurovaný')) {
        $flash = null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = (string)($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Neplatný e-mail.";
    }
    if ($pass === '') {
        $errors[] = "Zadajte heslo.";
    }

    if (!$errors) {
        $pdo = db();
        $st = $pdo->prepare("SELECT id, email, password_hash, totp_enabled FROM users WHERE email = :e");
        $st->execute([':e' => $email]);
        $u = $st->fetch();

        if (!$u || empty($u['password_hash']) || !password_verify($pass, $u['password_hash'])) {
            $errors[] = "Nesprávny e-mail alebo heslo.";
        } else {
            if ((int)$u['totp_enabled'] !== 1) {
                $_SESSION['setup_2fa_user'] = (int)$u['id'];
                redirect_to('/setup2fa.php');
            }

            $_SESSION['pending_2fa_user'] = (int)$u['id'];
            redirect_to('/verify2fa.php');
        }
    }
}
?>
<!doctype html>
<html lang="sk">
<head>
<meta charset="utf-8">
<title>Prihlásenie</title>
<link rel="stylesheet" href="assets/styles.css">
<script defer src="assets/app.js"></script>
</head>
<body>
<div class="container">
  <div class="topbar">
    <div class="brand">
      <h1>Prihlásenie<span class="dot">.</span></h1>
      <div class="sub"><?= $googleReady ? 'Local alebo Google' : 'Lokálne konto' ?></div>
    </div>
    <div class="actions">
      <a class="btn ghost" href="register.php">Registrácia</a>
    </div>
  </div>

  <div class="card">
    <?php if ($flash): ?>
      <div class="msg <?= $flash['type'] === 'err' ? 'err' : 'info' ?>"><?= h($flash['message'] ?? '') ?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="msg err"><?= htmlspecialchars(implode(" ", $errors)) ?></div>
    <?php endif; ?>

    <form method="post" class="filters">
      <div class="field">
        <label>E-mail</label>
        <input name="email" type="email" required>
      </div>
      <div class="field">
        <label>Heslo</label>
        <input name="password" type="password" required>
      </div>
      <div class="actions">
        <button class="btn primary" type="submit">Prihlásiť</button>
        <?php if ($googleReady): ?>
          <a class="btn" href="oauth/google_start.php">Prihlásiť cez Google</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>
</body>
</html>
