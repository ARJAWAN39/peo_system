<?php
session_start();
require_once "db.php";

/* =========================
   CHECK LOGIN
========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* =========================
   VALIDATE ID
========================= */
if (!isset($_GET['id'])) {
    header("Location: assign_survey.php");
    exit();
}

$assignment_id = (int) $_GET['id'];

/* =========================
   GET SURVEY INFO FIRST
========================= */
$stmt = $pdo->prepare("
    SELECT survey_id, batch_year
    FROM survey_assignments
    WHERE assignment_id = ?
");
$stmt->execute([$assignment_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

/* =========================
   DELETE ASSIGNMENT
========================= */
$delete = $pdo->prepare("
    DELETE FROM survey_assignments
    WHERE assignment_id = ?
");
$delete->execute([$assignment_id]);

/* =========================
   🔥 DELETE RELATED NOTIFICATIONS
========================= */
if ($data) {

    $survey_id = $data['survey_id'];
    $batch_year = $data['batch_year'];

    $deleteNotif = $pdo->prepare("
        DELETE FROM notifications
        WHERE related_survey_id = ?
        AND batch_year = ?
    ");
    $deleteNotif->execute([$survey_id, $batch_year]);
}

/* =========================
   REDIRECT BACK
========================= */
header("Location: assign_survey.php");
exit();