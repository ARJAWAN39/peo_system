<?php
session_start();
require_once "config.php";
include "layout/header.php";
include "layout/sidebar.php";

/* =========================
   🔥 DEBUG + SESSION CHECK
========================= */
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    die("User session missing");
}

/* =========================
   🔥 HANDLE ACTIVATE (ADDED)
========================= */
if (isset($_GET['activate_id'])) {

    $assignment_id = (int) $_GET['activate_id'];

    // get survey info
    $stmt = $pdo->prepare("
        SELECT survey_id, batch_year
        FROM survey_assignments
        WHERE assignment_id = ?
    ");
    $stmt->execute([$assignment_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {

        // update status
        $update = $pdo->prepare("
            UPDATE survey_assignments
            SET status = 'active'
            WHERE assignment_id = ?
        ");
        $update->execute([$assignment_id]);

        $survey_id = $data['survey_id'];
        $batch_year = $data['batch_year'];

        // insert notification (no duplicate)
        $checkNotif = $pdo->prepare("
            SELECT COUNT(*) FROM notifications
            WHERE related_survey_id = ?
            AND batch_year = ?
            AND type = 'pending'
        ");
        $checkNotif->execute([$survey_id, $batch_year]);

        if ($checkNotif->fetchColumn() == 0) {

            $stmtNotif = $pdo->prepare("
                INSERT INTO notifications 
                (user_id, title, message, type, related_survey_id, batch_year, is_read, created_at)
                VALUES (?, ?, ?, ?, ?, ?, 0, NOW())
            ");

            $stmtNotif->execute([
                $_SESSION['user_id'],
                "Survey Activated",
                "Survey for Batch $batch_year has been activated.",
                "pending",
                $survey_id,
                $batch_year
            ]);
        }
    }

    header("Location: assign_survey.php");
    exit();
}

/* =========================
   INIT
========================= */
$error = "";
$success = "";

/* =========================
   EDIT MODE DETECTION
========================= */
$isEdit = false;
$editData = null;

if (isset($_GET['edit_id'])) {
    $isEdit = true;

    $stmt = $pdo->prepare("
        SELECT *
        FROM survey_assignments
        WHERE assignment_id = ?
    ");
    $stmt->execute([(int) $_GET['edit_id']]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$editData) {
        header("Location: assign_survey.php");
        exit;
    }
}

/* =========================
   HANDLE UPDATE
========================= */
if (isset($_POST['update_assignment'])) {

    $assignment_id = (int) $_POST['assignment_id'];
    $assigned_date = $_POST['assigned_date'];
    $due_date      = $_POST['due_date'];
    $email_message = trim($_POST['email_message']);

    $update = $pdo->prepare("
        UPDATE survey_assignments
        SET assigned_date = ?,
            due_date = ?,
            email_message = ?
        WHERE assignment_id = ?
    ");

    $update->execute([
        $assigned_date,
        $due_date,
        $email_message ?: null,
        $assignment_id
    ]);

    header("Location: assign_survey.php");
    exit;
}

/* =========================
   HANDLE CREATE
========================= */
if (isset($_POST['assign_survey']) || isset($_POST['save_draft'])) {

    $survey_id     = (int) $_POST['survey_id'];
    $batch_year    = (int) $_POST['batch_year'];
    $assigned_date = $_POST['assigned_date'];
    $due_date      = $_POST['due_date'];
    $email_message = trim($_POST['email_message']);

    $status = isset($_POST['save_draft']) ? 'draft' : 'active';

    if ($status === 'active') {
        $check = $pdo->prepare("
            SELECT 1
            FROM survey_assignments
            WHERE survey_id = ?
            AND batch_year = ?
            AND status = 'active'
            LIMIT 1
        ");
        $check->execute([$survey_id, $batch_year]);

        if ($check->rowCount() > 0) {
            $error = "This survey is already ACTIVE for the selected batch.";
        }
    }

    if ($error === "") {

        $insert = $pdo->prepare("
            INSERT INTO survey_assignments
            (survey_id, batch_year, assigned_date, due_date, email_message, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $insert->execute([
            $survey_id,
            $batch_year,
            $assigned_date,
            $due_date,
            $email_message ?: null,
            $status
        ]);

        if ($status === 'active') {

            try {

                $checkNotif = $pdo->prepare("
                    SELECT COUNT(*) 
                    FROM notifications
                    WHERE related_survey_id = ?
                    AND batch_year = ?
                    AND type = 'pending'
                ");
                $checkNotif->execute([$survey_id, $batch_year]);

                if ($checkNotif->fetchColumn() == 0) {

                    $stmtNotif = $pdo->prepare("
                        INSERT INTO notifications 
                        (user_id, title, message, type, related_survey_id, batch_year, is_read, created_at)
                        VALUES (?, ?, ?, ?, ?, ?, 0, NOW())
                    ");

                    $stmtNotif->execute([
                        $_SESSION['user_id'],
                        "Survey Assigned",
                        "PEO Survey for Batch $batch_year is now available.",
                        "pending",
                        $survey_id,
                        $batch_year
                    ]);
                }

            } catch (PDOException $e) {
                die("Notification Error: " . $e->getMessage());
            }
        }

        header("Location: assign_survey.php");
        exit;
    }
}

/* =========================
   FETCH SURVEYS
========================= */
$surveys = $pdo->query("
    SELECT survey_id, survey_title
    FROM surveys
    ORDER BY survey_title ASC
")->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   FETCH ASSIGNMENTS
========================= */
$stmt = $pdo->query("
    SELECT 
        sa.*,
        s.survey_title,

        CASE
            WHEN sa.status = 'draft' THEN 'draft'
            WHEN sa.status = 'active' THEN 'active'
            ELSE 'active'
        END AS computed_status,

        0 AS total_alumni,
        0 AS total_responses

    FROM survey_assignments sa
    JOIN surveys s ON sa.survey_id = s.survey_id
    ORDER BY sa.created_at DESC
");

$assignedSurveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">

    <div class="page-header page-header-vertical">
        <h2>ASSIGN SURVEY TO ALUMNI</h2>
        <p class="page-subtitle">Assign surveys to specific graduation batches</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <div class="card compact-card">
        <h3><?= $isEdit ? 'Edit Assignment' : 'Create New Assignment' ?></h3>

        <form method="POST" class="form-grid">

            <div>
                <label>Survey</label>
                <select name="survey_id" required <?= $isEdit && $editData['status'] === 'active' ? 'disabled' : '' ?>>
                    <option value="">-- Select Survey --</option>
                    <?php foreach ($surveys as $s): ?>
                        <option value="<?= $s['survey_id'] ?>"
                            <?= $isEdit && $editData['survey_id'] == $s['survey_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['survey_title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Graduation Batch</label>
                <select name="batch_year" required <?= $isEdit && $editData['status'] === 'active' ? 'disabled' : '' ?>>
                    <option value="">-- Select Batch --</option>
                    <?php for ($y = 2018; $y <= date('Y'); $y++): ?>
                        <option value="<?= $y ?>"
                            <?= $isEdit && $editData['batch_year'] == $y ? 'selected' : '' ?>>
                            <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div>
                <label>Assigned Date</label>
                <input type="date" name="assigned_date"
                       value="<?= $isEdit ? $editData['assigned_date'] : '' ?>" required>
            </div>

            <div>
                <label>Due Date</label>
                <input type="date" name="due_date"
                       value="<?= $isEdit ? $editData['due_date'] : '' ?>" required>
            </div>

            <div class="full-width">
                <label>Notification Message (Optional)</label>
                <textarea name="email_message" rows="3"><?= $isEdit ? htmlspecialchars($editData['email_message'] ?? '') : '' ?></textarea>

            <div class="full-width actions">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="assignment_id" value="<?= $editData['assignment_id'] ?>">
                    <button type="submit" name="update_assignment" class="btn-primary">
                        Update Assignment
                    </button>
                    <a href="assign_survey.php" class="btn-secondary">Cancel</a>
                <?php else: ?>
                    <button type="submit" name="assign_survey" class="btn-primary">
                        Assign Survey
                    </button>
                    <button type="submit" name="save_draft" class="btn-secondary">
                        Save as Draft
                    </button>
                <?php endif; ?>
            </div>

        </form>
    </div>

    <div class="card compact-card">
        <h3>Assigned Surveys</h3>

        <table class="assign-table">
            <thead>
                <tr>
                    <th>Batch</th>
                    <th>Survey</th>
                    <th>Assigned Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th class="actions-header">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assignedSurveys as $row): ?>
                <tr>
                    <td><?= $row['batch_year'] ?></td>
                    <td><?= htmlspecialchars($row['survey_title']) ?></td>
                    <td><?= $row['assigned_date'] ?? '-' ?></td>
                    <td><?= $row['due_date'] ?></td>

                    <td>
                        <span class="status <?= $row['computed_status'] ?>">
                            <?= strtoupper($row['computed_status']) ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="assign_survey.php?edit_id=<?= $row['assignment_id'] ?>" class="btn-edit">EDIT</a>

                        <a href="delete_assign_survey.php?id=<?= $row['assignment_id'] ?>"
                        class="btn-delete"
                        onclick="return confirm('Delete this assignment?')">
                        DELETE</a>

                        <?php if ($row['computed_status'] === 'draft'): ?>
                            <a href="assign_survey.php?activate_id=<?= $row['assignment_id'] ?>"
                            class="btn-activate"
                            onclick="return confirm('Activate this survey for alumni?')">
                            ACTIVATE</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<style>
/* =========================
   FORMAL ADMIN UI POLISH
========================= */
/* FORCE VERTICAL HEADER (OVERRIDE GLOBAL FLEX) */
.page-header-vertical {
    display: block !important;
}

.page-header-vertical h2 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
    color: #111827;
}

.page-header-vertical .page-subtitle {
    margin-top: 6px;
    font-size: 15px;
    color: #6b7280;
}

/* CARD */
.card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
}

.card h3 {
    margin-top: 0;
    margin-bottom: 18px;
    font-size: 18px;
    font-weight: 600;
}

/* FORM GRID */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px 28px;
    align-items: end;
}

.form-grid label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 6px;
    color: #374151;
}

.form-grid input,
.form-grid select,
.form-grid textarea {
    width: 100%;
    padding: 10px 12px;
    font-size: 14px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    background: #fff;
}

.form-grid textarea {
    resize: vertical;
}

.form-grid input:focus,
.form-grid select:focus,
.form-grid textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37,99,235,0.15);
}

.form-grid .full-width {
    grid-column: span 2;
}

.form-grid .actions {
    margin-bottom: 30px; 
}

/* BUTTONS */
.actions {
    display: flex;
    gap: 12px;
}

.btn-primary {
    background: #1f2937;
    color: #fff;
    border: none;
    padding: 10px 18px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
}

.btn-primary:hover {
    background: #0f40aaff;
}

.btn-secondary {
    background: #f3f4f6;
    color: #111827;
    border: 1px solid #d1d5db;
    padding: 10px 18px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

/* ALERTS */
.alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 18px;
    font-size: 14px;
}

.alert-success {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.alert-error {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

/* TABLE */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.table th {
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    padding: 12px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.table td {
    padding: 12px;
    font-size: 14px;
    border-bottom: 1px solid #e5e7eb;
}

.table tr:hover {
    background: #f9fafb;
}

/* STATUS */
.status {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.status.active {
    background: #dcfce7;
    color: #166534;
}

.status.draft {
    background: #fef3c7;
    color: #92400e;
}

/* ASSIGN TABLE */
.assign-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
}

.assign-table th {
    text-align: left;
    padding: 12px 14px;
    background: #f9fafb;
    font-size: 13px;
}

.assign-table td {
    background: #fff;
    padding: 14px;
    border-top: 1px solid #e5e7eb;
    border-bottom: 1px solid #e5e7eb;
}

.assign-table tr td:first-child {
    border-left: 1px solid #e5e7eb;
    border-radius: 8px 0 0 8px;
}

.assign-table tr td:last-child {
    border-right: 1px solid #e5e7eb;
    border-radius: 0 8px 8px 0;
}

/* STATUS (TABLE) */
.status {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.status.active { background: #dcfce7; color: #166534; }
.status.draft  { background: #fef3c7; color: #92400e; }

/* ACTION BUTTONS */
.actions {
    display: flex;
    gap: 8px;
}

.btn-edit {
    background: #22c55e;
    color: #fff;
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
}

.btn-delete {
    background: #f14545ff;
    color: #fff;
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
}

/* RESPONSES BAR */
.progress-bar {
    background: #e5e7eb;
    height: 6px;
    border-radius: 10px;
    margin-top: 4px;
    overflow: hidden;
}

.progress-bar span {
    display: block;
    height: 100%;
    background: #2563eb;
}

.btn-secondary {
    background: #f3f4f6;
    color: #111827;
    border: 1px solid #d1d5db;
    padding: 10px 18px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;   /* 🔥 THIS FIXES THE LINE */
    display: inline-flex;
    align-items: center;
}

.btn-primary {
    background: #2563eb;
    color: #fff;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    text-decoration: none;
}

.btn-activate {
    background: #e5ee38ff;      
    color: #fff;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    text-decoration: none;
    font-weight: 600;
}

.btn-activate:hover {
    background: #e5ee38ff;
}

.assign-table td.actions {
    justify-content: center;   /* 🔥 move table buttons slightly to center */
}
.assign-table th.actions-header {
    text-align: center;        /* center the word */
    padding-right: 40px;       /* adjust slightly to the right */
}
</style>
