<?php
session_start();

// LOGIN CHECK
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// SAVE LANGUAGE & TIMEZONE INTO SESSION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $_SESSION['language'] = $_POST['language'] ?? 'English';
    $_SESSION['timezone'] = $_POST['timezone'] ?? 'Asia/Kuala_Lumpur';

    // Optional success flag
    $_SESSION['language_updated'] = true;
}

// Redirect back to profile & settings page
header("Location: profile_settings.php");
exit;
