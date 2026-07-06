<?php
require_once __DIR__ . '/../../app/auth.php';

require_login();
$me = current_user();
$pdo = db();
$done = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        $pdo->exec('DELETE FROM athlete_medals');
        $pdo->exec('DELETE FROM athletes');
        $pdo->exec('DELETE FROM disciplines');
        $pdo->exec('DELETE FROM medal_types');
        $pdo->exec('DELETE FROM olympic_games');
        $pdo->exec('DELETE FROM countries');
        $pdo->commit();
        $done = true;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $error = $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vymazanie údajov</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <script defer src="../assets/app.js"></script>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div class="brand">
            <h1>Vymazanie údajov<span class="dot">.</span></h1>
            <div class="sub">Po vymazaní ostáva opätovný import zachovaný.</div>
        </div>
        <div class="actions private-user">
            <span class="pill">Prihlásený: <?= h($me['email']) ?></span>
            <span class="pill">Účet: <?= h(current_account_label()) ?></span>
            <a class="btn" href="../index.php">Verejná časť</a>
            <a class="btn" href="import.php">Import</a>
            <a class="btn" href="manage.php">Správa API</a>
            <a class="btn" href="../api-docs.php">API docs</a>
            <a class="btn" href="../profile.php">Profil</a>
            <a class="btn ghost" href="../logout.php">Odhlásiť</a>
        </div>
    </div>

    <div class="card">
        <?php if ($done): ?><div class="msg ok">Údaje boli úspešne vymazané. Môžete ich znova naimportovať.</div><?php endif; ?>
        <?php if ($error): ?><div class="msg err">Vymazanie zlyhalo: <?= h($error) ?></div><?php endif; ?>

        <p class="muted">Táto akcia vymaže importované olympijské údaje, ale neodstráni používateľské účty ani históriu prihlásení.</p>

        <form method="post" class="filters" novalidate>
            <div class="actions">
                <button class="btn danger" type="submit">Vymazať importované údaje</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
