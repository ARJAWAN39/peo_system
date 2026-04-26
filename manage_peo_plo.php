<?php
session_start();
require_once 'db.php';

/* =======================
   ADD PEO
======================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_peo'])) {

    $peo_code = trim($_POST['peo_code']);
    $peo_description = trim($_POST['peo_description']);

    if ($peo_code && $peo_description) {
        $stmt = $pdo->prepare("
            INSERT INTO peo_plo_mapping (peo_code, peo_description)
            VALUES (?, ?)
        ");
        $stmt->execute([$peo_code, $peo_description]);
    }

    header("Location: manage_peo_plo.php");
    exit;
}

/* =======================
   DELETE PEO
======================= */
if (isset($_GET['delete_peo'])) {
    $id = (int) $_GET['delete_peo'];

    $stmt = $pdo->prepare("DELETE FROM peo_plo_mapping WHERE peo_code = (
        SELECT peo_code FROM peo_plo_mapping WHERE id = ?
    )");
    $stmt->execute([$id]);

    header("Location: manage_peo_plo.php");
    exit;
}

/* =======================
   FETCH DISTINCT PEOs
======================= */
$stmtPeo = $pdo->query("
    SELECT MIN(id) AS id, peo_code, peo_description
    FROM peo_plo_mapping
    WHERE peo_code IS NOT NULL
    GROUP BY peo_code, peo_description
    ORDER BY peo_code
");

/* =======================
   FETCH MAPPED PLOs
======================= */
function getMappedPLOs($pdo, $peo_code) {
    $stmt = $pdo->prepare("
        SELECT DISTINCT plo_code
        FROM peo_plo_mapping
        WHERE peo_code = ?
        AND plo_code IS NOT NULL
        ORDER BY plo_code
    ");
    $stmt->execute([$peo_code]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

/* =======================
   FETCH ALL UNIQUE PLOs (MATRIX HEADER)
======================= */
$allPLOs = $pdo->query("
    SELECT DISTINCT plo_code
    FROM peo_plo_mapping
    WHERE plo_code IS NOT NULL
    ORDER BY plo_code
")->fetchAll(PDO::FETCH_COLUMN);

/* =======================
   BUILD MATRIX
======================= */
$raw = $pdo->query("
    SELECT peo_code, plo_code
    FROM peo_plo_mapping
    WHERE plo_code IS NOT NULL
    AND plo_code IS NOT NULL
")->fetchAll(PDO::FETCH_ASSOC);

$matrix = [];
foreach ($raw as $r) {
    $matrix[$r['peo_code']][$r['plo_code']] = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage PEO & PLO</title>

<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<link rel="stylesheet" href="assets/css/style.css">

<style>
.main-content {
    margin-left: 230px;
    padding: 30px;
    background: #f6f7fb;
    min-height: 100vh;
}

.page-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 25px;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.header-actions a {
    text-decoration: none;
}

.btn-add {
    background: #111827;
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
}

/* ===== PEO CARD ===== */
.peo-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 22px;
    margin-bottom: 22px;
}

.peo-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.peo-actions {
    display: flex;
    gap: 8px;
}

.btn-edit-sm {
    background: #28a745;      /* main green */
    color: #ffffff;
    padding: 6px 14px;
    font-size: 13px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
}

.btn-edit-sm:hover {
    background: #218838;      /* darker green on hover */
}

.btn-delete {
    background: #dc2626;
    color: #fff;
    padding: 6px 14px;
    border-radius: 6px;
    text-decoration: none;
}

.btn-delete:hover {
    background: #b91c1c;
}

/* ===== MAPPED PLO BADGES ===== */
.mapped-plos {
    margin-top: 14px;
}

.plo-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 6px;
}

.plo-badge {
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

/* ===== MATRIX ===== */
.mapping-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    text-align: center;
}

.mapping-table th,
.mapping-table td {
    border: 1px solid #d1d5db;
    padding: 10px;
}

.mapping-table th {
    background: #f3f4f6;
}

.matrix-cell {
    font-size: 18px;
    font-weight: bold;
}

/* ===== MODAL ===== */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.55);
    z-index: 999;
}

.modal-box {
    background: #fff;
    width: 480px;
    margin: 120px auto;
    padding: 28px;
    border-radius: 12px;
}

.form-group {
    margin-bottom: 16px;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.btn-primary {
    background: #111827;
    color: #fff;
    border: none;
    padding: 8px 18px;
    border-radius: 6px;
}

.btn-secondary {
    background: #e5e7eb;
    border: none;
    padding: 8px 18px;
    border-radius: 6px;
}
.sidebar-icon {
    width: 56px;
    height: 56px;
    background: rgba(255,255,255,0.15);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 20px auto 3px 60px; /* ← move left */
}

.sidebar-icon i {
    font-size: 28px;
    color: #fff;
}
</style>
</head>

<body>

<div class="top-header"><h2>ADMIN DASHBOARD</h2></div>

<div class="sidebar">
    <div class="sidebar-icon">
    <i class="fa-solid fa-graduation-cap"></i>
    </div>
    
    <div class="logo">PEO Achievement System<br><small>ADMIN</small></div>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li class="active"><a href="manage_peo_plo.php">Manage PEO & PLO</a></li>
        <li><a href="survey_builder.php">Survey Builder</a></li>
        <li><a href="assign_survey.php">Assign Survey</a></li>
        <li><a href="notifications.php">Notifications</a></li>
        <li><a href="reports.php">Reports & Analytics</a></li>
        <li><a href="profile_settings.php">Profile / Settings</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="main-content">

<div class="page-header">
    <div>
        <h2>MANAGE PEO & PLO</h2>
        <p>Program Educational Objectives</p>
    </div>
    <div class="header-actions">
    <button class="btn-add" onclick="openAddPeoModal()">+ ADD PEO</button>
    <a href="manage_plo.php" class="btn-add">+ ADD PLO</a>
    </div>
</div>

<!-- ===== PEO LIST ===== -->
<?php while ($peo = $stmtPeo->fetch(PDO::FETCH_ASSOC)): ?>
<?php $mapped = getMappedPLOs($pdo, $peo['peo_code']); ?>

<div class="peo-card">
    <div class="peo-header">
        <h4><?= htmlspecialchars($peo['peo_code'] ?? '') ?></h4>
        <div class="peo-actions">
        <a href="edit_peo.php?peo_id=<?= $peo['id'] ?>" class="btn-edit">
            ✎ EDIT
        <a href="?delete_peo=<?= $peo['id'] ?>" class="btn-delete"
           onclick="return confirm('Delete this PEO?')">DELETE</a>
    </div>
</div>

    <p><?= htmlspecialchars($peo['peo_description'] ?? '') ?></p>

    <?php if ($mapped): ?>
    <div class="mapped-plos">
        <strong>Mapped PLOs:</strong>
        <div class="plo-badges">
            <?php foreach ($mapped as $plo): ?>
                <span class="plo-badge"><?= htmlspecialchars($plo ?? '') ?></span>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php endwhile; ?>

<!-- ===== MATRIX ===== -->
<div class="peo-card">
<h3>PEO–PLO Mapping Matrix</h3>

<table class="mapping-table">
<thead>
<tr>
    <th>PEO</th>
    <?php foreach ($allPLOs as $plo): ?>
        <th><?= htmlspecialchars($plo ?? '') ?></th>
    <?php endforeach; ?>
</tr>
</thead>
<tbody>

<?php
$peos = $pdo->query("
    SELECT DISTINCT peo_code
    FROM peo_plo_mapping
    WHERE peo_code IS NOT NULL
    ORDER BY peo_code
");
while ($p = $peos->fetch(PDO::FETCH_ASSOC)):
?>
<tr>
    <td><strong><?= htmlspecialchars($p['peo_code'] ?? '') ?></strong></td>
    <?php foreach ($allPLOs as $plo): ?>
        <td class="matrix-cell">
            <?= isset($matrix[$p['peo_code']][$plo]) ? '■' : '□' ?>
        </td>
    <?php endforeach; ?>
</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>

</div>

<!-- ===== ADD PEO MODAL ===== -->
<div id="addPeoModal" class="modal-overlay">
<div class="modal-box">
<h3>Add Program Educational Objective (PEO)</h3>

<form method="POST">
<div class="form-group">
<label>PEO Number</label>
<select name="peo_code" required>
<option value="">-- Select PEO --</option>
<?php for ($i=1;$i<=10;$i++): ?>
<option value="PEO <?= $i ?>">PEO <?= $i ?></option>
<?php endfor; ?>
</select>
</div>

<div class="form-group">
<label>PEO Description</label>
<textarea name="peo_description" rows="4" required></textarea>
</div>

<input type="hidden" name="add_peo" value="1">

<div class="modal-actions">
<button class="btn-primary">SAVE</button>
<button type="button" class="btn-secondary" onclick="closeAddPeoModal()">CANCEL</button>
</div>
</form>
</div>
</div>

<script>
function openAddPeoModal(){document.getElementById('addPeoModal').style.display='block';}
function closeAddPeoModal(){document.getElementById('addPeoModal').style.display='none';}
</script>

</body>
</html>
