<?php
session_start();
require_once "config.php";

/* =========================
   LOGIN CHECK
========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

/* =========================
   FETCH QUESTIONS
========================= */
$stmtQuestions = $pdo->query("
    SELECT *
    FROM survey_questions
    ORDER BY question_id ASC
");
$questions = $stmtQuestions->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "layout/header.php"; ?>
<?php include "layout/sidebar.php"; ?>

<div class="content-wrapper">

    <!-- HIDDEN FORM FOR CREATE SURVEY -->
    <form id="createSurveyForm" method="POST" action="create_survey.php">
        <input type="hidden" name="selected_questions" id="selectedQuestionsInput">
    </form>

    <!-- PAGE HEADER -->
    <div class="page-header survey-builder-header">
        <div class="header-left">
            <h2>SURVEY QUESTION BUILDER</h2>
            <p>Create and manage survey questions mapped to PEOs</p>
        </div>

        <div class="header-right">
            <div class="survey-actions-group">
                <button id="createSurveyBtn"
                        type="button"
                        class="btn-primary create-survey-btn"
                        disabled>
                    <span id="createSurveyText">+ Create Survey (0 selected)</span>
                </button>

                <a href="create_survey.php"
                   class="view-survey-btn"
                   title="View surveys">👁</a>
            </div>

            <a href="add_question.php" class="add-btn">+ Add Question</a>
        </div>
    </div>

    <!-- QUESTION LIST -->
    <div class="card">
        <div class="card-header">
            <div class="question-list-header">
                <strong>Question List</strong>
                <span class="question-count"><?= count($questions) ?> questions</span>
            </div>
        </div>

        <div class="card-body">

        <?php if (empty($questions)): ?>
            <div class="empty-state">No questions added yet.</div>
        <?php endif; ?>

<?php
$qNo = 1;

$typeMap = [
    'short'     => 'SHORT ANSWER',
    'paragraph' => 'PARAGRAPH',
    'mcq'       => 'MULTIPLE CHOICE',
    'checkbox'  => 'CHECKBOX',
    'dropdown'  => 'DROPDOWN',
    'scale'     => 'LINEAR SCALE',
    'rating'    => 'RATING',
    'grid'      => 'MULTIPLE CHOICE GRID'
];

foreach ($questions as $q):

    $type = $q['question_type'];
    $config = json_decode($q['question_config'] ?? '{}', true);
?>

<div class="survey-question" data-question-id="<?= $q['question_id'] ?>">

    <div class="question-select">
        <input type="checkbox"
               class="select-question"
               value="<?= $q['question_id'] ?>">
    </div>

    <div class="question-tags">
        <span class="tag dark">Q<?= $qNo ?></span>
        <span class="tag outline"><?= htmlspecialchars($q['peo_id']) ?></span>
        <span class="tag light"><?= $typeMap[$type] ?></span>
    </div>

    <div class="question-text">
        <?= htmlspecialchars($q['question_text']) ?>
    </div>

    <div class="question-preview">

        <?php if ($type === 'short'): ?>
            <div class="question-note">Short answer response</div>
        <?php endif; ?>

        <?php if ($type === 'paragraph'): ?>
            <div class="question-note">Paragraph response</div>
        <?php endif; ?>

        <?php if (in_array($type, ['mcq','checkbox','dropdown']) && !empty($config['options'])): ?>
            <div class="preview-label">Options:</div>
            <div class="question-options">
                <?php foreach ($config['options'] as $opt): ?>
                    <span class="option-chip">
                        <?= htmlspecialchars(is_array($opt) ? implode(', ', $opt) : $opt) ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($q['question_type'] === 'scale'): ?>
            <?php
            $max = $config['scale_max'] ?? 5;
            $labelMin = $config['label_min'] ?? '';
            $labelMax = $config['label_max'] ?? '';
            ?>

            <div class="preview-label">Scale:</div>

            <div class="linear-scale-preview">
                <div class="scale-point">
                    <div class="scale-number">1</div>
                    <?php if ($labelMin): ?>
                        <div class="scale-label">(<?= htmlspecialchars($labelMin) ?>)</div>
                    <?php endif; ?>
                </div>

                <div class="scale-point">
                    <div class="scale-number"><?= $max ?></div>
                    <?php if ($labelMax): ?>
                        <div class="scale-label">(<?= htmlspecialchars($labelMax) ?>)</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($q['question_type'] === 'rating'): ?>
            <?php
            $max = $config['rating_max'] ?? 5;
            ?>
            <div class="preview-label">Rating:</div>
            <div class="rating-preview">
                <?php for ($i = 1; $i <= $max; $i++): ?>
                    <span class="option-chip">★</span>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <?php if ($q['question_type'] === 'grid'): ?>
            <?php
            $rows = $config['rows'] ?? [];
            $cols = $config['columns'] ?? [];
            ?>

            <?php if (!empty($rows)): ?>
                <div class="preview-label">Rows:</div>
                <div class="question-options">
                    <?php foreach ($rows as $row): ?>
                        <span class="option-chip"><?= htmlspecialchars($row) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($cols)): ?>
            <div class="preview-label" style="margin-top:8px;">Columns:</div>
            <div class="question-options">
                <?php foreach ($cols as $col): ?>
                    <span class="option-chip"><?= htmlspecialchars($col) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php endif; ?>

    </div>

    <!-- actions -->
    <div class="question-actions-right">
        <a href="add_question.php?id=<?= $q['question_id'] ?>" class="btn-edit-sm">✎ EDIT</a>
        <a href="delete_question.php?id=<?= $q['question_id'] ?>"
           class="btn-delete-sm"
           onclick="return confirm('Delete this question?')">DELETE</a>
    </div>

</div>

<?php
$qNo++;
endforeach;
?>

        </div>
    </div>
</div>

<!-- =========================
     EXISTING CSS (UNCHANGED)
========================= -->
<style>
.survey-question {
    position: relative;
    padding-left: 46px;
}

.question-select {
    position: absolute;
    top: 18px;
    left: 16px;
}

.question-select input {
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.survey-question.selected {
    background: #f0f7ff;
    border-color: #2563eb;
}
</style>

<!-- =========================
     UPDATED JS (SESSION + REDIRECT)
========================= -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const typeSelect = document.getElementById("questionType");
    const configArea = document.getElementById("configArea");
    const hiddenType = document.getElementById("questionTypeHidden");

    if (!typeSelect || !configArea || !hiddenType) return;

    // 🔥 CONFIG FROM PHP (EDIT MODE)
    let existingConfig = window.existingQuestionConfig;

    // =========================
    // MAIN RENDER FUNCTION
    // =========================
    function renderConfig(type, isInitial = false) {
        configArea.innerHTML = "";
        hiddenType.value = type;

        // If admin changes type manually → reset config
        if (!isInitial) {
            existingConfig = null;
        }

        /* ===== SHORT ANSWER ===== */
        if (type === "short") {
            configArea.innerHTML = `
                <div class="preview-box small">Short answer text</div>
                <div class="preview-hint">Respondent will answer this question</div>
            `;
        }

        /* ===== PARAGRAPH ===== */
        if (type === "paragraph") {
            configArea.innerHTML = `
                <div class="preview-box large">Long answer text</div>
                <div class="preview-hint">Respondent will answer this question</div>
            `;
        }

        /* ===== MCQ / CHECKBOX / DROPDOWN ===== */
        if (["mcq", "checkbox", "dropdown"].includes(type)) {
            renderOptions(type);
        }

        /* ===== LINEAR SCALE ===== */
        if (type === "scale") {
            const max = existingConfig?.scale_max || 5;
            const minLabel = existingConfig?.label_min || "";
            const maxLabel = existingConfig?.label_max || "";

            configArea.innerHTML = `
                <label>Scale Range</label>
                <select name="scale_max">
                    <option value="5" ${max == 5 ? "selected" : ""}>1 to 5</option>
                    <option value="7" ${max == 7 ? "selected" : ""}>1 to 7</option>
                </select>

                <label>Label for 1 (optional)</label>
                <input type="text" name="scale_label_min" value="${minLabel}">

                <label>Label for max (optional)</label>
                <input type="text" name="scale_label_max" value="${maxLabel}">
            `;
        }

        /* ===== RATING ===== */
        if (type === "rating") {
            const stars = existingConfig?.scale_max || 5;

            configArea.innerHTML = `
                <label>Number of stars</label>
                <select name="rating_max">
                    <option value="5" ${stars == 5 ? "selected" : ""}>5 Stars</option>
                </select>
            `;
        }

        /* ===== GRID ===== */
        if (type === "grid") {
            configArea.innerHTML = `
                <label>Rows</label>
                <div id="rows"></div>
                <button type="button" onclick="addRow()">+ Add row</button>

                <label>Columns</label>
                <div id="cols"></div>
                <button type="button" onclick="addCol()">+ Add column</button>
            `;

            const rows = existingConfig?.rows || ["", ""];
            const cols = existingConfig?.cols || ["", ""];

            rows.forEach(r => addRow(r));
            cols.forEach(c => addCol(c));
        }
    }

    // =========================
    // OPTIONS
    // =========================
    window.renderOptions = function (type) {
        configArea.innerHTML = `
            <label>Options</label>
            <div id="options"></div>
            <button type="button" class="add-option"
                onclick="addOption('${type}')">+ Add option</button>
        `;

        const opts = existingConfig?.options?.length
            ? existingConfig.options
            : ["", ""];

        opts.forEach(opt => addOption(type, opt));
    };

    window.addOption = function (type, value = "") {
        const options = document.getElementById("options");
        const count = options.children.length + 1;

        let symbol = "◯";
        if (type === "checkbox") symbol = "☐";
        if (type === "dropdown") symbol = count + ".";

        const div = document.createElement("div");
        div.className = "option-row";
        div.innerHTML = `
            <span class="symbol">${symbol}</span>
            <input type="text" name="options[]" value="${value}">
            <button type="button" onclick="this.parentElement.remove()">✖</button>
        `;
        options.appendChild(div);
    };

    // =========================
    // GRID HELPERS
    // =========================
    window.addRow = function (value = "") {
        const rows = document.getElementById("rows");
        const div = document.createElement("div");
        div.className = "option-row";
        div.innerHTML = `
            <input type="text" name="grid_rows[]" value="${value}">
            <button type="button" onclick="this.parentElement.remove()">✖</button>
        `;
        rows.appendChild(div);
    };

    window.addCol = function (value = "") {
        const cols = document.getElementById("cols");
        const div = document.createElement("div");
        div.className = "option-row";
        div.innerHTML = `
            <input type="text" name="grid_columns[]" value="${value}">
            <button type="button" onclick="this.parentElement.remove()">✖</button>
        `;
        cols.appendChild(div);
    };

    // =========================
    // EVENTS
    // =========================
    typeSelect.addEventListener("change", function () {
        renderConfig(this.value, false);
    });

    // =========================
    // AUTO LOAD (EDIT MODE)
    // =========================
    if (typeSelect.value) {
        renderConfig(typeSelect.value, true);
    }

    // =========================
    // FINAL SAFETY NET
    // =========================
    document.querySelector("form").addEventListener("submit", function () {
        if (!hiddenType.value) {
            hiddenType.value = typeSelect.value;
        }
    });

});
document.addEventListener("DOMContentLoaded", function () {

    const selectedQuestions = new Set();

    const createBtn = document.getElementById("createSurveyBtn");
    const createText = document.getElementById("createSurveyText");
    const hiddenInput = document.getElementById("selectedQuestionsInput");
    const form = document.getElementById("createSurveyForm");

    // Safety check
    if (!createBtn || !createText || !hiddenInput || !form) {
        console.error("Survey Builder: Required elements missing");
        return;
    }

    // =========================
    // UPDATE BUTTON UI
    // =========================
    function updateCreateButton() {
        const count = selectedQuestions.size;

        createText.textContent = `+ Create Survey (${count} selected)`;

        if (count > 0) {
            createBtn.disabled = false;
        } else {
            createBtn.disabled = true;
        }
    }

    // =========================
    // CHECKBOX HANDLING
    // =========================
    document.querySelectorAll(".select-question").forEach(cb => {

        cb.addEventListener("change", function () {

            const questionId = this.value;
            const card = this.closest(".survey-question");

            if (this.checked) {
                selectedQuestions.add(questionId);
                card.classList.add("selected");
            } else {
                selectedQuestions.delete(questionId);
                card.classList.remove("selected");
            }

            updateCreateButton();
        });
    });

    // =========================
    // CREATE SURVEY BUTTON
    // =========================
    createBtn.addEventListener("click", function () {

        if (selectedQuestions.size === 0) {
            alert("Please select at least one question.");
            return;
        }

        // Convert selected IDs to JSON
        hiddenInput.value = JSON.stringify(Array.from(selectedQuestions));

        // DEBUG (you can remove later)
        console.log("Submitting selected questions:", hiddenInput.value);

        // SUBMIT FORM → redirect to create_survey.php
        form.submit();
    });

});
</script>


<style>
.question-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.question-count {
    color: #111827;
}

.survey-question {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 16px;
    background: #fff;
    position: relative;
}

.question-tags {
    margin-bottom: 6px;
}

.question-actions-right {
    position: absolute;
    top: 16px;
    right: 16px;
    display: flex;
    gap: 10px;
}

.tag {
    display: inline-block;
    padding: 4px 10px;
    font-size: 12px;
    border-radius: 20px;
    margin-right: 6px;
}

.tag.dark {
    background: #111827;
    color: #fff;
}

.tag.outline {
    border: 1px solid #111827;
    color: #111827;
}

.tag.light {
    background: #e5e7eb;
    color: #111827;
    border: 1px solid #d1d5db;
}

.question-text {
    font-weight: 600;
    margin-bottom: 10px;
}

.question-preview {
    margin-top: 8px;
}

.preview-label {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 6px;
}

.question-options {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.option-chip {
    padding: 6px 14px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 13px;
    background: #f9fafb;
}

.question-note {
    font-size: 13px;
    color: #374151;
    margin-top: 6px;
}

.empty-state {
    text-align: center;
    color: #6b7280;
    padding: 40px 0;
}

.add-btn {
    background: #111827;
    color: #ffffff !important;
    padding: 10px 16px;
    border-radius: 6px;
    text-decoration: none;
}

.add-btn:hover {
    background: #1f2937;
}

.btn-edit-sm {
    background: #3de264ff;
    color: #ffffff;
    padding: 6px 14px;
    font-size: 13px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
}

.btn-edit-sm:hover {
    background: #218838;
}

.btn-delete-sm {
    background: #dc2626;
    color: #ffffff;
    padding: 6px 14px;
    font-size: 13px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
}

.btn-delete-sm:hover {
    background: #b91c1c;
}
.rating-preview {
    margin-top: 6px;
}

.rating-preview .star {
    font-size: 22px;
    color: #9ca3af; /* grey */
    margin-right: 6px;
    cursor: default;
}
/* ===== QUESTION SELECTION ===== */
.survey-question {
    position: relative;
    padding-left: 46px; /* space for checkbox */
}

.question-select {
    position: absolute;
    top: 18px;
    left: 16px;
}

.survey-question.selected {
    background: #f0f7ff;
    border-color: #2563eb;
}

.question-select input[type="checkbox"] {
    width: 16px;
    height: 16px;
    cursor: pointer;
}
/* HEADER LAYOUT FIX */
.survey-builder-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
}

.header-left {
    flex: 1;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* GROUP WRAPPER */
.survey-actions-group {
    display: inline-flex;
    align-items: stretch;
    border-radius: 8px;
    overflow: hidden; /* 🔴 IMPORTANT: hides the seam */
}

/* CREATE SURVEY BUTTON */
.create-survey-btn {
    border-radius: 0;
    border-right: 1px solid #1f2937; /* subtle divider */
}

/* VIEW (EYE) BUTTON */
.view-survey-btn {
    background: #111827;
    color: #fff;
    padding: 10px 12px;
    border-radius: 0 8px 8px 0;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.view-survey-btn:hover {
    background: #1f2937;
}

/* ===== LINEAR SCALE PREVIEW (MATCH OPTION STYLE) ===== */
.linear-scale-preview {
    display: flex;
    justify-content: space-between;
    margin-top: 8px;
    max-width: 200px;
}

.scale-point {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.scale-number {
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 6px 14px;
    font-weight: 600;
    font-size: 14px;
    color: #111827;
}

.scale-label {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
}
</style>

</body>
</html>
