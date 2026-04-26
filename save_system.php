<?php
session_start();

// LOGIN CHECK
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// SAVE SYSTEM SETTINGS INTO SESSION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Toggle settings
    $_SESSION['auto_assign']     = isset($_POST['auto_assign']) ? 1 : 0;
    $_SESSION['email_reminder']  = isset($_POST['email_reminder']) ? 1 : 0;

    // Export format
    $_SESSION['export_format']   = $_POST['export_format'] ?? 'PDF';

    // Optional success flag
    $_SESSION['system_updated'] = true;
}

// Redirect back to profile & settings page
header("Location: profile_settings.php");
exit;
