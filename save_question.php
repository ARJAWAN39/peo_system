<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$survey_id = 1;

/* ==========================
   BASIC DATA
========================== */
$question_id = $_POST['question_id'] ?? null;

$title  = trim($_POST['question_title'] ?? '');
$type   = trim($_POST['question_type'] 
        ?? $_POST['question_type_hidden'] 
        ?? '');
$peo_id = trim($_POST['peo_id'] ?? '');

if ($title === '' || $type === '' || $peo_id === '') {
    die("Required fields missing.");
}

/* ==========================
   BUILD QUESTION CONFIG
========================== */
$config = null;

/* MULTIPLE CHOICE / CHECKBOX / DROPDOWN */
if (in_array($type, ['mcq', 'checkbox', 'dropdown'])) {

    $options = array_values(
        array_filter($_POST['options'] ?? [], fn($v) => trim($v) !== '')
    );

    $scoredOptions = [];

    $score = count($options); // highest score first

    foreach ($options as $opt) {
        $scoredOptions[] = [
            'text' => $opt,
            'score' => $score
        ];
        $score--;
    }

    $config = json_encode([
        'options' => $scoredOptions
    ]);
}

/* RATING */
elseif ($type === 'rating') {
    $config = json_encode([
        'max' => $_POST['rating_max'] ?? 5
    ]);
}

/* LINEAR SCALE */
elseif ($type === 'scale') {
    $config = json_encode([
        'scale_max' => $_POST['scale_max'] ?? 5,
        'label_min' => $_POST['scale_label_min'] ?? '',
        'label_max' => $_POST['scale_label_max'] ?? ''
    ]);
}

/* GRID ✅ FIXED */
elseif ($type === 'grid') {
    $rows = $_POST['grid_rows'] ?? [];
    $columns = $_POST['grid_columns'] ?? []; // 🔥 FIX HERE

    $config = json_encode([
        'rows' => array_values(
            array_filter($rows, fn($v) => trim($v) !== '')
        ),
        'columns' => array_values(
            array_filter($columns, fn($v) => trim($v) !== '')
        )
    ]);
}

/* SHORT / PARAGRAPH */
elseif (in_array($type, ['short', 'paragraph'])) {
    $config = null;
}

/* ==========================
   SAVE
========================== */
if ($question_id) {

    $stmt = $pdo->prepare("
        UPDATE survey_questions
        SET
            question_text   = ?,
            question_type   = ?,
            peo_id          = ?,
            question_config = ?
        WHERE question_id = ?
    ");
    $stmt->execute([
        $title,
        $type,
        $peo_id,
        $config,
        $question_id
    ]);

} else {

    $stmt = $pdo->prepare("
        INSERT INTO survey_questions
        (survey_id, question_text, question_type, peo_id, question_config)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $survey_id,
        $title,
        $type,
        $peo_id,
        $config
    ]);
}

/* ==========================
   DONE
========================== */
header("Location: survey_builder.php");
exit;
