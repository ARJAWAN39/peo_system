<?php
// auth.php
require_once __DIR__ . '/config.php';

function is_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function login_user(array $user): void {
    // regenerate session id to prevent fixation
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['full_name'] = $user['full_name'] ?? $user['username'];
    $_SESSION['role'] = $user['role'] ?? 'user';
}
