<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

/* ======================================
   FETCH PEOs FROM peo_plo_mapping
====================================== */
$peos = $pdo->query("
    SELECT DISTINCT peo_code
    FROM peo_plo_mapping
    WHERE peo_code IS NOT NULL
    ORDER BY peo_code ASC
")->fetchAll(PDO::FETCH_ASSOC);

/* ======================================
   EDIT MODE DETECTION
====================================== */
$question_id   = $_GET['id'] ?? null;
$isEdit        = false;

$question_title  = '';
$description     = '';
$question_type   = '';
$peo_id          = '';
$question_config = [];

if ($question_id) {
    $isEdit = true;

    $stmt = $pdo->prepare("
        SELECT *
        FROM survey_questions
        WHERE question_id = ?
    ");
    $stmt->execute([$question_id]);
    $question = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$question) {
        header("Location: survey_builder.php");
        exit;
    }

    $question_title = $question['question_text'] ?? '';
    $description    = $question['description'] ?? '';
    $question_type  = $question['question_type'] ?? '';
    $peo_id         = $question['peo_id'] ?? '';

    if (!empty($question['question_config'])) {
        $question_config = json_decode($question['question_config'], true);
    }
}
?>

<?php include "layout/header.php"; ?>
<?php include "layout/sidebar.php"; ?>

<div class="content-wrapper">

    <div class="page-header">
        <div>
            <h2><?= $isEdit ? 'Edit Question' : 'Add New Question' ?></h2>
            <p>Create a survey question mapped to a PEO</p>
        </div>

        <a href="survey_builder.php" class="btn-back">← Back</a>
    </div>

    <div class="card">
        <form action="save_question.php" method="POST">

            <?php if ($isEdit): ?>
                <input type="hidden" name="question_id" value="<?= $question_id ?>">
            <?php endif; ?>

            <label>Question Title</label>
            <input type="text"
                   name="question_title"
                   value="<?= htmlspecialchars($question_title) ?>"
                   required>

            <label>Description (Optional)</label>
            <input type="text"
                   name="description"
                   value="<?= htmlspecialchars($description) ?>">

            <label>Question Type</label>
            <select name="question_type" id="questionType" required>
                <option value="">-- Select Type --</option>
                <option value="short"     <?= $question_type === 'short' ? 'selected' : '' ?>>Short answer</option>
                <option value="paragraph" <?= $question_type === 'paragraph' ? 'selected' : '' ?>>Paragraph</option>
                <option value="mcq"       <?= $question_type === 'mcq' ? 'selected' : '' ?>>Multiple choice</option>
                <option value="checkbox"  <?= $question_type === 'checkbox' ? 'selected' : '' ?>>Checkboxes</option>
                <option value="dropdown"  <?= $question_type === 'dropdown' ? 'selected' : '' ?>>Dropdown</option>
                <option value="scale"     <?= $question_type === 'scale' ? 'selected' : '' ?>>Linear scale</option>
                <option value="rating"    <?= $question_type === 'rating' ? 'selected' : '' ?>>Rating</option>
                <option value="grid"      <?= $question_type === 'grid' ? 'selected' : '' ?>>Multiple choice grid</option>
            </select>

            <!-- Dynamic config area -->
            <div id="configArea"></div>

            <!-- ✅ FIX: PASS CONFIG TO JS SAFELY -->
            <script>
                window.existingQuestionConfig = <?= json_encode($question_config) ?>;
            </script>

            <!-- ✅ PEO SELECTION -->
            <label>PEO</label>
            <select name="peo_id" required>
                <option value="">-- Select PEO --</option>
                <?php foreach ($peos as $p): ?>
                    <option value="<?= htmlspecialchars($p['peo_code']) ?>"
                        <?= $peo_id === $p['peo_code'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['peo_code']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <?= $isEdit ? 'Update Question' : 'Save Question' ?>
                </button>
            </div>

            <input type="hidden" name="question_type_hidden" id="questionTypeHidden">

        </form>
    </div>
</div>

<style>
/* =========================
   CARD & FORM BASE
========================= */
.card {
    background: #fff;
    padding: 24px;
    border-radius: 8px;
}

label {
    font-weight: 500;
    margin-top: 16px;
    display: block;
}

input, select, textarea {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    font-size: 14px;
}

.preview {
    background: #f9fafb;
    padding: 12px;
    border-radius: 6px;
    margin-top: 12px;
}

/* =========================
   OPTION / ROW / COLUMN
========================= */
.option-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 10px;
}

/* radio / checkbox / number */
.option-row .symbol {
    min-width: 20px;
    text-align: center;
    font-size: 16px;
    color: #374151;
}

/* option input */
.option-row input[type="text"] {
    flex: 1;
}

/* =========================
   REMOVE (✖) BUTTON
========================= */
.option-row button {
    background: transparent;
    border: 1px solid #e5e7eb;
    color: #ef4444;
    font-size: 16px;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.2s ease;
}

.option-row button:hover {
    background: #fee2e2;
    border-color: #ef4444;
}

/* =========================
   ADD OPTION / ROW / COLUMN
========================= */
.add-option,
button[onclick*="addRow"],
button[onclick*="addCol"] {
    margin-top: 10px;
    background: #f3f4f6;
    border: 1px dashed #9ca3af;
    color: #111827;
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.add-option:hover,
button[onclick*="addRow"]:hover,
button[onclick*="addCol"]:hover {
    background: #e5e7eb;
    border-color: #6b7280;
}

/* =========================
   BUTTONS
========================= */
.btn-back {
    background: #2563eb;
    color: #fff;
    padding: 8px 14px;
    border-radius: 6px;
    text-decoration: none;
}

.form-actions {
    margin-top: 24px;
    display: flex;
    justify-content: flex-end;
}

.btn-primary {
    background: #111827;
    color: #ffffff;
    padding: 10px 22px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
}

.btn-primary:hover {
    background: #1f2937;
}

/* =========================
   PREVIEW ONLY (ADMIN VIEW)
========================= */
.preview-box {
    background: #f3f4f6;
    border: 1px dashed #d1d5db;
    border-radius: 8px;
    padding: 14px;
    color: #6b7280;
    font-size: 14px;
    pointer-events: none;
    margin-top: 8px;
}

.preview-box.small {
    height: 44px;
    display: flex;
    align-items: center;
}

.preview-box.large {
    height: 90px;
}

.preview-hint {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 6px;
}
</style>

<script src="assets/js/add_question.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const typeSelect = document.getElementById("questionType");

    if (typeSelect && typeof renderConfig === "function") {
        renderConfig(typeSelect.value);
    }

    // 🔥 IMPORTANT FIX (use columns NOT cols)
    window.existingQuestionConfig = <?= json_encode($question_config) ?>;
});
</script>

</body>
</html>
