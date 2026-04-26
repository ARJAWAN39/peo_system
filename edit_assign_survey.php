<?php
session_start();
require_once "config.php";
include "layout/header.php";
include "layout/sidebar.php";

/* =========================
   GET ASSIGNMENT ID
========================= */
if (!isset($_GET['id'])) {
    header("Location: assign_survey.php");
    exit;
}

$assignment_id = (int) $_GET['id'];

/* =========================
   FETCH ASSIGNMENT
========================= */
$stmt = $pdo->prepare("
    SELECT *
    FROM survey_assignments
    WHERE assignment_id = ?
");
$stmt->execute([$assignment_id]);
$assignment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$assignment) {
    header("Location: assign_survey.php");
    exit;
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
   HANDLE UPDATE
========================= */
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $assigned_date = $_POST['assigned_date'];
    $due_date      = $_POST['due_date'];
    $email_message = trim($_POST['email_message']);
    $status        = $assignment['status'];

    // Only allow changing survey & batch if NOT active
    if ($assignment['status'] !== 'active') {
        $survey_id  = (int) $_POST['survey_id'];
        $batch_year = (int) $_POST['batch_year'];

        // Prevent duplicate active survey
        if ($status === 'active') {
            $check = $pdo->prepare("
                SELECT 1
                FROM survey_assignments
                WHERE survey_id = ?
                AND batch_year = ?
                AND status = 'active'
                AND assignment_id != ?
                LIMIT 1
            ");
            $check->execute([$survey_id, $batch_year, $assignment_id]);

            if ($check->rowCount() > 0) {
                $error = "This survey is already ACTIVE for the selected batch.";
            }
        }
    } else {
        $survey_id  = $assignment['survey_id'];
        $batch_year = $assignment['batch_year'];
    }

    if ($error === "") {
        $update = $pdo->prepare("
            UPDATE survey_assignments
            SET survey_id = ?,
                batch_year = ?,
                assigned_date = ?,
                due_date = ?,
                email_message = ?
            WHERE assignment_id = ?
        ");

        $update->execute([
            $survey_id,
            $batch_year,
            $assigned_date,
            $due_date,
            $email_message ?: null,
            $assignment_id
        ]);

        $success = "Survey assignment updated successfully.";

        // Refresh data
        $stmt->execute([$assignment_id]);
        $assignment = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!-- =========================
     CONTENT
========================= -->
<div class="content-wrapper">

    <div class="page-header page-header-vertical">
        <h2>Edit Survey Assignment</h2>
        <p class="page-subtitle">
            Update assigned survey details
        </p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <div class="card compact-card">
        <form method="POST" class="form-grid">

            <div>
                <label>Survey</label>
                <select name="survey_id" <?= $assignment['status'] === 'active' ? 'disabled' : '' ?>>
                    <?php foreach ($surveys as $s): ?>
                        <option value="<?= $s['survey_id'] ?>"
                            <?= $s['survey_id'] == $assignment['survey_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['survey_title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Graduation Batch</label>
                <select name="batch_year" <?= $assignment['status'] === 'active' ? 'disabled' : '' ?>>
                    <?php for ($y = 2018; $y <= date('Y'); $y++): ?>
                        <option value="<?= $y ?>"
                            <?= $y == $assignment['batch_year'] ? 'selected' : '' ?>>
                            <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div>
                <label>Assigned Date</label>
                <input type="date" name="assigned_date"
                       value="<?= $assignment['assigned_date'] ?>" required>
            </div>

            <div>
                <label>Due Date</label>
                <input type="date" name="due_date"
                       value="<?= $assignment['due_date'] ?>" required>
            </div>

            <div class="full-width">
                <label>Notification Message</label>
                <textarea name="email_message" rows="3"><?= htmlspecialchars($assignment['email_message']) ?></textarea>
            </div>

            <div class="full-width actions">
                <button type="submit" class="btn-primary">Update Assignment</button>
                <a href="assign_survey.php" class="btn-secondary">Cancel</a>
            </div>

        </form>
    </div>

</div>
