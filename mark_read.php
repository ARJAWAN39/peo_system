<?php
require_once "config.php";

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE notification_id = ?");
    $stmt->execute([$_GET['id']]);
}

header("Location: notifications.php");
exit;
