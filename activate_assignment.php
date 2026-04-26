<?php
session_start();
require_once "config.php";

/* =========================
   VALIDATE ID
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
    SELECT survey_id, batch_year, status
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
   ONLY DRAFT CAN BE ACTIVATED
========================= */
if ($assignment['status'] !== 'draft') {
    header("Location: assign_survey.php");
    exit;
}

/* =========================
   CHECK EXISTING ACTIVE SURVEY
========================= */
$check = $pdo->prepare("
    SELECT 1
    FROM survey_assignments
    WHERE batch_year = ?
    AND status = 'active'
    AND assignment_id != ?
    LIMIT 1
");
$check->execute([
    $assignment['batch_year'],
    $assignment_id
]);

if ($check->rowCount() > 0) {
    // optional: pass error message
    header("Location: assign_survey.php?error=active_exists");
    exit;
}

/* =========================
   ACTIVATE ASSIGNMENT
========================= */
$update = $pdo->prepare("
    UPDATE survey_assignments
    SET status = 'active'
    WHERE assignment_id = ?
");
$update->execute([$assignment_id]);

/* =========================
   REDIRECT BACK
========================= */
header("Location: assign_survey.php");
exit;
