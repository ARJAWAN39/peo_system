<?php
session_start();
require_once 'config.php';

/* =========================
   ADD / UPDATE PLO
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $plo_code = trim($_POST['plo_code']);
    $plo_desc = trim($_POST['plo_description']);

    // ADD PLO
    if (isset($_POST['add_plo'])) {
        $stmt = $pdo->prepare("
            INSERT INTO peo_plo_mapping (plo_code, plo_description)
            VALUES (?, ?)
        ");
        $stmt->execute([$plo_code, $plo_desc]);
    }

    // EDIT PLO
    if (isset($_POST['edit_plo'])) {
        $id = (int) $_POST['plo_id'];
        $stmt = $pdo->prepare("
            UPDATE peo_plo_mapping
            SET plo_code = ?, plo_description = ?
            WHERE id = ?
        ");
        $stmt->execute([$plo_code, $plo_desc, $id]);
    }

    header("Location: manage_plo.php");
    exit;
}

/* =========================
   DELETE PLO
========================= */
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("
        DELETE FROM peo_plo_mapping
        WHERE id = ?
    ");
    $stmt->execute([(int)$_GET['delete_id']]);

    header("Location: manage_plo.php");
    exit;
}

/* =========================
   EDIT MODE
========================= */
$editPlo = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("
        SELECT * FROM peo_plo_mapping
        WHERE id = ?
    ");
    $stmt->execute([(int)$_GET['edit_id']]);
    $editPlo = $stmt->fetch(PDO::FETCH_ASSOC);
}

/* =========================
   FETCH PLO LIST
========================= */
$plos = $pdo->query("
    SELECT id, plo_code, plo_description
    FROM peo_plo_mapping
    WHERE plo_code IS NOT NULL
    ORDER BY plo_code ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage PLO</title>

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
    align-items: flex-start;
    margin-bottom: 25px;
}

.btn-back {
    background: #2563eb;
    color: #ffffff;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 8px;
}

.card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
}

/* ===== TABLE FIX (STRAIGHT LINES) ===== */
.table {
    width: 100%;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 14px 16px;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: middle;
}

.table tr:last-child td {
    border-bottom: none;
}

/* ===== ACTION BUTTON ALIGNMENT ===== */
.actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

/* ===== EDIT BUTTON (GREEN LIKE SECOND PIC) ===== */
.btn-edit-sm {
    background: #3de264ff;
    color: #fff;
    padding: 6px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-edit-sm:hover {
    background: #218838;
}

/* ===== DELETE BUTTON (UNCHANGED RED) ===== */
.btn-delete-sm {
    background: #ef4444;
    color: #fff;
    padding: 6px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-delete-sm:hover {
    background: #b91c1c;
}

.form-box {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 25px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

.btn-primary,
.btn-secondary {
    padding: 8px 18px;
    border-radius: 6px;
    font-size: 14px;

    display: inline-flex;        /* ⭐ key fix */
    align-items: center;         /* ⭐ vertical center */
    justify-content: center;

    height: 36px;                /* ⭐ force same height */
    min-width: 100px;            /* ⭐ same width */
    box-sizing: border-box;
}

/* SAVE */
.btn-primary {
    background: #111827;
    color: #fff;
    border: none;
}

/* CANCEL */
.btn-secondary {
    background: #e5e7eb;
    color: #111;
    text-decoration: none;
}
.sidebar-icon {
    width: 56px;
    height: 56px;
    background: rgba(255,255,255,0.15);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 20px auto 3px 40px; /* ← move left */
}

.sidebar-icon i {
    font-size: 28px;
    color: #fff;
}

</style>
</head>

<body>

<div class="top-header">
    <h2>ADMIN DASHBOARD</h2>
</div>

<div class="sidebar">
    <div class="logo">
        <div class="sidebar-icon">
    <i class="fa-solid fa-graduation-cap"></i>
    </div>
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

<div class="page-header">
    <div>
        <h2>MANAGE PROGRAM LEARNING OUTCOMES (PLO)</h2>
        <p>Create, update and manage PLOs</p>
    </div>
    <a href="manage_peo_plo.php" class="btn-back">← Back</a>
</div>

<!-- ADD / EDIT FORM -->
<div class="form-box">
    <h3><?= $editPlo ? 'Edit PLO' : 'Add New PLO' ?></h3>

    <form method="POST">
        <?php if ($editPlo): ?>
            <input type="hidden" name="plo_id" value="<?= $editPlo['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>PLO Number</label>
            <select name="plo_code" required>
                <option value="">-- Select PLO --</option>
                <?php
                $selectedPlo = $editPlo['plo_code'] ?? '';
                for ($i = 1; $i <= 15; $i++):
                    $code = "PLO $i";
                ?>
                    <option value="<?= $code ?>" <?= ($selectedPlo === $code) ? 'selected' : '' ?>>
                        <?= $code ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="form-group">
            <label>PLO Description</label>
            <textarea name="plo_description" rows="4" required><?= htmlspecialchars($editPlo['plo_description'] ?? '') ?></textarea>
        </div>

        <button type="submit" name="<?= $editPlo ? 'edit_plo' : 'add_plo' ?>" class="btn-primary">
            SAVE
        </button>

        <?php if ($editPlo): ?>
            <a href="manage_plo.php" class="btn-secondary">CANCEL</a>
        <?php endif; ?>
    </form>
</div>

<!-- PLO LIST -->
<div class="card">
    <h3>Existing PLOs</h3>

    <table class="table">
        <tr>
            <th>PLO Code</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>

        <?php if ($plos): foreach ($plos as $plo): ?>
        <tr>
            <td><?= htmlspecialchars($plo['plo_code']) ?></td>
            <td><?= htmlspecialchars($plo['plo_description']) ?></td>
            <td class="actions">
                <a href="?edit_id=<?= $plo['id'] ?>" class="btn-edit-sm">EDIT</a>
                <a href="?delete_id=<?= $plo['id'] ?>" class="btn-delete-sm"
                   onclick="return confirm('Delete this PLO?')">DELETE</a>
            </td>
        </tr>
        <?php endforeach; else: ?>
        <tr>
            <td colspan="3">No PLO available</td>
        </tr>
        <?php endif; ?>
    </table>
</div>

</div>
</body>
</html>
