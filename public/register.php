<?php
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/totp.php';
require_once __DIR__ . '/../app/validation.php';

if (is_logged_in()) {
    redirect_to('/profile.php');
}

$errors = [];
$values = [
    'first_name' => '',
    'last_name' => '',
    'email' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['first_name'] = trim((string)($_POST['first_name'] ?? ''));
    $values['last_name'] = trim((string)($_POST['last_name'] ?? ''));
    $values['email'] = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    foreach ([
        validate_person_name($values['first_name'], 'Meno'),
        validate_person_name($values['last_name'], 'Priezvisko'),
        validate_email_address($values['email']),
        validate_password_value($password),
    ] as $error) {
        if ($error) {
            $errors[] = $error;
        }
    }

    if (!$errors) {
        $pdo = db();
        $existing = $pdo->prepare('SELECT id FROM users WHERE email = :email');
        $existing->execute([':email' => $values['email']]);

        if ($existing->fetchColumn()) {
            $errors[] = 'Tento e-mail už existuje.';
        } else {
            $secret = totp_generate_secret();
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $insert = $pdo->prepare(
                'INSERT INTO users (first_name, last_name, email, password_hash, totp_secret, totp_enabled)
                 VALUES (:first_name, :last_name, :email, :password_hash, :totp_secret, 0)'
            );
            $insert->execute([
                ':first_name' => $values['first_name'],
                ':last_name' => $values['last_name'],
                ':email' => $values['email'],
                ':password_hash' => $hash,
                ':totp_secret' => $secret,
            ]);

            $_SESSION['setup_2fa_user'] = (int)$pdo->lastInsertId();
            set_flash('info', 'Registrácia prebehla úspešne. Dokončite nastavenie 2FA pre lokálne konto.');
            redirect_to('/setup2fa.php');
        }
    }
}
?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrácia</title>
    <link rel="stylesheet" href="assets/styles.css">
    <script defer src="assets/app.js"></script>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div class="brand">
            <h1>Registrácia<span class="dot">.</span></h1>
            <div class="sub">Vytvorenie lokálneho konta s povinným 2FA</div>
        </div>
        <div class="actions">
            <a class="btn ghost" href="login.php">Prihlásiť</a>
            <a class="btn ghost" href="index.php">Verejná časť</a>
        </div>
    </div>

    <div class="card">
        <?php if ($errors): ?><div class="msg err"><?= h(implode(' ', $errors)) ?></div><?php endif; ?>
        <form method="post" class="filters" novalidate>
            <div class="field">
                <label for="first_name">Meno</label>
                <input id="first_name" name="first_name" maxlength="100" pattern=".{1,100}" value="<?= h($values['first_name']) ?>" required>
            </div>
            <div class="field">
                <label for="last_name">Priezvisko</label>
                <input id="last_name" name="last_name" maxlength="100" pattern=".{1,100}" value="<?= h($values['last_name']) ?>" required>
            </div>
            <div class="field">
                <label for="register_email">E-mail</label>
                <input id="register_email" name="email" type="email" maxlength="190" value="<?= h($values['email']) ?>" required>
            </div>
            <div class="field">
                <label for="register_password">Heslo</label>
                <input id="register_password" name="password" type="password" minlength="8" maxlength="255" required>
            </div>
            <div class="actions">
                <button class="btn primary" type="submit">Registrovať</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>