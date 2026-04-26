<?php
session_start();
require_once "../config.php";

$email = $_SESSION['email'];

if (!isset($_FILES['photo'])) {
    die("No file uploaded");
}

$file = $_FILES['photo'];

/* VALIDATION */
$allowed = ['jpg','jpeg','png'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    die("Only JPG, JPEG, PNG allowed");
}

/* GENERATE UNIQUE NAME */
$newName = uniqid() . "." . $ext;

/* SAFE PATH (IMPORTANT FIX) */
$uploadDir = __DIR__ . "/../uploads/profile/";

/* CREATE FOLDER IF NOT EXISTS */
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

/* FULL PATH */
$uploadPath = $uploadDir . $newName;

/* MOVE FILE */
if (move_uploaded_file($file['tmp_name'], $uploadPath)) {

    $stmt = $pdo->prepare("
        UPDATE alumni_students
        SET profile_photo = ?
        WHERE student_email = ?
    ");

    $stmt->execute([$newName, $email]);

    header("Location: profile.php");
    exit;

} else {
    die("Upload failed");
}
