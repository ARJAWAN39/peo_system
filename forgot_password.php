<?php
include "config.php";

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if ($email === '') {
        $errors[] = "Please enter your email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email.";
    } else {
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() === 0) {
            $errors[] = "Email not found.";
        } else {
            header("Location: reset_password.php?email=" . urlencode($email));
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password - PEO Achievement System</title>
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

/* ===== LEFT PANEL (EXACT MATCH SIGN UP) ===== */
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
    text-align: left;
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

/* ===== FORGOT CARD (PIXEL MATCH SIGN UP) ===== */
.forgot-card {
    width: 420px;
    background: #ffffff;
    padding: 32px 30px;          /* EXACT */
    border-radius: 14px;
    box-shadow: 0 12px 32px rgba(0,0,0,0.12);
}

/* TITLE */
.forgot-card h3 {
    text-align: center;
    font-size: 25px;             /* EXACT */
    font-weight: 600;
    margin-bottom: 22px;
    color: #4169E1;
}

/* LABEL */
.form-label {
    font-size: 13px;             /* EXACT */
    color: #374151;
    margin-bottom: 4px;
    display: block;
}

/* INPUT */
.form-control {
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 10px 12px;          /* EXACT */
    font-size: 14px;             /* EXACT */
}

/* REMOVE SHIFT FEEL */
.form-control,
.form-label,
.btn-royal {
    width: 100%;
}

/* FOCUS */
.form-control:focus {
    background: #f3f4f6;
    border-color: #4169E1;
    box-shadow: none;
}

/* BUTTON */
.btn-royal {
    background-color: #4169E1;
    color: white;
    border: none;
    font-weight: 600;
    padding: 10px;               /* EXACT */
    font-size: 14px;             /* EXACT */
    border-radius: 10px;
}

.btn-royal:hover {
    background-color: #3154B7;
}

/* ALERT */
.alert {
    font-size: 14px;
}

/* LINK */
.text-link {
    color: #4169E1;
    text-decoration: none;
}

.text-link:hover {
    text-decoration: underline;
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
                <div class="feature">✔ Secure account recovery</div>
                <div class="feature">✔ Verified email validation</div>
                <div class="feature">✔ Easy password reset process</div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="auth-right">
        <div class="forgot-card">

            <h3>Forgot Password</h3>

            <?php if(!empty($errors)) : ?>
                <div class="alert alert-danger">
                    <?php foreach($errors as $e) echo htmlspecialchars($e) . "<br>"; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           placeholder="Enter your email"
                           required>
                </div>

                <button class="btn btn-royal w-100" type="submit">
                    Next
                </button>

                <div class="text-center mt-3">
                    <a href="login.php" class="text-link">Back to Login</a>
                </div>
            </form>

        </div>
    </div>

</div>

</body>
</html>
