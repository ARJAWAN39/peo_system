<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/db.php';

if (!isset($_GET['batch_year'])) {
    die("Batch year not provided.");
}

$batch_year = (int) $_GET['batch_year'];

/* ================= GET BATCH INFO ================= */
$batchStmt = $pdo->prepare("
    SELECT DISTINCT batch_year, programme
    FROM alumni_students
    WHERE batch_year = ?
    LIMIT 1
");
$batchStmt->execute([$batch_year]);
$batch = $batchStmt->fetch(PDO::FETCH_ASSOC);

if (!$batch) {
    die("Invalid batch year.");
}

/* ================= GET STUDENTS ================= */
$stmt = $pdo->prepare("
    SELECT student_name, student_email
    FROM alumni_students
    WHERE batch_year = ?
    ORDER BY student_name ASC
");
$stmt->execute([$batch_year]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Alumni Student List</title>

<style>
/* ===== SYSTEM THEME ===== */
body {
    margin: 0;
    font-family: "Segoe UI", Arial, sans-serif;
    background: #f6f8fb;
    color: #1f2937;
}

.page-container {
    max-width: 1100px;
    margin: 40px auto;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    padding: 30px;
}

/* ===== HEADER ===== */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-header h2 {
    margin: 0;
}

.back-btn {
    padding: 8px 16px;
    background: #2563eb;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
}


/* ===== INFO ===== */
.batch-info {
    margin-bottom: 20px;
    font-size: 15px;
}

/* ===== TABLE ===== */
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
}

th {
    background: #f1f5f9;
    font-weight: 600;
}

tr:hover {
    background: #f9fafb;
}

.no-data {
    text-align: center;
    padding: 20px;
    color: #6b7280;
}
</style>

</head>
<body>

<div class="page-container">

    <!-- HEADER -->
    <div class="page-header">
        <h2>Alumni Student List</h2>
        <a href="manage_users.php" class="back-btn">← Back</a>
    </div>

    <!-- BATCH INFO -->
    <div class="batch-info">
        Batch: <b><?= htmlspecialchars($batch['batch_year']) ?></b> |
        Programme: <b><?= htmlspecialchars($batch['programme']) ?></b>
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($students): ?>
            <?php foreach ($students as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['student_name']) ?></td>
                    <td><?= htmlspecialchars($s['student_email']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2" class="no-data">No students found</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>
