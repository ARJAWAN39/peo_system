<?php
include "db.php";

$errors = [];
$success = '';

$email = $_GET['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password === '' || $confirm_password === '') {
        $errors[] = "All fields are required.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    $strongPassword = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
    if (!preg_match($strongPassword, $password)) {
        $errors[] = "Password must be at least 8 characters and include uppercase, lowercase, number, and symbol.";
    }

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        if ($stmt->execute([$hashed, $email])) {
            $success = "Password successfully reset. <a href='login.php'>Login now</a>";
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password - PEO Achievement System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

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

/* ===== LEFT PANEL (MATCH SIGN UP & FORGOT) ===== */
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
    font-size: 36px;             /* MATCH */
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
    font-size: 15px;             /* MATCH */
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

/* ===== RESET CARD (EXACT MATCH SIGN UP) ===== */
.reset-card {
    width: 420px;
    background: #ffffff;
    padding: 32px 30px;          /* EXACT SAME */
    border-radius: 14px;
    box-shadow: 0 12px 32px rgba(0,0,0,0.12);
}

/* TITLE */
.reset-card h3 {
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

/* ENSURE PERFECT ALIGNMENT */
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

/* PASSWORD ICON */
.position-relative {
    position: relative;
}

.right-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 1.2rem;
    color: #6c757d;
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
                <div class="feature">✔ Strong password protection</div>
                <div class="feature">✔ Secure reset process</div>
                <div class="feature">✔ Account safety ensured</div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="auth-right">
        <div class="reset-card">

            <h3>Reset Password</h3>

            <?php if(!empty($errors)) : ?>
                <div class="alert alert-danger">
                    <?php foreach($errors as $e) echo htmlspecialchars($e) . "<br>"; ?>
                </div>
            <?php endif; ?>

            <?php if($success) : ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3 position-relative">
                    <label class="form-label">New Password</label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control pe-5"
                           placeholder="Enter new password"
                           required>
                    <i class="bi bi-eye right-icon" onclick="togglePassword('password')"></i>
                </div>

                <div class="mb-3 position-relative">
                    <label class="form-label">Confirm Password</label>
                    <input type="password"
                           name="confirm_password"
                           id="confirm_password"
                           class="form-control pe-5"
                           placeholder="Re-enter new password"
                           required>
                    <i class="bi bi-eye right-icon" onclick="togglePassword('confirm_password')"></i>
                </div>

                <button class="btn btn-royal w-100" type="submit">
                    Reset Password
                </button>

                <div class="text-center mt-3">
                    <a href="login.php" class="text-link">Back to Login</a>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    const icon = input.nextElementSibling;
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    }
}
</script>

</body>
</html>
