<?php
session_start();
require_once "config.php";
include "layout/header.php";
include "layout/sidebar.php";

/* =========================
   CHECK ADMIN SESSION
========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

/* =========================
   🔥 AUTO CLEAN INVALID NOTIFICATIONS (ADD HERE)
========================= */
$pdo->exec("
    DELETE FROM notifications
    WHERE related_survey_id IS NOT NULL
    AND NOT EXISTS (
        SELECT 1 FROM survey_assignments sa
        WHERE sa.survey_id = notifications.related_survey_id
        AND sa.batch_year = notifications.batch_year
    )
");

/* =========================
   🔥 AUTO GENERATE OVERDUE
========================= */
$today = date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT * FROM survey_assignments
    WHERE due_date < ?
    AND status = 'active'
");
$stmt->execute([$today]);

$overdueSurveys = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($overdueSurveys as $row) {

    $survey_id = $row['survey_id'];
    $batch_year = $row['batch_year'];

    // prevent duplicate
    $check = $pdo->prepare("
        SELECT COUNT(*) FROM notifications
        WHERE related_survey_id = ?
        AND batch_year = ?
        AND type = 'overdue'
    ");
    $check->execute([$survey_id, $batch_year]);

    if ($check->fetchColumn() == 0) {

        $insert = $pdo->prepare("
            INSERT INTO notifications
            (user_id, title, message, type, related_survey_id, batch_year, is_read, created_at)
            VALUES (?, ?, ?, 'overdue', ?, ?, 0, NOW())
        ");

        $insert->execute([
            $_SESSION['user_id'],
            "Survey Overdue",
            "Survey for Batch $batch_year is overdue!",
            $survey_id,
            $batch_year
        ]);
    }
}

/* =========================
   🔥 AUTO GENERATE ALERT (FIXED)
========================= */
$today = date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT * FROM survey_assignments
    WHERE due_date < ?
    AND status = 'active'
");
$stmt->execute([$today]);

$surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($surveys as $row) {

    $survey_id = $row['survey_id'];
    $batch_year = $row['batch_year'];

    // total students
    $stmtTotal = $pdo->prepare("
        SELECT COUNT(*) FROM alumni_students
        WHERE batch_year = ?
    ");
    $stmtTotal->execute([$batch_year]);
    $total = $stmtTotal->fetchColumn();

    // total responses
    $stmtDone = $pdo->prepare("
        SELECT COUNT(DISTINCT alumni_id)
        FROM survey_responses
        WHERE survey_id = ?
    ");
    $stmtDone->execute([$survey_id]);
    $done = $stmtDone->fetchColumn();

    $percentage = ($total > 0) ? ($done / $total) * 100 : 0;

    // ONLY overdue + low response
    if ($percentage < 50) {

        $check = $pdo->prepare("
            SELECT COUNT(*) FROM notifications
            WHERE related_survey_id = ?
            AND batch_year = ?
            AND type = 'alert'
        ");
        $check->execute([$survey_id, $batch_year]);

        if ($check->fetchColumn() == 0) {

            $insert = $pdo->prepare("
                INSERT INTO notifications
                (user_id, title, message, type, related_survey_id, batch_year, is_read, created_at)
                VALUES (?, ?, ?, 'alert', ?, ?, 0, NOW())
            ");

            $insert->execute([
                $_SESSION['user_id'],
                "Low Response Rate",
                "Overdue survey for Batch $batch_year has response rate below 50%.",
                $survey_id,
                $batch_year
            ]);
        }
    }
}

/* =========================
   🔥 FIXED FILTER LOGIC
========================= */
$filter = $_GET['filter'] ?? 'all';

$whereClause = "";

if ($filter == 'unread') {
    $whereClause = "WHERE is_read = 0";
} elseif ($filter == 'pending') {
    $whereClause = "WHERE type = 'pending'";
} elseif ($filter == 'alert') {
    $whereClause = "WHERE type = 'alert'";
} elseif ($filter == 'overdue') {
    $whereClause = "WHERE type = 'overdue'";
}

/* =========================
   🔥 FETCH NOTIFICATIONS (FIXED)
========================= */
$sql = "SELECT * FROM notifications $whereClause ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   COUNTS
========================= */
$countStmt = $pdo->prepare("
    SELECT
        SUM(is_read = 0) AS unread_count,
        SUM(type = 'pending') AS pending_count,
        SUM(type = 'overdue') AS overdue_count,
        SUM(type = 'alert') AS alert_count
    FROM notifications
");
$countStmt->execute();
$counts = $countStmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Notifications Center</title>

<link rel="stylesheet" href="assets/css/notifications.css">
</head>

<body>

<div class="header">
    <h2>NOTIFICATIONS CENTRE</h2>

    <form action="mark_all_read.php" method="POST" style="display:inline;">
        <button class="mark-all-btn">MARK ALL AS READ</button>
    </form>
</div>

<div class="cards">
    <div class="card">Unread<br><strong><?= $counts['unread_count'] ?? 0 ?></strong></div>
    <div class="card">Pending Surveys<br><strong><?= $counts['pending_count'] ?? 0 ?></strong></div>
    <div class="card">Overdue<br><strong><?= $counts['overdue_count'] ?? 0 ?></strong></div>
    <div class="card">Alerts<br><strong><?= $counts['alert_count'] ?? 0 ?></strong></div>
</div>

<div class="tabs">
    <a href="?filter=all" class="<?= $filter=='all'?'active':'' ?>">ALL</a>
    <a href="?filter=unread" class="<?= $filter=='unread'?'active':'' ?>">UNREAD</a>
    <a href="?filter=pending" class="<?= $filter=='pending'?'active':'' ?>">PENDING</a>
    <a href="?filter=alert" class="<?= $filter=='alert'?'active':'' ?>">ALERTS</a>
</div>

<?php if (empty($notifications)): ?>
<div class="no-data">No notifications found.</div>
<?php endif; ?>

<?php foreach ($notifications as $n): ?>

<?php
$batch = $n['batch_year'];
$survey_id = $n['related_survey_id'];

/* =========================
   TOTAL STUDENTS
========================= */
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM alumni_students 
    WHERE batch_year = ?
");
$stmt->execute([$batch]);
$total = $stmt->fetchColumn();

/* =========================
   TOTAL RESPONDED
========================= */
$stmt = $pdo->prepare("
    SELECT COUNT(DISTINCT alumni_id)
    FROM survey_responses
    WHERE survey_id = ?
");
$stmt->execute([$survey_id]);
$completed = $stmt->fetchColumn();

/* =========================
   PERCENTAGE
========================= */
$percentage = ($total > 0) ? round(($completed / $total) * 100) : 0;

$type = $n['type'];
?>

<div class="notification 
<?= $n['is_read']==0 ? 'unread' : '' ?> 
<?= $type ?>">

    <div class="notif-top">
        <h4>
            <?= htmlspecialchars($n['title']) ?>
            <span class="badge <?= $type ?>">
            <?= strtoupper($type) ?>
        </span>
        </h4>

        <span class="notif-time">
            <?= date("d M Y, h:i A", strtotime($n['created_at'])) ?>
        </span>
    </div>

    <p><?= htmlspecialchars($n['message']) ?></p>

    <div class="actions">

        <a href="#"
        onclick="openModal(
        '<?= htmlspecialchars($n['title'], ENT_QUOTES) ?>',
        '<?= htmlspecialchars($n['message'], ENT_QUOTES) ?>',
        '<?= $type ?>',
        '<?= $n['batch_year'] ?>',
        '<?= date("d M Y, h:i A", strtotime($n['created_at'])) ?>',
        '<?= $percentage ?>',
        '<?= $completed ?>',
        '<?= $total ?>'
        )">VIEW DETAILS</a>

        <?php if ($n['is_read'] == 0): ?>
        <a href="mark_read.php?id=<?= $n['notification_id'] ?>">MARK AS READ</a>
        <?php endif; ?>

    </div>

</div>

<?php endforeach; ?>

<!-- MODAL -->
<div id="notifModal" class="modal">
  <div class="modal-content">

    <div class="modal-header">
      <h3 id="modalTitle"></h3>
      <span class="close">&times;</span>
    </div>

    <p id="modalMessage" class="modal-message"></p>

    <div class="modal-details">
      <p><strong>Type:</strong> <span id="modalType"></span></p>
      <p><strong>Batch:</strong> <span id="modalBatch"></span></p>
      <p><strong>Date:</strong> <span id="modalDate"></span></p>
    </div>

    <div class="progress-section">
        <div class="progress-bar">
            <div id="progressFill" class="progress-fill"></div>
        </div>
        <p id="modalRate" class="progress-text"></p>
    </div>

  </div>
</div>

<script>
function openModal(title, message, type, batch, date, percent, done, total) {

    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalMessage').innerText = message;
    document.getElementById('modalType').innerText = type;
    document.getElementById('modalBatch').innerText = batch;
    document.getElementById('modalDate').innerText = date;

    document.getElementById('modalRate').innerText =
        percent + "% (" + done + "/" + total + " students)";

    let bar = document.getElementById('progressFill');
    bar.style.width = percent + "%";

    if (percent < 50) {
        bar.style.background = "#ef4444";
    } else if (percent < 75) {
        bar.style.background = "#f59e0b";
    } else {
        bar.style.background = "#22c55e";
    }

    document.getElementById('notifModal').style.display = "block";
}

document.querySelector('.close').onclick = function() {
    document.getElementById('notifModal').style.display = "none";
}

window.onclick = function(event) {
    let modal = document.getElementById('notifModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

</body>
</html>
