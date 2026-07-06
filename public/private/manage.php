<?php
require_once __DIR__ . '/../../app/auth.php';

require_login();
$me = current_user();
$pdo = db();
$sports = $pdo->query('SELECT DISTINCT category FROM disciplines ORDER BY category ASC')->fetchAll(PDO::FETCH_COLUMN);
$years = $pdo->query('SELECT DISTINCT year FROM olympic_games ORDER BY year DESC')->fetchAll(PDO::FETCH_COLUMN);
?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Správa olympionikov</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <script>
        window.APP_BASE_PATH = <?= json_encode(app_base_path(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    </script>
    <script defer src="../assets/app.js"></script>
    <script defer src="../assets/manage.js"></script>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div class="brand">
            <h1>Správa olympionikov<span class="dot">.</span></h1>
            <div class="sub">Privátna CRUD zóna nad REST API a JWT tokenom</div>
        </div>
        <div class="actions private-user">
            <span class="pill">Prihlásený: <?= h($me['email']) ?></span>
            <span class="pill">Účet: <?= h(current_account_label()) ?></span>
            <a class="btn" href="../index.php">Verejná časť</a>
            <a class="btn" href="import.php">XLSX import</a>
            <a class="btn" href="../api-docs.php">API dokumentácia</a>
            <a class="btn ghost" href="../logout.php">Odhlásiť</a>
        </div>
    </div>

    <div id="toast-root" class="toast-root" aria-live="polite"></div>

    <div class="grid split-layout">
        <section class="card">
            <div class="section-head">
                <h2>Filtrovanie a zoznam</h2>
                <span id="jwt-status" class="pill">JWT: získavam…</span>
            </div>

            <form id="filter-form" class="filters" novalidate>
                <div class="field">
                    <label for="filter-q">Meno alebo priezvisko</label>
                    <input id="filter-q" name="q" type="text" placeholder="napr. Beňuš">
                </div>
                <div class="field">
                    <label for="filter-type">Typ OH</label>
                    <select id="filter-type" name="type">
                        <option value="">-- všetky --</option>
                        <option value="LOH">LOH</option>
                        <option value="ZOH">ZOH</option>
                    </select>
                </div>
                <div class="field">
                    <label for="filter-year">Rok</label>
                    <select id="filter-year" name="year">
                        <option value="">-- všetky --</option>
                        <?php foreach ($years as $year): ?>
                            <option value="<?= (int)$year ?>"><?= (int)$year ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="filter-placing">Umiestnenie</label>
                    <input id="filter-placing" name="placing" type="number" min="1" max="999" placeholder="napr. 1">
                </div>
                <div class="field">
                    <label for="filter-sport">Šport</label>
                    <select id="filter-sport" name="sport">
                        <option value="">-- všetky --</option>
                        <?php foreach ($sports as $sport): ?>
                            <option value="<?= h($sport) ?>"><?= h($sport) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="actions">
                    <button class="btn primary" type="submit">Načítať cez API</button>
                    <button id="filter-reset" class="btn ghost" type="button">Reset</button>
                </div>
            </form>

            <div class="pills">
                <span id="results-count" class="pill">Záznamy: 0</span>
            </div>

            <div class="tablewrap">
                <table>
                    <thead>
                    <tr>
                        <th>Olympionik</th>
                        <th>Základné údaje</th>
                        <th>Ocenenia</th>
                        <th>Akcie</th>
                    </tr>
                    </thead>
                    <tbody id="athletes-table-body">
                    <tr>
                        <td colspan="4" class="muted">Načítavam dáta z API…</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card">
            <div class="section-head">
                <h2 id="form-title">Pridať olympionika</h2>
                <div class="actions">
                    <button id="new-athlete-btn" class="btn ghost" type="button">Nový formulár</button>
                </div>
            </div>

            <form id="athlete-form" class="stack-form" novalidate>
                <input type="hidden" id="athlete-id" name="athlete_id">

                <div class="form-grid">
                    <div class="field">
                        <label for="first-name">Meno</label>
                        <input id="first-name" name="first_name" type="text" maxlength="100" required>
                    </div>
                    <div class="field">
                        <label for="last-name">Priezvisko</label>
                        <input id="last-name" name="last_name" type="text" maxlength="100" required>
                    </div>
                    <div class="field">
                        <label for="birth-date">Dátum narodenia</label>
                        <input id="birth-date" name="birth_date" type="date">
                    </div>
                    <div class="field">
                        <label for="birth-place">Miesto narodenia</label>
                        <input id="birth-place" name="birth_place" type="text" maxlength="191">
                    </div>
                    <div class="field">
                        <label for="birth-country">Krajina narodenia</label>
                        <input id="birth-country" name="birth_country" type="text" maxlength="191">
                    </div>
                    <div class="field">
                        <label for="death-date">Dátum úmrtia</label>
                        <input id="death-date" name="death_date" type="date">
                    </div>
                    <div class="field">
                        <label for="death-place">Miesto úmrtia</label>
                        <input id="death-place" name="death_place" type="text" maxlength="191">
                    </div>
                    <div class="field">
                        <label for="death-country">Krajina úmrtia</label>
                        <input id="death-country" name="death_country" type="text" maxlength="191">
                    </div>
                </div>

                <div class="section-head compact-head">
                    <h2>Ocenenia a výsledky</h2>
                    <button id="add-award-btn" class="btn" type="button">Pridať riadok</button>
                </div>
                <div id="awards-list" class="award-list"></div>

                <div class="actions">
                    <button class="btn primary" type="submit">Uložiť cez API</button>
                    <button id="delete-athlete-btn" class="btn danger hidden" type="button">Vymazať olympionika</button>
                </div>
            </form>
        </section>
    </div>

    <div class="grid import-grid">
        <section class="card">
            <div class="section-head">
                <h2>Hromadný import JSON</h2>
            </div>
            <p class="muted">Nahrajte JSON pole objektov. Každý objekt má rovnakú štruktúru ako pri API vytvárania jedného olympionika.</p>
            <form id="json-import-form" class="filters" novalidate>
                <div class="field">
                    <label for="json-file">JSON súbor</label>
                    <input id="json-file" name="json_file" type="file" accept=".json,application/json" required>
                </div>
                <div class="actions">
                    <button class="btn primary" type="submit">Importovať JSON</button>
                </div>
            </form>
        </section>
    </div>
</div>

<template id="award-template">
    <div class="award-row">
        <div class="award-grid">
            <div class="field">
                <label>Typ OH</label>
                <select name="award_type" required>
                    <option value="LOH">LOH</option>
                    <option value="ZOH">ZOH</option>
                </select>
            </div>
            <div class="field">
                <label>Rok</label>
                <input name="award_year" type="number" min="1896" max="2100" required>
            </div>
            <div class="field">
                <label>Umiestnenie</label>
                <input name="award_placing" type="number" min="1" max="999" required>
            </div>
            <div class="field">
                <label>Kategória</label>
                <input name="award_category" type="text" maxlength="191" required>
            </div>
            <div class="field">
                <label>Disciplína</label>
                <input name="award_discipline" type="text" maxlength="191" required>
            </div>
            <div class="field">
                <label>Mesto</label>
                <input name="award_city" type="text" maxlength="191" required>
            </div>
            <div class="field">
                <label>Hostiteľská krajina</label>
                <input name="award_host_country" type="text" maxlength="191" required>
            </div>
            <div class="field">
                <label>Reprezentovaná krajina</label>
                <input name="award_represented_country" type="text" maxlength="191">
            </div>
        </div>
        <div class="actions">
            <button class="btn ghost remove-award-btn" type="button">Odstrániť riadok</button>
        </div>
    </div>
</template>
</body>
</html>
