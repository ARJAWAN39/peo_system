<?php
require_once __DIR__ . '/auth.php';
require_login();

$full_name = $_SESSION['full_name'] ?? null;
$email = $_SESSION['email'] ?? 'User';
$display_name = $full_name ?: $email;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css"> <!-- link to your CSS file -->
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">My Dashboard</div>
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Users</a></li>
            <li><a href="#" class="logout">Logout</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <h1>Welcome, <?php echo htmlspecialchars($display_name); ?></h1>
        <p>This is your dashboard content.</p>
    </div>

</body>
</html>
