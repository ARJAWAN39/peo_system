<?php
session_start(); // ✅ ONLY HERE

// LOGIN CHECK
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'layout/header.php';
include 'layout/sidebar.php';

// Page router
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        include 'dashboard_home.php';
        break;

    case 'reports':
        include 'reports.php';
        break;

    case 'notifications':
        include 'notifications.php';
        break;

    case 'profile':
        include 'profile_settings.php';
        break;

    default:
        include 'dashboard_home.php';
        break;
}

include 'layout/footer.php';
