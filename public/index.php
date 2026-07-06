<?php
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/auth.php';

$pdo = db();

$year = isset($_GET['year']) && $_GET['year'] !== '' ? (int)$_GET['year'] : null;
$cat  = isset($_GET['cat']) && $_GET['cat'] !== '' ? (string)$_GET['cat'] : null;

$sort = $_GET['sort'] ?? 'default';
$mode = $_GET['mode'] ?? 'default';

$allowedSort = ['default','last_name','year','category'];
$allowedMode = ['default','asc','desc'];
if (!in_array($sort, $allowedSort, true)) $sort = 'default';
if (!in_array($mode, $allowedMode, true)) $mode = 'default';

$orderSql = "a.last_name ASC, a.first_name ASC";

if ($sort !== 'default' && $mode !== 'default') {
    if ($sort === 'last_name') {
        $orderSql = "a.last_name " . strtoupper($mode) . ", a.first_name " . strtoupper($mode);
    } elseif ($sort === 'year') {
        $orderSql = "g.year " . strtoupper($mode) . ", a.last_name ASC, a.first_name ASC";
    } elseif ($sort === 'category') {
        $orderSql = "d.category " . strtoupper($mode) . ", a.last_name ASC, a.first_name ASC";
    }
}

$per = $_GET['per'] ?? '20';
if (!in_array($per, ['10','20','all'], true)) $per = '20';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

$limit = null;
$offset = null;
if ($per !== 'all') {
    $limit = (int)$per;
    $offset = ($page - 1) * $limit;
}

$years = $pdo->query("SELECT DISTINCT year FROM olympic_games ORDER BY year DESC")->fetchAll(PDO::FETCH_COLUMN);
$cats  = $pdo->query("SELECT DISTINCT category FROM disciplines ORDER BY category ASC")->fetchAll(PDO::FETCH_COLUMN);

$where = "WHERE 1=1";
$params = [];

if ($year) { $where .= " AND g.year = :year"; $params[':year'] = $year; }
if ($cat)  { $where .= " AND d.category = :cat"; $params[':cat'] = $cat; }

$countSql = "
SELECT COUNT(*)
FROM athlete_medals am
JOIN athletes a ON a.id = am.athlete_id
JOIN olympic_games g ON g.id = am.olympic_games_id
LEFT JOIN countries rc ON rc.id = am.represented_country_id
JOIN disciplines d ON d.id = am.discipline_id
{$where}
";
$stCount = $pdo->prepare($countSql);
$stCount->execute($params);
$totalRows = (int)$stCount->fetchColumn();

$dataSql = "
SELECT
  a.id AS athlete_id,
  a.last_name,
  a.first_name,
  g.year,
  COALESCE(rc.name, '-') AS represented_country,
  d.category
FROM athlete_medals am
JOIN athletes a ON a.id = am.athlete_id
JOIN olympic_games g ON g.id = am.olympic_games_id
LEFT JOIN countries rc ON rc.id = am.represented_country_id
JOIN disciplines d ON d.id = am.discipline_id
{$where}
ORDER BY {$orderSql}
";

if ($limit !== null) {
    $dataSql .= " LIMIT {$limit} OFFSET {$offset}";
}

$st = $pdo->prepare($dataSql);
$st->execute($params);
$rows = $st->fetchAll();

function q(array $add = [], array $remove = []): string {
    $q = $_GET;
    foreach ($remove as $k) unset($q[$k]);
    foreach ($add as $k => $v) {
        if ($v === null) unset($q[$k]);
        else $q[$k] = $v;
    }
    return '?' . http_build_query($q);
}

function nextSortState(string $col, string $currentSort, string $currentMode): array {
    $isYear = ($col === 'year');
    if ($currentSort !== $col || $currentMode === 'default') {
        return [$col, $isYear ? 'desc' : 'asc'];
    }
    if ($isYear) {
        if ($currentMode === 'desc') return [$col, 'asc'];
        if ($currentMode === 'asc') return ['default', 'default'];
    }
    if ($currentMode === 'asc')  return [$col, 'desc'];
    if ($currentMode === 'desc') return ['default', 'default'];
    return [$col, $isYear ? 'desc' : 'asc'];
}

$me = current_user();
?>
<!doctype html>
<html lang="sk">
<head>
  <meta charset="utf-8">
  <title>Slovenskí olympionici</title>
  <link rel="stylesheet" href="assets/styles.css">
  <script defer src="assets/app.js"></script>
</head>
<body>
<div class="container">
  <div class="topbar">
    <div class="brand">
      <h1>Slovenskí olympionici<span class="dot">.</span></h1>
      <div class="sub">Filtrovanie • Triedenie • Stránkovanie</div>
    </div>
    <div class="actions">
      <?php if ($me): ?>
        <span class="pill"><?= htmlspecialchars($me['email']) ?></span>
        <a class="btn" href="profile.php">Profil</a>
        <a class="btn" href="private/import.php">Import</a>
        <a class="btn" href="private/manage.php">Správa API</a>
        <a class="btn" href="api-docs.php">API docs</a>
        <a class="btn ghost" href="logout.php">Odhlásiť</a>
      <?php else: ?>
        <a class="btn" href="login.php">Prihlásiť</a>
        <a class="btn ghost" href="api-docs.php">API docs</a>
      <?php endif; ?>
    </div>
  </div>

  <div class="grid">

    <div class="card">
      <h2>Filtre</h2>

      <form class="filters" method="get">
        <div class="field">
          <label>Rok</label>
          <select name="year">
            <option value="">-- všetky --</option>
            <?php foreach ($years as $y): ?>
              <option value="<?= (int)$y ?>" <?= $year===(int)$y ? 'selected' : '' ?>><?= (int)$y ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label>Kategória</label>
          <select name="cat">
            <option value="">-- všetky --</option>
            <?php foreach ($cats as $c): ?>
              <option value="<?= htmlspecialchars($c) ?>" <?= $cat===$c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label>Záznamy na stránku</label>
          <select name="per">
            <option value="10" <?= $per==='10'?'selected':'' ?>>10</option>
            <option value="20" <?= $per==='20'?'selected':'' ?>>20</option>
            <option value="all" <?= $per==='all'?'selected':'' ?>>-- všetky --</option>
          </select>
        </div>

        <div class="actions">
          <button class="btn primary" type="submit">Filtrovať</button>
          <a class="btn ghost" href="index.php">Reset</a>
        </div>
      </form>

      <div class="pills">
        <?php if ($year): ?><span class="pill">Rok: <?= (int)$year ?></span><?php endif; ?>
        <?php if ($cat): ?><span class="pill">Kategória: <?= htmlspecialchars($cat) ?></span><?php endif; ?>
        <span class="pill">Spolu: <?= $totalRows ?></span>
      </div>
    </div>

    <div class="card">
      <h2>Zoznam</h2>

      <div class="tablewrap">
        <table>
          <thead>
            <tr>
              <?php
                [$ns1, $nm1] = nextSortState('last_name', $sort, $mode);
                [$ns2, $nm2] = nextSortState('year', $sort, $mode);
                [$ns3, $nm3] = nextSortState('category', $sort, $mode);
              ?>
              <th>
                <a href="<?= htmlspecialchars(q(['sort'=>$ns1,'mode'=>$nm1,'page'=>1])) ?>">Priezvisko</a>
              </th>

              <?php if (!$year): ?>
                <th>
                  <a href="<?= htmlspecialchars(q(['sort'=>$ns2,'mode'=>$nm2,'page'=>1])) ?>">Rok</a>
                </th>
              <?php endif; ?>

              <th>Reprezentovaná krajina</th>

              <?php if (!$cat): ?>
                <th>
                  <a href="<?= htmlspecialchars(q(['sort'=>$ns3,'mode'=>$nm3,'page'=>1])) ?>">Kategória</a>
                </th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td>
                  <a href="athlete.php?id=<?= (int)$r['athlete_id'] ?>">
                    <?= htmlspecialchars($r['last_name'].' '.$r['first_name']) ?>
                  </a>
                </td>

                <?php if (!$year): ?><td><?= (int)$r['year'] ?></td><?php endif; ?>
                <td><?= htmlspecialchars($r['represented_country']) ?></td>
                <?php if (!$cat): ?><td><?= htmlspecialchars($r['category']) ?></td><?php endif; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php if ($per !== 'all'): ?>
        <?php
          $pages = max(1, (int)ceil($totalRows / (int)$per));
          $page = min($page, $pages);
        ?>
        <div class="pills" style="margin-top:12px">
          <span class="pill">Strana <?= $page ?> / <?= $pages ?></span>
          <?php if ($page > 1): ?>
            <a class="btn ghost" href="<?= htmlspecialchars(q(['page'=>$page-1])) ?>">← Predchádzajúca</a>
          <?php endif; ?>
          <?php if ($page < $pages): ?>
            <a class="btn ghost" href="<?= htmlspecialchars(q(['page'=>$page+1])) ?>">Ďalšia →</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>
</body>
</html>
