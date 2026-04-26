<?php
/**
 * survey_logic.php (FINAL FIXED VERSION)
 */
require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ================================
   VALIDATE INPUT
================================ */
if (!isset($_GET['assignment_id'])) {
    die("Invalid survey access.");
}

$assignment_id = (int) $_GET['assignment_id'];

$step = isset($_POST['step'])
    ? max(1, (int) $_POST['step'])
    : (isset($_GET['step']) ? max(1, (int) $_GET['step']) : 1);

/* ================================
   GET SURVEY FROM ASSIGNMENT
================================ */
$stmtSurvey = $pdo->prepare("
    SELECT sa.survey_id, s.survey_title
    FROM survey_assignments sa
    JOIN surveys s ON sa.survey_id = s.survey_id
    WHERE sa.assignment_id = ?
    LIMIT 1
");
$stmtSurvey->execute([$assignment_id]);
$surveyData = $stmtSurvey->fetch(PDO::FETCH_ASSOC);

if (!$surveyData) {
    die("Survey not found.");
}

$survey_id    = $surveyData['survey_id'];
$survey_title = $surveyData['survey_title'];

/* ================================
   FETCH QUESTIONS
================================ */
$stmtQuestions = $pdo->prepare("
    SELECT q.*
    FROM survey_question_map sqm
    JOIN survey_questions q ON q.question_id = sqm.question_id
    WHERE sqm.survey_id = ?
    ORDER BY q.question_id ASC
");
$stmtQuestions->execute([$survey_id]);
$questions = $stmtQuestions->fetchAll(PDO::FETCH_ASSOC);

$totalQuestions = count($questions);
if ($totalQuestions === 0) {
    die("No questions available.");
}

/* ================================
   FIX STEP LIMITS
================================ */
if ($step > $totalQuestions) {
    $step = $totalQuestions;
}

$currentIndex = $step - 1;
$currentQuestion = $questions[$currentIndex];

/* ================================
   INIT RESPONSE
================================ */
if (!isset($_SESSION['response_id'])) {

    if (!isset($_SESSION['alumni_id'])) {
        die("Session alumni not found.");
    }

    $alumni_id = $_SESSION['alumni_id'];

    // CHECK EXISTING RESPONSE
    $checkStmt = $pdo->prepare("
        SELECT response_id FROM survey_responses
        WHERE survey_id = ? AND alumni_id = ?
        LIMIT 1
    ");
    $checkStmt->execute([$survey_id, $alumni_id]);
    $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $_SESSION['response_id'] = $existing['response_id'];
    } else {
        // CREATE NEW RESPONSE (NO submit yet)
        $stmtInsert = $pdo->prepare("
            INSERT INTO survey_responses (survey_id, alumni_id, created_at)
            VALUES (?, ?, NOW())
        ");
        $stmtInsert->execute([$survey_id, $alumni_id]);
        $_SESSION['response_id'] = $pdo->lastInsertId();
    }
}

$response_id = $_SESSION['response_id'];

/* ================================
   SAVE ANSWER
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {

    $answer = is_array($_POST['answer'])
        ? json_encode($_POST['answer'])
        : trim($_POST['answer']);

    $score = is_numeric($answer) ? (int)$answer : NULL;

    // DELETE OLD ANSWER
    $stmtDelete = $pdo->prepare("
        DELETE FROM survey_answers
        WHERE response_id = ? AND question_id = ?
    ");
    $stmtDelete->execute([$response_id, $currentQuestion['question_id']]);

    // INSERT NEW ANSWER
    $stmtInsertAnswer = $pdo->prepare("
        INSERT INTO survey_answers (response_id, question_id, answer_text, score)
        VALUES (?, ?, ?, ?)
    ");
    $stmtInsertAnswer->execute([
        $response_id,
        $currentQuestion['question_id'],
        $answer,
        $score
    ]);

    /* ================================
       NAVIGATION CONTROL
    ================================= */

    // NEXT
    if (isset($_POST['next'])) {
        $nextStep = min($totalQuestions, $step + 1);
        header("Location: dashboard.php?survey=1&assignment_id=$assignment_id&step=$nextStep");
        exit;
    }

    // PREVIOUS
    if (isset($_POST['prev'])) {
        $prevStep = max(1, $step - 1);
        header("Location: dashboard.php?survey=1&assignment_id=$assignment_id&step=$prevStep");
        exit;
    }

    // SUBMIT FINAL
    if (isset($_POST['submit'])) {

        // MARK AS SUBMITTED NOW
        $stmtUpdate = $pdo->prepare("
            UPDATE survey_responses
            SET submitted_at = NOW()
            WHERE response_id = ?
        ");
        $stmtUpdate->execute([$response_id]);

        unset($_SESSION['response_id']);

        header("Location: dashboard.php?survey_completed=1");
        exit;
    }
}

/* ================================
   LOAD EXISTING ANSWER
================================ */
$stmtAnswer = $pdo->prepare("
    SELECT answer_text
    FROM survey_answers
    WHERE response_id = ? AND question_id = ?
");
$stmtAnswer->execute([$response_id, $currentQuestion['question_id']]);
$existingAnswer = $stmtAnswer->fetchColumn();

/* ================================
   PROGRESS
================================ */
$progressPercent = round(($step / $totalQuestions) * 100);
?>