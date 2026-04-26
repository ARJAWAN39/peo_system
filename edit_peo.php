<?php
session_start();
require_once 'config.php';

/* =========================
   VALIDATE PEO ID
========================= */
if (!isset($_GET['peo_id'])) {
    header("Location: manage_peo_plo.php");
    exit;
}

$peo_id = (int) $_GET['peo_id'];

/* =========================
   FETCH PEO (FROM peo_plo_mapping)
========================= */
$stmt = $pdo->prepare("
    SELECT id, peo_code, peo_description
    FROM peo_plo_mapping
    WHERE id = ?
");
$stmt->execute([$peo_id]);
$peo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$peo) {
    header("Location: manage_peo_plo.php");
    exit;
}

/* =========================
   FETCH ALL DISTINCT PLO
========================= */
$plos = $pdo->query("
    SELECT DISTINCT plo_code, plo_description
    FROM peo_plo_mapping
    WHERE plo_code IS NOT NULL
    ORDER BY plo_code
")->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   FETCH MAPPED PLOs
========================= */
$stmt = $pdo->prepare("
    SELECT plo_code
    FROM peo_plo_mapping
    WHERE peo_code = ?
      AND plo_code IS NOT NULL
");
$stmt->execute([$peo['peo_code']]);
$mappedPLOs = $stmt->fetchAll(PDO::FETCH_COLUMN);

/* =========================
   SAVE CHANGES
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $description = trim($_POST['peo_description']);
    $selectedPLOs = $_POST['plo_ids'] ?? [];

    /* Update PEO description (all rows of this PEO) */
    $stmt = $pdo->prepare("
        UPDATE peo_plo_mapping
        SET peo_description = ?
        WHERE peo_code = ?
    ");
    $stmt->execute([$description, $peo['peo_code']]);

    /* Remove old PLO mappings */
    $stmt = $pdo->prepare("
        DELETE FROM peo_plo_mapping
        WHERE peo_code = ?
          AND plo_code IS NOT NULL
    ");
    $stmt->execute([$peo['peo_code']]);

    /* Insert new mappings */
    $stmt = $pdo->prepare("
        INSERT INTO peo_plo_mapping (peo_code, peo_description, plo_code, plo_description)
        SELECT ?, ?, plo_code, plo_description
        FROM peo_plo_mapping
        WHERE plo_code = ?
        LIMIT 1
    ");

    foreach ($selectedPLOs as $plo_code) {
        $stmt->execute([$peo['peo_code'], $description, $plo_code]);
    }

    header("Location: manage_peo_plo.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit PEO</title>
<link rel="stylesheet" href="assets/css/style.css">

<style>
.main-content {
    margin-left: 230px;
    padding: 24px;
    background: #f6f7fb;
    min-height: 100vh;
}
.edit-container {
    max-width: 1100px;   /* slightly smaller */
    margin: 0 auto;
}
.card {
    background: #ffffff;
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 24px;
    border: 1px solid #e5e7eb;
}
.form-group {
    margin-bottom: 18px;
}
label {
    font-weight: 600;
    display: block;
    margin-bottom: 6px;
}
input[readonly],
textarea {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    background: #f9fafb;
}
.plo-item {
    display: flex;
    gap: 12px;
    padding:10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    margin-bottom: 10px;
}
.actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 30px;
}
.btn-primary {
    background: #111827;
    color: #fff;
    border: none;
    padding: 10px 22px;
    border-radius: 8px;
}
.btn-secondary {
    background: #e5e7eb;
    color: #111827;
    padding: 10px 22px;
    border-radius: 8px;
    text-decoration: none;
}
</style>
</head>

<body>

<div class="top-header">
    <h2>ADMIN DASHBOARD</h2>
</div>

<div class="sidebar">
    <div class="logo">
        PEO Achievement System<br><small>ADMIN</small>
    </div>
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
<div class="edit-container">

<form method="POST">

<div class="card">
    <h2>Edit Program Educational Objective (PEO)</h2>

    <div class="form-group">
        <label>PEO Code</label>
        <input type="text" value="<?= htmlspecialchars($peo['peo_code']) ?>" readonly>
    </div>

    <div class="form-group">
        <label>PEO Description</label>
        <textarea name="peo_description" rows="3" required><?= htmlspecialchars($peo['peo_description']) ?></textarea>
    </div>
</div>

<div class="card">
    <h3>Manage PLO Mapping</h3>

    <?php foreach ($plos as $plo): ?>
        <label class="plo-item">
            <input type="checkbox"
                   name="plo_ids[]"
                   value="<?= htmlspecialchars($plo['plo_code']) ?>"
                   <?= in_array($plo['plo_code'], $mappedPLOs) ? 'checked' : '' ?>>
            <div>
                <strong><?= htmlspecialchars($plo['plo_code']) ?></strong><br>
                <?= htmlspecialchars($plo['plo_description']) ?>
            </div>
        </label>
    <?php endforeach; ?>

    <div class="actions">
        <button type="submit" class="btn-primary">SAVE CHANGES</button>
        <a href="manage_peo_plo.php" class="btn-secondary">CANCEL</a>
    </div>
</div>

</form>

</div>
</div>

</body>
</html>
