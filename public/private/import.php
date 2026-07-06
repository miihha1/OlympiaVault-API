<?php
require_once __DIR__ . '/../../app/auth.php';
require_once __DIR__ . '/../../app/importer.php';

require_login();
$me = current_user();
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['xlsx'])) {
    $file = $_FILES['xlsx'];

    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        $result = ['ok' => false, 'error' => 'Chyba pri nahrávaní súboru.'];
    } elseif (($file['size'] ?? 0) <= 0 || ($file['size'] ?? 0) > 10 * 1024 * 1024) {
        $result = ['ok' => false, 'error' => 'Súbor musí mať veľkosť 1 B až 10 MB.'];
    } else {
        $extension = strtolower(pathinfo((string)$file['name'], PATHINFO_EXTENSION));
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file((string)$file['tmp_name']);
        $allowedMimes = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/octet-stream',
            'application/zip',
        ];

        if ($extension !== 'xlsx' || !in_array($mime, $allowedMimes, true)) {
            $result = ['ok' => false, 'error' => 'Povolené sú iba XLSX súbory.'];
        } else {
            $result = importXlsx((string)$file['tmp_name']);
        }
    }
}
?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Import údajov</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <script defer src="../assets/app.js"></script>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div class="brand">
            <h1>Import údajov<span class="dot">.</span></h1>
            <div class="sub">Privátna zóna — nahratie súboru oh_v2.xlsx</div>
        </div>
        <div class="actions private-user">
            <span class="pill">Prihlásený: <?= h($me['email']) ?></span>
            <span class="pill">Účet: <?= h(current_account_label()) ?></span>
            <a class="btn" href="../index.php">Verejná časť</a>
            <a class="btn" href="../profile.php">Profil</a>
            <a class="btn" href="manage.php">Správa API</a>
            <a class="btn" href="../api-docs.php">API docs</a>
            <a class="btn" href="delete_data.php">Vymazať údaje</a>
            <a class="btn ghost" href="../logout.php">Odhlásiť</a>
        </div>
    </div>

    <div class="card">
        <h2>Import z XLSX</h2>
        <p class="muted">Do databázy sa dynamicky načítajú údaje zo súboru vo formáte XLSX. Import je dostupný iba po prihlásení.</p>

        <?php if ($result): ?>
            <div class="msg <?= $result['ok'] ? 'ok' : 'err' ?>">
                <?php if ($result['ok']): ?>
                    Import prebehol úspešne. Načítané riadky z OH: <?= (int)$result['games_rows'] ?>, riadky zo športovcov: <?= (int)$result['people_rows'] ?>.
                <?php else: ?>
                    Chyba importu: <?= h($result['error']) ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="filters" novalidate>
            <div class="field">
                <label for="xlsx">XLSX súbor</label>
                <input id="xlsx" type="file" name="xlsx" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
            </div>
            <div class="actions">
                <button class="btn primary" type="submit">Importovať</button>
                <a class="btn ghost" href="../index.php">Späť na verejnú časť</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
