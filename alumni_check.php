<?php
/**
 * ================================
 * ALUMNI AUTH & ACCESS CHECK
 * ================================
 */

require_once "../init.php";
require_once "../config.php";

/* ================================
   1. LOGIN CHECK
================================ */
if (!isset($_SESSION['user_id'], $_SESSION['role'], $_SESSION['email'])) {
    header("Location: ../login.php");
    exit;
}

/* ================================
   2. ROLE CHECK (ALUMNI ONLY)
================================ */
if ($_SESSION['role'] !== 'alumni') {
    header("Location: ../login.php");
    exit;
}

/* ================================
   3. VERIFY ALUMNI EXISTS
   (USING student_email)
================================ */
$student_email = $_SESSION['email'];

$stmt = $pdo->prepare("
    SELECT id, student_email
    FROM alumni_students
    WHERE student_email = ?
    LIMIT 1
");
$stmt->execute([$student_email]);
$alumni = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$alumni) {
    session_destroy();
    header("Location: ../login.php");
    exit;
}

/* ================================
   4. STORE ALUMNI INFO IN SESSION
================================ */
$_SESSION['alumni_id'] = $alumni['id'];
$_SESSION['matric_email'] = $alumni['student_email'];

/* ================================
   ACCESS GRANTED
================================ */
// Alumni is verified and allowed
