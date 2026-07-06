<?php
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/validation.php';

require_login();
$pdo = db();
$me = current_user();
$errors = [];
$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_name') {
        $firstName = trim((string)($_POST['first_name'] ?? ''));
        $lastName = trim((string)($_POST['last_name'] ?? ''));

        foreach ([
            validate_person_name($firstName, 'Meno'),
            validate_person_name($lastName, 'Priezvisko'),
        ] as $error) {
            if ($error) {
                $errors[] = $error;
            }
        }

        if (!$errors) {
            $pdo->prepare('UPDATE users SET first_name = :first_name, last_name = :last_name WHERE id = :id')
                ->execute([
                    ':first_name' => $firstName,
                    ':last_name' => $lastName,
                    ':id' => $me['id'],
                ]);
            $message = 'Údaje boli úspešne uložené.';
            $me = current_user();
        }
    }

    if ($action === 'change_password') {
        $password1 = (string)($_POST['pass1'] ?? '');
        $password2 = (string)($_POST['pass2'] ?? '');

        if ($error = validate_password_value($password1)) {
            $errors[] = $error;
        }
        if ($password1 !== $password2) {
            $errors[] = 'Heslá sa nezhodujú.';
        }

        if (!$errors) {
            $hash = password_hash($password1, PASSWORD_DEFAULT);
            $pdo->prepare('UPDATE users SET password_hash = :hash WHERE id = :id')
                ->execute([':hash' => $hash, ':id' => $me['id']]);
            $message = 'Heslo bolo úspešne zmenené.';
        }
    }
}

$historyStmt = $pdo->prepare(
    'SELECT logged_at, method, identifier, ip
     FROM login_history
     WHERE user_id = :id
     ORDER BY logged_at DESC
     LIMIT 50'
);
$historyStmt->execute([':id' => $me['id']]);
$history = $historyStmt->fetchAll();
?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil</title>
    <link rel="stylesheet" href="assets/styles.css">
    <script defer src="assets/app.js"></script>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div class="brand">
            <h1>Privátna zóna<span class="dot">.</span></h1>
            <div class="sub">Vitajte, <?= h($me['first_name'] . ' ' . $me['last_name']) ?>.</div>
        </div>
        <div class="actions private-user">
            <span class="pill">Prihlásený: <?= h($me['email']) ?></span>
            <span class="pill">Účet: <?= h(current_account_label()) ?></span>
            <a class="btn" href="index.php">Verejná časť</a>
            <a class="btn" href="private/import.php">Import</a>
            <a class="btn" href="private/delete_data.php">Vymazať údaje</a>
            <a class="btn ghost" href="logout.php">Odhlásiť</a>
        </div>
    </div>

    <?php if ($message): ?><div class="msg ok"><?= h($message) ?></div><?php endif; ?>
    <?php if ($errors): ?><div class="msg err"><?= h(implode(' ', $errors)) ?></div><?php endif; ?>

    <div class="grid">
        <div class="card">
            <h2>Kto je prihlásený</h2>
            <div class="pills">
                <span class="pill">Meno: <?= h($me['first_name']) ?></span>
                <span class="pill">Priezvisko: <?= h($me['last_name']) ?></span>
                <span class="pill">E-mail: <?= h($me['email']) ?></span>
                <span class="pill">Aktuálne konto: <?= h(current_account_label()) ?></span>
            </div>
        </div>

        <div class="card">
            <h2>Zmena mena a priezviska</h2>
            <form method="post" class="filters" novalidate>
                <input type="hidden" name="action" value="update_name">
                <div class="field">
                    <label for="first_name">Meno</label>
                    <input id="first_name" name="first_name" maxlength="100" pattern=".{1,100}" value="<?= h($me['first_name']) ?>" required>
                </div>
                <div class="field">
                    <label for="last_name">Priezvisko</label>
                    <input id="last_name" name="last_name" maxlength="100" pattern=".{1,100}" value="<?= h($me['last_name']) ?>" required>
                </div>
                <div class="actions">
                    <button class="btn primary" type="submit">Uložiť</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Zmena hesla</h2>
            <form method="post" class="filters" novalidate>
                <input type="hidden" name="action" value="change_password">
                <div class="field">
                    <label for="pass1">Nové heslo</label>
                    <input id="pass1" name="pass1" type="password" minlength="8" maxlength="255" required>
                </div>
                <div class="field">
                    <label for="pass2">Nové heslo znova</label>
                    <input id="pass2" name="pass2" type="password" minlength="8" maxlength="255" required>
                </div>
                <div class="actions">
                    <button class="btn primary" type="submit">Zmeniť heslo</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>História prihlásení</h2>
            <div class="tablewrap">
                <table>
                    <thead>
                    <tr>
                        <th>Dátum a čas</th>
                        <th>Spôsob</th>
                        <th>Identifikátor</th>
                        <th>IP adresa</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!$history): ?>
                        <tr>
                            <td colspan="4">História prihlásení je zatiaľ prázdna.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($history as $item): ?>
                        <tr>
                            <td><?= h($item['logged_at']) ?></td>
                            <td><?= h($item['method']) ?></td>
                            <td><?= h($item['identifier']) ?></td>
                            <td><?= h($item['ip'] ?: '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
