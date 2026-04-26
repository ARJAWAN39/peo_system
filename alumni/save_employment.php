<?php
session_start();
require_once "../db.php";

$email = $_SESSION['email'];

$company = $_POST['company_name'];
$job = $_POST['job_title'];
$status = $_POST['employment_status'];
$industry = $_POST['industry'];
$years = $_POST['years_experience'];

$stmt = $pdo->prepare("
    UPDATE alumni_students
    SET company_name = ?, 
        job_title = ?, 
        employment_status = ?, 
        industry = ?, 
        years_experience = ?
    WHERE student_email = ?
");

$stmt->execute([$company, $job, $status, $industry, $years, $email]);

header("Location: profile.php");
exit;