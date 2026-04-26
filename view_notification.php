<?php
session_start();
require_once "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

if(!isset($_GET['id'])){
    header("Location: notifications.php");
    exit();
}

$id = $_GET['id'];

/* fetch notification */
$stmt = $pdo->prepare("
    SELECT * FROM notifications
    WHERE notification_id = ? AND user_id = ?
");
$stmt->execute([$id,$admin_id]);

$notification = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$notification){
    echo "Notification not found";
    exit();
}

/* mark as read automatically */
$pdo->prepare("
    UPDATE notifications
    SET is_read = 1
    WHERE notification_id = ?
")->execute([$id]);
?>

<!DOCTYPE html>
<html>
<head>
<title>Notification Details</title>

<style>
body{
font-family:Arial;
background:#f6f7fb;
padding:30px;
}

.box{
background:white;
padding:25px;
border-radius:8px;
border:1px solid #ddd;
max-width:600px;
}

h2{
margin-top:0;
}

.label{
font-weight:bold;
margin-top:10px;
}

.back{
display:inline-block;
margin-top:20px;
padding:8px 14px;
border:1px solid #111827;
text-decoration:none;
color:#111827;
border-radius:4px;
}

.back:hover{
background:#111827;
color:white;
}
</style>

</head>

<body>

<div class="box">

<h2><?= htmlspecialchars($notification['title']) ?></h2>

<p><?= htmlspecialchars($notification['message']) ?></p>

<div class="label">Type</div>
<p><?= $notification['type'] ?></p>

<?php if($notification['batch_year']) : ?>
<div class="label">Batch Year</div>
<p><?= $notification['batch_year'] ?></p>
<?php endif; ?>

<div class="label">Created At</div>
<p><?= date("d M Y, h:i A", strtotime($notification['created_at'])) ?></p>

<a href="notifications.php" class="back">Back to Notifications</a>

</div>

</body>
</html>