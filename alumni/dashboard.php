<?php
require_once "../alumni_check.php";
require_once "../config.php";

include "../layout/alumni/header.php";
include "../layout/alumni/sidebar.php";

/* =========================================================
   SURVEY MODE (ONE QUESTION AT A TIME)
========================================================= */
if (isset($_GET['survey']) && isset($_GET['assignment_id'])) {

    require_once "survey_logic.php";
?>
<div class="alumni-dashboard survey-page">

    <!-- TOP RIGHT BACK LINK -->
    <div class="survey-topbar">
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>

    <div class="page-header">
        <h2><?= htmlspecialchars($survey_title) ?></h2>
        <p>Please answer all questions honestly.</p>
    </div>

    <!-- PROGRESS -->
    <div class="survey-progress">
        <div>Question <?= $step ?> of <?= $totalQuestions ?></div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: <?= $progressPercent ?>%"></div>
        </div>
        <small><?= $progressPercent ?>% completed</small>
    </div>

    <form method="post" class="survey-layout">

        <input type="hidden" name="step" value="<?= $step ?>">

        <div class="survey-card-modern">
            <div class="survey-card-body">

                <h4 class="survey-question-title">
                    <?= htmlspecialchars($currentQuestion['question_text']) ?>
                </h4>

                <?php
                $type = $currentQuestion['question_type'];
                $options = json_decode($currentQuestion['question_config'] ?? '[]', true);
                ?>

                <?php if ($type === 'short'): ?>

                    <input type="text"
                           name="answer"
                           class="survey-input"
                           include
                           value="<?= htmlspecialchars($existingAnswer ?? '') ?>"
                           required>

                <?php elseif ($type === 'paragraph'): ?>

                    <textarea name="answer"
                              class="survey-input"
                              rows="4"
                              required><?= htmlspecialchars($existingAnswer ?? '') ?></textarea>

                <?php elseif ($type === 'mcq'): ?>

                    <?php foreach (($options['options'] ?? []) as $opt): ?>
                        <label class="survey-option">
                            <input type="radio" name="answer"
                                   value="<?= htmlspecialchars($opt) ?>"
                                   <?= ($existingAnswer === $opt) ? 'checked' : '' ?>
                                   required>
                            <span><?= htmlspecialchars($opt) ?></span>
                        </label>
                    <?php endforeach; ?>

                <?php elseif ($type === 'checkbox'): ?>

                    <?php $saved = $existingAnswer ? json_decode($existingAnswer, true) : []; ?>
                    <?php foreach (($options['options'] ?? []) as $opt): ?>
                        <label class="survey-option">
                            <input type="checkbox" name="answer[]"
                                   value="<?= htmlspecialchars($opt) ?>"
                                   <?= in_array($opt, $saved) ? 'checked' : '' ?>>
                            <span><?= htmlspecialchars($opt) ?></span>
                        </label>
                    <?php endforeach; ?>

                <?php elseif ($type === 'dropdown'): ?>

                    <select name="answer" class="survey-input" required>
                        <option value="">-- Select an option --</option>

                        <?php foreach (($options['options'] ?? []) as $opt): 

                            // 🔥 FIX FORMAT
                            if (is_string($opt) && strpos($opt, ',') !== false) {
                                $parts = explode(',', $opt);
                                $text = trim($parts[0]);
                            } else {
                                $text = is_array($opt) ? ($opt['text'] ?? '') : $opt;
                            }
                        ?>

                            <option value="<?= htmlspecialchars($text) ?>"
                                <?= ($existingAnswer === $text) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($text) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                <?php elseif (in_array($type, ['scale', 'likert', 'rating_scale'])): ?>

                <?php
                $min = 1;
                $max = isset($options['scale_max']) ? (int)$options['scale_max'] : ($options['max'] ?? 5);
                $labelMin = $options['label_min'] ?? '';
                $labelMax = $options['label_max'] ?? '';
                ?>

                <div class="survey-scale">
                    <?php for ($i = $min; $i <= $max; $i++): ?>
                        <label class="survey-option">
                            <input type="radio" name="answer"
                                value="<?= $i ?>"
                                <?= ($existingAnswer == $i) ? 'checked' : '' ?>
                                required>
                            <span><?= $i ?></span>
                        </label>
                    <?php endfor; ?>

                    <div class="survey-scale-labels">
                        <span><?= htmlspecialchars($labelMin) ?></span>
                        <span><?= htmlspecialchars($labelMax) ?></span>
                    </div>
                </div>

                <?php elseif ($type === 'grid'): ?>

                <?php
                $rows = $options['rows'] ?? [];
                $cols = $options['columns'] ?? [];
                $saved = $existingAnswer ? json_decode($existingAnswer, true) : [];
                ?>

                <table class="survey-grid">
                    <thead>
                        <tr>
                            <th></th>
                            <?php foreach ($cols as $col): ?>
                                <th><?= htmlspecialchars($col) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td class="grid-row-label">
                                    <?= htmlspecialchars($row) ?>
                                </td>

                                <?php foreach ($cols as $col): ?>
                                    <td class="grid-cell">
                                        <input
                                            type="radio"
                                            name="answer[<?= htmlspecialchars($row) ?>]"
                                            value="<?= htmlspecialchars($col) ?>"
                                            <?= (($saved[$row] ?? '') === $col) ? 'checked' : '' ?>
                                            required
                                        >
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php elseif (in_array($type, ['rating', 'star'])): ?>

                <?php
                $max = $options['max'] ?? 5;
                ?>

                <div class="survey-rating">

                    <?php for ($i = $max; $i >= 1; $i--): ?>
                        <input
                            type="radio"
                            id="star<?= $i ?>"
                            name="answer"
                            value="<?= $i ?>"
                            <?= ($existingAnswer == $i) ? 'checked' : '' ?>
                            required
                        >
                        <label for="star<?= $i ?>" title="<?= $i ?> stars">★</label>
                    <?php endfor; ?>

                </div>

                <?php else: ?>
                    <p style="color:red;">⚠ Unsupported question type</p>
                <?php endif; ?>

            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="survey-actions-row">

            <?php if ($step > 1): ?>
                <button type="submit" name="prev" class="btn-secondary">← Previous</button>
            <?php else: ?>
                <div></div>
            <?php endif; ?>

            <?php if ($step < $totalQuestions): ?>
                <button type="submit" name="next" class="btn-primary">Next →</button>
            <?php else: ?>
                <button type="submit" name="submit" class="btn-primary">Submit</button>
            <?php endif; ?>

        </div>

    </form>
</div>

<?php
include "../layout/alumni/footer.php";
exit;
}

/* =========================================================
   SURVEY COMPLETED MODE
========================================================= */
if (isset($_GET['survey_completed'])) {
?>

<div class="survey-complete-wrapper">

    <div class="survey-complete-card">

        <div class="survey-complete-icon">✓</div>

        <h2 class="survey-complete-title">Survey Completed!</h2>

        <p class="survey-complete-text">
            Thank you for taking the time to complete this survey.
            Your feedback is valuable to us.
        </p>

        <a href="dashboard.php" class="survey-complete-btn">
            Back to Dashboard
        </a>

    </div>

</div>

<?php
    include "../layout/alumni/footer.php";
    exit;
}

/* =========================================================
   NORMAL DASHBOARD MODE
========================================================= */

$user_id = $_SESSION['user_id'];

/* GET ALUMNI BATCH */
$stmtBatch = $pdo->prepare("
    SELECT id, batch_year
    FROM alumni_students
    WHERE user_id = ?
    LIMIT 1
");
$stmtBatch->execute([$user_id]);
$alumni = $stmtBatch->fetch(PDO::FETCH_ASSOC);

if (!$alumni) {
    echo "<p>Alumni record not found.</p>";
    include "../layout/alumni/footer.php";
    exit;
}

$batch_year = $alumni['batch_year'];
$alumni_id = $alumni['id']; 

/* COUNTS */
// COMPLETED (ONLY submitted)
$stmtCompleted = $pdo->prepare("
    SELECT COUNT(DISTINCT sr.survey_id)
    FROM survey_responses sr
    WHERE sr.alumni_id = ?
    AND sr.submitted_at IS NOT NULL
");
$stmtCompleted->execute([$alumni_id]);
$completed = $stmtCompleted->fetchColumn();

// PENDING
$stmtPending = $pdo->prepare("
    SELECT COUNT(*)
    FROM survey_assignments
    WHERE batch_year = ?
    AND due_date >= CURDATE()
    AND survey_id NOT IN (
        SELECT survey_id
        FROM survey_responses
        WHERE alumni_id = ?
        AND submitted_at IS NOT NULL
    )
");
$stmtPending->execute([$batch_year, $alumni_id]);
$pending = $stmtPending->fetchColumn();

// OVERDUE
$stmtOverdue = $pdo->prepare("
    SELECT COUNT(*)
    FROM survey_assignments
    WHERE batch_year = ?
    AND due_date < CURDATE()
    AND survey_id NOT IN (
        SELECT survey_id
        FROM survey_responses
        WHERE alumni_id = ?
        AND submitted_at IS NOT NULL
    )
");
$stmtOverdue->execute([$batch_year, $alumni_id]);
$overdue = $stmtOverdue->fetchColumn();

/* SURVEY LIST */
$stmt = $pdo->prepare("
    SELECT
        sa.assignment_id,
        sa.survey_id,
        s.survey_title,
        sa.due_date,
        sa.status
    FROM survey_assignments sa
    JOIN surveys s ON sa.survey_id = s.survey_id
    WHERE sa.batch_year = ?
    ORDER BY sa.due_date ASC
");
$stmt->execute([$batch_year]);
$surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- DASHBOARD -->
<div class="alumni-dashboard">

    <div class="page-header">
        <h2>Alumni Dashboard</h2>
        <p>Welcome back. Please complete your assigned surveys.</p>
    </div>

    <div class="summary-grid">
        <div class="summary-card pending">
            <h4>PENDING</h4>
            <span><?= $pending ?></span>
        </div>
        <div class="summary-card completed">
            <h4>COMPLETED</h4>
            <span><?= $completed ?></span>
        </div>
        <div class="summary-card overdue">
            <h4>OVERDUE</h4>
            <span><?= $overdue ?></span>
        </div>
    </div>

    <?php if ($overdue > 0): ?>
        <div class="alert-box">
            ⚠ You have <?= $overdue ?> overdue survey(s).
        </div>
    <?php endif; ?>

    <div class="survey-list">
        <?php foreach ($surveys as $row): ?>

            <?php
            $stmtCheck = $pdo->prepare("
                SELECT submitted_at
                FROM survey_responses
                WHERE survey_id = ?
                AND alumni_id = ?
                LIMIT 1
            ");
            $stmtCheck->execute([$row['survey_id'], $alumni_id]);
            $response = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($response && $response['submitted_at'] !== null) {
                $badge = "badge-completed";
                $button = "<span class='btn-view'>COMPLETED</span>";
            } else {
                $badge = "badge-pending";
                $button = "<a href='dashboard.php?survey=1&assignment_id={$row['assignment_id']}' class='btn-primary'>START SURVEY</a>";
            }
            ?>

            <div class="survey-card-modern">
                <div class="survey-left">
                    <h4><?= htmlspecialchars($row['survey_title']) ?></h4>
                    <span class="badge <?= $badge ?>"><?= strtoupper($row['status']) ?></span>
                    <p>📅 Due: <?= date("d M Y", strtotime($row['due_date'])) ?></p>
                </div>
                <div class="survey-right"><?= $button ?></div>
            </div>

        <?php endforeach; ?>
    </div>

</div>

<?php include "../layout/alumni/footer.php"; ?>
