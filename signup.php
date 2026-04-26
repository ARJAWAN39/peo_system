<?php
require_once "db.php";
session_start();

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($email === '' || $password === '' || $confirm === '') {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {

        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "Email already registered.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users (email, password, role)
                VALUES (?, ?, 'alumni')
            ");
            $stmt->execute([$email, $hashed]);

            $success = "Account created successfully.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Account - PEO Achievement System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
* {
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    margin: 0;
    min-height: 100vh;
}

/* ===== SPLIT LAYOUT ===== */
.auth-wrapper {
    display: flex;
    height: 100vh;
}

/* ===== LEFT PANEL (SAME AS LOGIN) ===== */
.auth-left {
    flex: 1;
    background: linear-gradient(
        135deg,
        #2F5BEA 0%,
        #3B6CF6 45%,
        #4F7DFF 100%
    );
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px;
    box-shadow: inset -80px 0 120px rgba(255,255,255,0.08);
}

.left-content {
    max-width: 420px;
}

.icon-box {
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    margin: 0 auto 30px auto;
}

.auth-left h1 {
    font-size: 36px;
    font-weight: bold;
    line-height: 1.3;
    margin-bottom: 15px;
}

.subtitle {
    font-size: 17px;
    letter-spacing: 1px;
    opacity: 0.9;
    margin-bottom: 40px;
}

.features {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.feature {
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(6px);
    padding: 15px 20px;
    border-radius: 14px;
    font-size: 15px;
}

/* ===== RIGHT PANEL ===== */
.auth-right {
    flex: 1;
    background-color: #f1f1f1;
    background-image: radial-gradient(rgba(0,0,0,0.02) 1px, transparent 1px);
    background-size: 20px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ===== SIGNUP CARD ===== */
.auth-card {
    width: 420px;
    background: #ffffff;
    padding: 32px 30px;
    border-radius: 14px;
    box-shadow: 0 12px 32px rgba(0,0,0,0.12);
}

.auth-card h3 {
    text-align: center;
    font-size: 25px;
    font-weight: 600;
    margin-bottom: 22px;
    color: #4169E1;
}

.form-label {
    font-size: 13px;
    color: #374151;
    margin-bottom: 4px;
}

.form-control {
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 10px 12px;
    font-size: 14px;
}

.form-control:focus {
    background: #f3f4f6;
    border-color: #4169E1;
    box-shadow: none;
}

.password-wrapper {
    position: relative;
}

.password-eye {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-5%);
    cursor: pointer;
    font-size: 20px;
    color: #9ca3af;
}

.btn-primary {
    background: #4169E1;
    border: none;
    border-radius: 10px;
    padding: 10px;
    font-size: 14px;
    font-weight: 600;
    margin-top: 8px;
}

.btn-primary:hover {
    background: #3154B7;
}

.auth-footer {
    text-align: center;
    margin-top: 16px;
    font-size: 13px;
    color: #374151;
}

.auth-footer a {
    color: #4169E1;
    text-decoration: none;
    font-weight: 500;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .auth-left {
        display: none;
    }
}
</style>
</head>

<body>

<div class="auth-wrapper">

    <!-- LEFT PANEL -->
    <div class="auth-left">
        <div class="left-content">
            <div class="icon-box">🎓</div>

            <h1>PEO ACHIEVEMENT<br>ANALYSIS SYSTEM</h1>
            <p class="subtitle">FOR UNDERGRADUATE PROGRAM AT FKKTM</p>

            <div class="features">
                <div class="feature">✔ Track student outcomes effectively</div>
                <div class="feature">✔ Generate comprehensive reports</div>
                <div class="feature">✔ Monitor program effectiveness</div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL (SIGN UP FORM) -->
    <div class="auth-right">
        <div class="auth-card">

            <h3>Create Account</h3>

            <?php if ($error): ?>
                <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success py-2"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           placeholder="Enter email"
                           required>
                </div>

                <div class="mb-3 password-wrapper">
                    <label class="form-label">Password</label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="Enter password"
                           required>
                    <span class="password-eye" onclick="togglePassword('password')">👁</span>
                </div>

                <div class="mb-3 password-wrapper">
                    <label class="form-label">Confirm Password</label>
                    <input type="password"
                           name="confirm_password"
                           id="confirm_password"
                           class="form-control"
                           placeholder="Re-enter password"
                           required>
                    <span class="password-eye" onclick="togglePassword('confirm_password')">👁</span>
                </div>

                <button class="btn btn-primary w-100">Sign Up</button>

                <div class="auth-footer">
                    Already have an account?
                    <a href="login.php">Login</a>
                </div>

            </form>
        </div>
    </div>

</div>

<script>
function togglePassword(id) {
    const field = document.getElementById(id);
    field.type = field.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
