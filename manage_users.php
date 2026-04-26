<?php
session_start();

// LOGIN CHECK
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

/* ================= DATABASE CONNECTION ================= */
require_once __DIR__ . '/config.php';

if (!isset($pdo)) {
    die("PDO connection not available. Check config.php");
}

/* ================= FILTER VALUES ================= */
$filterProgramme = $_GET['programme'] ?? '';
$filterBatch     = $_GET['batch_year'] ?? '';
$searchBatch     = $_GET['search'] ?? '';

// Same-page view values (NEVER NULL)
$viewBatch       = $_GET['view_batch'] ?? '';
$viewProgramme   = $_GET['programme'] ?? '';

/* 🔧 FORCE FILTER WHEN VIEWING A BATCH */
if ($viewBatch !== '') {
    $filterBatch = $viewBatch;
    $filterProgramme = $viewProgramme;
}

/* ================= DELETE BATCH ================= */
if (isset($_GET['delete_id'])) {
    $batch_year = (int) $_GET['delete_id'];

    $stmt = $pdo->prepare("
        DELETE FROM alumni_students
        WHERE batch_year = ?
    ");
    $stmt->execute([$batch_year]);

    header("Location: manage_users.php");
    exit;
}

/* ================= EDIT MODE ================= */
$editBatch = null;
if (isset($_GET['edit_id'])) {
    $batch_year = (int) $_GET['edit_id'];

    $stmt = $pdo->prepare("
        SELECT DISTINCT batch_year, programme
        FROM alumni_students
        WHERE batch_year = ?
        LIMIT 1
    ");
    $stmt->execute([$batch_year]);
    $editBatch = $stmt->fetch(PDO::FETCH_ASSOC);
}

/* ================= IMPORT / UPDATE ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['batch_year'], $_POST['programme'])) {

    $batch_year = (int) $_POST['batch_year'];
    $programme  = trim($_POST['programme']);
    $fileTmp    = $_FILES['import_file']['tmp_name'] ?? null;

    /* ===== EDIT MODE (RE-IMPORT) ===== */
    if (!empty($_POST['batch_id'])) {

        $pdo->prepare("
            UPDATE alumni_students
            SET programme = ?
            WHERE batch_year = ?
        ")->execute([$programme, $batch_year]);

        if ($fileTmp) {

            $pdo->prepare("
                DELETE FROM alumni_students
                WHERE batch_year = ?
            ")->execute([$batch_year]);

            if (($handle = fopen($fileTmp, "r")) !== false) {

                // Skip CSV header
                fgetcsv($handle, 1000, ",", "\"", "\\");

                while (($data = fgetcsv($handle, 1000, ",", "\"", "\\")) !== false) {
                    if (count($data) < 2) continue;

                    /* 🔒 DUPLICATE CHECK */
                    $check = $pdo->prepare("
                        SELECT COUNT(*) FROM alumni_students
                        WHERE student_email = ?
                        AND batch_year = ?
                        AND programme = ?
                    ");
                    $check->execute([
                        trim($data[1]),
                        $batch_year,
                        $programme
                    ]);

                    if ($check->fetchColumn() > 0) {
                        continue;
                    }

                    $pdo->prepare("
                        INSERT INTO alumni_students
                        (student_name, student_email, programme, batch_year)
                        VALUES (?, ?, ?, ?)
                    ")->execute([
                        trim($data[0]),
                        trim($data[1]),
                        $programme,
                        $batch_year
                    ]);
                }
                fclose($handle);
            }
        }

    } 
    /* ===== NORMAL IMPORT ===== */
    else {

        if ($fileTmp && ($handle = fopen($fileTmp, "r")) !== false) {

            // Skip CSV header
            fgetcsv($handle, 1000, ",", "\"", "\\");

            while (($data = fgetcsv($handle, 1000, ",", "\"", "\\")) !== false) {
                if (count($data) < 2) continue;

                /* 🔒 DUPLICATE CHECK */
                $check = $pdo->prepare("
                    SELECT COUNT(*) FROM alumni_students
                    WHERE student_email = ?
                    AND batch_year = ?
                    AND programme = ?
                ");
                $check->execute([
                    trim($data[1]),
                    $batch_year,
                    $programme
                ]);

                if ($check->fetchColumn() > 0) {
                    continue;
                }

                $pdo->prepare("
                    INSERT INTO alumni_students
                    (student_name, student_email, programme, batch_year)
                    VALUES (?, ?, ?, ?)
                ")->execute([
                    trim($data[0]),
                    trim($data[1]),
                    $programme,
                    $batch_year
                ]);
            }
            fclose($handle);
        }
    }

    header("Location: manage_users.php");
    exit;
}

include 'layout/header.php';
include 'layout/sidebar.php';
?>

<style>
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.4);
    justify-content: center;
    align-items: center;
    z-index: 999;
}
.modal-box {
    background: #fff;
    padding: 20px;
    width: 400px;
    border-radius: 8px;
}
.modal-box input,
.modal-box select {
    width: 100%;
    padding: 8px;
    margin-bottom: 12px;
}
.modal-actions {
    text-align: right;
}
</style>

<div class="main-content">

    <div class="page-header">
        <div>
            <h2>MANAGE USERS</h2>
            <p>Add, edit, or delete alumni batch accounts</p>
        </div>

        <button class="btn-primary" onclick="openImportModal()">+ IMPORT BATCH</button>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-bar">

        <a href="manage_users.php"
           class="filter-btn <?= !$filterProgramme && !$filterBatch && !$searchBatch ? 'active' : '' ?>">
            All
        </a>

        <div class="filter-group">
            <button class="filter-btn">By Programme ▾</button>
            <div class="filter-dropdown">
                <a href="manage_users.php?programme=BIT">Bachelor of Information Technology (BIT)</a>
                <a href="manage_users.php?programme=BIW">Bachelor of Web Technology (BIW)</a>
            </div>
        </div>

        <div class="filter-group">
            <button class="filter-btn">By Batch ▾</button>
            <div class="filter-dropdown">
                <?php
                $batches = $pdo->query("
                    SELECT DISTINCT batch_year
                    FROM alumni_students
                    ORDER BY batch_year DESC
                ")->fetchAll(PDO::FETCH_ASSOC);

                foreach ($batches as $b) {
                    echo '<a href="manage_users.php?batch_year='.$b['batch_year'].'">'.$b['batch_year'].'</a>';
                }
                ?>
            </div>
        </div>

        <form method="get" class="search-box">
            <input type="text" name="search"
                   value="<?= htmlspecialchars($searchBatch ?? '') ?>"
                   placeholder="🔍 Search Batch e.g. 2021">
        </form>

    </div>

    <!-- USERS TABLE -->
    <?php if ($viewBatch === ''): ?>
    <div class="card">
        <div class="card-header">
            <h3>USERS BY BATCH</h3>
        </div>

        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th style="text-align:center;">BATCH</th>
                    <th style="text-align:center;">PROGRAMME</th>
                    <th style="text-align:center;">ACTIONS</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql = "
                    SELECT DISTINCT batch_year, programme
                    FROM alumni_students
                    WHERE batch_year IS NOT NULL
                    AND programme IS NOT NULL
                ";
                $params = [];

                if ($filterProgramme) {
                    $sql .= " AND programme = ?";
                    $params[] = $filterProgramme;
                }
                if ($filterBatch) {
                    $sql .= " AND batch_year = ?";
                    $params[] = $filterBatch;
                }
                if ($searchBatch) {
                    $sql .= " AND batch_year LIKE ?";
                    $params[] = "%$searchBatch%";
                }

                $sql .= " ORDER BY batch_year DESC";

                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <?php if ($rows): ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td style="text-align:center;">
                                <?= htmlspecialchars($row['batch_year']) ?>
                            </td>
                            <td style="text-align:center;">
                                <?= htmlspecialchars($row['programme']) ?>
                            </td>
                            <td class="action-cell">
                                <a href="manage_users.php?view_batch=<?= $row['batch_year'] ?>&programme=<?= $row['programme'] ?>"
                                   class="btn btn-view">View</a>

                                <a href="manage_users.php?edit_id=<?= $row['batch_year'] ?>"
                                   class="btn btn-edit">Edit</a>

                                <a href="manage_users.php?delete_id=<?= $row['batch_year'] ?>"
                                   class="btn btn-delete"
                                   onclick="return confirm('Are you sure you want to delete this batch?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align:center;">No batch data available</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

<!-- STUDENT LIST -->
    <?php if ($viewBatch !== ''): ?>
    <div class="card" style="margin-top:30px;">
        <div class="card-header">
            <h3>
                STUDENTS – Batch <?= htmlspecialchars($viewBatch) ?>
                (<?= htmlspecialchars($viewProgramme) ?>)
            </h3>
        </div>

        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-name">Student Name</th>
                    <th class="col-email">Email</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $stmt = $pdo->prepare("
                    SELECT student_name, student_email
                    FROM alumni_students
                    WHERE batch_year = ? AND programme = ?
                    ORDER BY student_name
                ");
                $stmt->execute([$viewBatch, $viewProgramme]);
                $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($students):
                    $i = 1;
                    foreach ($students as $s):
                ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($s['student_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($s['student_email'] ?? '') ?></td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="3" style="text-align:center;">No students found</td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- IMPORT / EDIT MODAL -->
<div id="importModal" class="modal-overlay">
    <div class="modal-box">
        <h3><?= $editBatch ? 'Edit Batch' : 'Import Batch' ?></h3>

        <form method="POST" enctype="multipart/form-data">

            <!-- 🔑 EDIT MODE IDENTIFIER -->
            <?php if ($editBatch): ?>
                <input type="hidden" name="batch_id" value="<?= $editBatch['batch_year'] ?>">
            <?php endif; ?>

            <label>Batch Year</label>
            <input type="number"
                   name="batch_year"
                   value="<?= $editBatch['batch_year'] ?? '' ?>"
                   <?= $editBatch ? 'readonly' : 'required' ?>>

            <label>Programme</label>
            <select name="programme" required>
                <option value="">-- Select Programme --</option>
                <option value="BIT" <?= ($editBatch['programme'] ?? '') === 'BIT' ? 'selected' : '' ?>>BIT</option>
                <option value="BIW" <?= ($editBatch['programme'] ?? '') === 'BIW' ? 'selected' : '' ?>>BIW</option>
            </select>

            <label>CSV File <?= $editBatch ? '(Optional)' : '' ?></label>
            <input type="file" name="import_file" accept=".csv" <?= $editBatch ? '' : 'required' ?>>

            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">
                    <?= $editBatch ? 'Update Batch' : 'Import Batch' ?>
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeImportModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openImportModal() {
    document.getElementById('importModal').style.display = 'flex';
}
function closeImportModal() {
    document.getElementById('importModal').style.display = 'none';
}

<?php if ($editBatch): ?>
    openImportModal();
<?php endif; ?>
</script>

<?php include 'layout/footer.php'; ?>
