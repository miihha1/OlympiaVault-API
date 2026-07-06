<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/db.php';
$pdo = db();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { http_response_code(404); exit("Bad id"); }

$ath = $pdo->prepare("
SELECT a.*, bc.name AS birth_country, dc.name AS death_country
FROM athletes a
LEFT JOIN countries bc ON bc.id = a.birth_country_id
LEFT JOIN countries dc ON dc.id = a.death_country_id
WHERE a.id = :id
");
$ath->execute([':id'=>$id]);
$athlete = $ath->fetch();
if (!$athlete) { http_response_code(404); exit("Not found"); }

$med = $pdo->prepare("
SELECT
    am.placing,
    d.category,
    d.name AS discipline,
    g.type,
    g.year,
    g.city,
    COALESCE(rc.name, '-') AS represented_country,
    hc.name AS host_country
FROM athlete_medals am
JOIN disciplines d ON d.id = am.discipline_id
JOIN olympic_games g ON g.id = am.olympic_games_id
JOIN countries hc ON hc.id = g.country_id
LEFT JOIN countries rc ON rc.id = am.represented_country_id
WHERE am.athlete_id = :id
ORDER BY g.year DESC, am.placing ASC
");
$med->execute([':id'=>$id]);
$rows = $med->fetchAll();
?>
<!doctype html>
<html lang="sk">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($athlete['last_name'].' '.$athlete['first_name']) ?></title>
  <link rel="stylesheet" href="assets/styles.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <script defer src="assets/app.js"></script>
</head>
<body>
  <div class="container">
    <div class="topbar">
      <div class="brand">
        <div class="logo"></div>
        <div>
          <h1><?= htmlspecialchars($athlete['last_name'].' '.$athlete['first_name']) ?></h1>
          <div class="sub">Detail športovca</div>
        </div>
      </div>
      <div class="actions">
        <a class="btn ghost" href="index.php">← Späť</a>
      </div>
    </div>

    <div class="grid">
      <div class="card">
        <h2>Základné údaje</h2>
        <div class="kv">
          <div class="item">
            <div class="k">Narodenie</div>
            <div class="v">
              <?= htmlspecialchars($athlete['birth_date'] ?: '-') ?>,
              <?= htmlspecialchars($athlete['birth_place'] ?: '-') ?>
              (<?= htmlspecialchars($athlete['birth_country'] ?: '-') ?>)
            </div>
          </div>
          <div class="item">
            <div class="k">Úmrtie</div>
            <div class="v">
              <?= htmlspecialchars($athlete['death_date'] ?: '-') ?>,
              <?= htmlspecialchars($athlete['death_place'] ?: '-') ?>
              (<?= htmlspecialchars($athlete['death_country'] ?: '-') ?>)
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <h2>Ocenenia / výsledky</h2>
        <div class="tablewrap">
          <table>
            <thead>
              <tr>
                <th>Umiestnenie</th>
                <th>Kategória</th>
                <th>Disciplína</th>
                <th>OH</th>
                <th>Mesto</th>
                <th>Reprezentoval</th>
                <th>Hostiteľská krajina OH</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rows as $r): ?>
                <tr>
                  <td><?= (int)$r['placing'] ?></td>
                  <td><?= htmlspecialchars($r['category']) ?></td>
                  <td><?= htmlspecialchars($r['discipline']) ?></td>
                  <td><?= htmlspecialchars($r['type'].' '.$r['year']) ?></td>
                  <td><?= htmlspecialchars($r['city']) ?></td>
                  <td><?= htmlspecialchars($r['represented_country']) ?></td>
                  <td><?= htmlspecialchars($r['host_country']) ?></td>
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
