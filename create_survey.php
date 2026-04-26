<?php
session_start();
require_once "db.php";

/* =========================
   LOGIN CHECK
========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

/* =========================
   ACTION HANDLER
========================= */
$action   = $_GET['action'] ?? null;
$surveyId = $_GET['id'] ?? null;

/* =========================
   RECEIVE SELECTED QUESTIONS  ✅ FIXED POSITION
========================= */
if (isset($_POST['selected_questions'])) {
    $_SESSION['selected_questions'] = json_decode($_POST['selected_questions'], true);
}

$selectedQuestionIds = $_SESSION['selected_questions'] ?? [];

/* =========================
   DELETE SURVEY
========================= */
if ($action === 'delete' && $surveyId) {

    $pdo->prepare("DELETE FROM survey_question_map WHERE survey_id = ?")
        ->execute([$surveyId]);

    $pdo->prepare("DELETE FROM surveys WHERE survey_id = ?")
        ->execute([$surveyId]);

    header("Location: create_survey.php");
    exit;
}

/* =========================
   FETCH SURVEY FOR EDIT
========================= */
$editSurvey = null;

if ($action === 'edit' && $surveyId) {
    $stmt = $pdo->prepare("SELECT * FROM surveys WHERE survey_id = ?");
    $stmt->execute([$surveyId]);
    $editSurvey = $stmt->fetch(PDO::FETCH_ASSOC);
}

/* =========================
   UPDATE SURVEY
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_survey_id'])) {

    $pdo->prepare("
        UPDATE surveys
        SET survey_title = ?
        WHERE survey_id = ?
    ")->execute([
        trim($_POST['survey_title']),
        $_POST['update_survey_id']
    ]);

    header("Location: create_survey.php");
    exit;
}

/* =========================
   CREATE SURVEY  ✅ AUTO-MAP FIXED
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['survey_title'])
    && !isset($_POST['update_survey_id'])
) {

    $title       = trim($_POST['survey_title']);
    $description = trim($_POST['survey_description'] ?? '');
    $selected    = $_SESSION['selected_questions'] ?? [];

    if ($title !== '' && !empty($selected)) {

        $pdo->prepare("
            INSERT INTO surveys (survey_title, created_at)
            VALUES (?, NOW())
        ")->execute([$title]);

        $newId = $pdo->lastInsertId();

        $map = $pdo->prepare("
            INSERT INTO survey_question_map (survey_id, question_id)
            VALUES (?, ?)
        ");

        foreach ($selected as $qid) {
            $map->execute([$newId, $qid]);
        }

        unset($_SESSION['selected_questions']);

        header("Location: create_survey.php");
        exit;
    }
}

/* =========================
   FETCH SELECTED QUESTIONS
========================= */
$selectedQuestions = [];

if (!empty($selectedQuestionIds)) {
    $in = implode(',', array_fill(0, count($selectedQuestionIds), '?'));
    $stmt = $pdo->prepare("
        SELECT question_id, question_text, question_type
        FROM survey_questions
        WHERE question_id IN ($in)
    ");
    $stmt->execute($selectedQuestionIds);
    $selectedQuestions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* =========================
   FETCH CREATED SURVEYS
========================= */
$createdSurveys = $pdo->query("
    SELECT s.survey_id, s.survey_title, s.created_at,
           COUNT(m.question_id) AS total_questions
    FROM surveys s
    LEFT JOIN survey_question_map m ON s.survey_id = m.survey_id
    GROUP BY s.survey_id
    ORDER BY s.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "layout/header.php"; ?>
<?php include "layout/sidebar.php"; ?>

<div class="content-wrapper">

    <div class="page-header">
        <div>
            <h2><?= $editSurvey ? 'Edit Survey' : 'Create New Survey' ?></h2>
            <p class="page-subtitle">
                Define survey title and review selected questions
            </p>
        </div>

        <a href="survey_builder.php" class="btn-back">
            ← Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">

            <form method="POST">

                <div class="form-group">
                    <label class="form-label">Survey Title</label>
                    <input type="text"
                           name="survey_title"
                           class="form-input"
                           value="<?= htmlspecialchars($editSurvey['survey_title'] ?? '') ?>"
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Survey Description <span class="optional">(optional)</span>
                    </label>
                    <textarea class="form-textarea"
                              name="survey_description"
                              rows="3"></textarea>
                </div>

                <?php if ($editSurvey): ?>
                    <input type="hidden"
                           name="update_survey_id"
                           value="<?= $editSurvey['survey_id'] ?>">
                <?php endif; ?>

                <?php if (!$editSurvey && !empty($selectedQuestions)): ?>
                    <div style="margin-top:20px;">
                        <strong>Selected Questions (<?= count($selectedQuestions) ?>)</strong>
                    </div>

                    <?php foreach ($selectedQuestions as $i => $q): ?>
                        <div class="selected-question-item">
                            <strong>Q<?= $i + 1 ?>.</strong>
                            <?= htmlspecialchars($q['question_text']) ?>
                            <span class="question-type">
                                (<?= strtoupper($q['question_type']) ?>)
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div style="margin-top:24px;">
                    <button type="submit" class="btn-primary">
                        <?= $editSurvey ? 'Update Survey' : 'Create Survey' ?>
                    </button>
                </div>

            </form>

        </div>
    </div>

    <div class="card" style="margin-top:32px;">
        <div class="card-header">
            <strong>Created Surveys</strong>
            <div>Total: <?= count($createdSurveys) ?> surveys</div>
        </div>

        <div class="card-body">
            <?php foreach ($createdSurveys as $s): ?>
                <div class="created-survey-card">
                    <div class="created-survey-header">
                        <div>
                            <strong><?= htmlspecialchars($s['survey_title']) ?></strong><br>
                            <span class="survey-date">
                                <?= date("d/m/Y", strtotime($s['created_at'])) ?>
                            </span>
                            <span class="survey-badge">
                                <?= $s['total_questions'] ?> questions
                            </span>
                        </div>

                        <div class="survey-actions">
                            <a class="btn-edit-sm"
                               href="?action=edit&id=<?= $s['survey_id'] ?>">
                               ✎ EDIT
                            </a>

                            <a class="btn-delete-sm"
                               href="?action=delete&id=<?= $s['survey_id'] ?>"
                               onclick="return confirm('Delete this survey?')">
                               DELETE
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<style>
/* ===== HEADER ===== */
.page-subtitle {
    margin-top: 6px;
    font-size: 14px;
    color: #6b7280;
}

/* ===== FORM ===== */
.form-group {
    margin-bottom: 20px;
    max-width: 520px;
}

.form-label {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

.form-input,
.form-textarea {
    width: 100%;
    padding: 10px 12px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    font-size: 14px;
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37,99,235,0.15);
}

.selected-question-item {
    padding: 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    margin-bottom: 10px;
    background: #f9fafb;
}
.question-type {
    color: #6b7280;
    font-size: 13px;
    margin-left: 6px;
}
.created-survey-card {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 16px;
}
.created-survey-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.survey-date {
    color: #6b7280;
    font-size: 13px;
    margin-right: 10px;
}
.survey-badge {
    background: #e0ecff;
    color: #2563eb;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
}

/* ACTION BUTTONS */
.survey-actions {
    display: flex;
    gap: 10px;
}
.btn-edit-sm {
    background: #3de264ff;
    color: #fff;
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
}
.btn-edit-sm:hover {
    background: #218838;
}
.btn-delete-sm {
    background: #dc2626;
    color: #fff;
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
}
.btn-delete-sm:hover {
    background: #b91c1c;
}
/* Header layout */
.survey-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Back button (same style as PLO page) */
.btn-back {
    background: #2563eb;
    color: #ffffff;
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.2s ease;
}

.btn-back:hover {
    background: #1e40af;
}
</style>
