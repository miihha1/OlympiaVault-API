<?php
require_once __DIR__ . '/../app/auth.php';
$me = current_user();
?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>API dokumentácia</title>
    <link rel="stylesheet" href="assets/styles.css">
    <script defer src="assets/app.js"></script>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div class="brand">
            <h1>API dokumentácia<span class="dot">.</span></h1>
            <div class="sub">REST služba pre správu slovenských olympionikov</div>
        </div>
        <div class="actions">
            <a class="btn" href="index.php">Verejná časť</a>
            <?php if ($me): ?>
                <a class="btn" href="private/manage.php">Správa olympionikov</a>
                <a class="btn ghost" href="logout.php">Odhlásiť</a>
            <?php else: ?>
                <a class="btn" href="login.php">Prihlásiť</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid">
        <section class="card">
            <h2>Autentifikácia</h2>
            <p class="muted">Všetky API požiadavky okrem získania tokenu vyžadujú JWT token v hlavičke <code>Authorization: Bearer &lt;token&gt;</code>.</p>
            <pre class="codeblock"><code>POST /public/api/auth/token.php
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "secret123"
}</code></pre>
            <pre class="codeblock"><code>{
  "ok": true,
  "token": "eyJ...",
  "token_type": "Bearer",
  "expires_in": 7200
}</code></pre>
        </section>

        <section class="card">
            <h2>Endpointy</h2>
            <div class="doc-list">
                <div class="doc-item">
                    <strong>GET /public/api/olympians.php</strong>
                    <p class="muted">Zoznam ocenených olympionikov. Podporené filtre: <code>type</code>, <code>year</code>, <code>placing</code>, <code>sport</code>, <code>q</code>.</p>
                </div>
                <div class="doc-item">
                    <strong>GET /public/api/olympians.php?id=12</strong>
                    <p class="muted">Detail jedného olympionika vrátane všetkých ocenení.</p>
                </div>
                <div class="doc-item">
                    <strong>POST /public/api/olympians.php</strong>
                    <p class="muted">Pridanie jedného olympionika so všetkými dostupnými údajmi a ľubovoľným počtom ocenení.</p>
                </div>
                <div class="doc-item">
                    <strong>PUT /public/api/olympians.php?id=12</strong>
                    <p class="muted">Úprava údajov olympionika aj jeho ocenení.</p>
                </div>
                <div class="doc-item">
                    <strong>DELETE /public/api/olympians.php?id=12</strong>
                    <p class="muted">Vymazanie olympionika. Naviazané výsledky sa vymažú spolu s ním.</p>
                </div>
                <div class="doc-item">
                    <strong>POST /public/api/olympians-import.php</strong>
                    <p class="muted">Hromadné pridanie viacerých olympionikov na základe JSON súboru alebo JSON poľa.</p>
                </div>
            </div>
        </section>

        <section class="card">
            <h2>Príklad vytvorenia olympionika</h2>
            <pre class="codeblock"><code>{
  "first_name": "Matej",
  "last_name": "Beňuš",
  "birth_date": "1987-11-02",
  "birth_place": "Bratislava",
  "birth_country": "Slovensko",
  "awards": [
    {
      "type": "LOH",
      "year": 2024,
      "placing": 3,
      "category": "vodný slalom",
      "discipline": "C1",
      "city": "Paríž",
      "host_country": "Francúzsko",
      "represented_country": "Slovensko"
    }
  ]
}</code></pre>
        </section>

        <section class="card">
            <h2>HTTP stavy</h2>
            <p class="muted"><code>200</code> úspech, <code>201</code> vytvorenie, <code>207</code> čiastočný úspech pri hromadnom importe, <code>400</code> chybná požiadavka, <code>401</code> neplatný alebo chýbajúci JWT token, <code>404</code> záznam neexistuje, <code>405</code> nepovolená metóda, <code>422</code> validačná chyba, <code>500</code> chyba servera.</p>
        </section>
    </div>
</div>
</body>
</html>
