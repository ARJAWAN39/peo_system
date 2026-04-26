<?php
session_start();

// LOGIN CHECK
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// SAVE NOTIFICATION SETTINGS INTO SESSION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Store checkbox values (1 = enabled, 0 = disabled)
    $_SESSION['survey_notif']     = isset($_POST['survey_notif']) ? 1 : 0;
    $_SESSION['system_notif']     = isset($_POST['system_notif']) ? 1 : 0;
    $_SESSION['weekly_summary']   = isset($_POST['weekly_summary']) ? 1 : 0;
    $_SESSION['low_response']     = isset($_POST['low_response']) ? 1 : 0;

    // Optional success flag
    $_SESSION['notifications_updated'] = true;
}

// Redirect back to profile & settings page
header("Location: profile_settings.php");
exit;
