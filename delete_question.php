<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$question_id = $_GET['id'] ?? null;

if (!$question_id) {
    header("Location: survey_builder.php");
    exit;
}

/* =========================
   DELETE QUESTION (SAFE)
========================= */

$stmt = $pdo->prepare("
    DELETE FROM survey_questions
    WHERE question_id = ?
");
$stmt->execute([$question_id]);

header("Location: survey_builder.php");
exit;
