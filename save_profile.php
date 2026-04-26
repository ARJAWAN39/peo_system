<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$full_name = $_POST['full_name'];
$email = $_POST['email'];
$department = $_POST['department'];

$stmt = $pdo->prepare("
    UPDATE users 
    SET full_name = ?, email = ?, department = ?
    WHERE user_id = ?
");

$stmt->execute([$full_name, $email, $department, $user_id]);

// update session also (important)
$_SESSION['full_name'] = $full_name;
$_SESSION['email'] = $email;
$_SESSION['department'] = $department;

header("Location: profile_settings.php");
exit;