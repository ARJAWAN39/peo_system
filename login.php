
<?php
require_once "config.php";
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Please enter both email and password.';
    } else {

        // 1️⃣ Get user by email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            // 2️⃣ Secure session
            session_regenerate_id(true);

            // 3️⃣ Store common user session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['role']    = $user['role'];

            // 4️⃣ IF ALUMNI → GET alumni_id (IMPORTANT FIX)
            if ($user['role'] === 'alumni') {
            $stmt = $pdo->prepare("
                SELECT id 
                FROM alumni_students 
                WHERE user_id = ? 
                LIMIT 1
            ");
            $stmt->execute([$user['user_id']]);
            $alumni = $stmt->fetch(PDO::FETCH_ASSOC);

            // 🔑 AUTO CREATE alumni record if missing
            if (!$alumni) {
                $stmtInsert = $pdo->prepare("
                    INSERT INTO alumni_students (user_id, student_email, student_name)
                    VALUES (?, ?, ?)
                ");
                $stmtInsert->execute([$user['user_id'], $user['email'], $user['name']]);

                $alumni_id = $pdo->lastInsertId();
            } else {
                $alumni_id = $alumni['id'];
            }

            $_SESSION['alumni_id'] = $alumni_id;
            header("Location: alumni/dashboard.php");
            exit;
        }

            // 5️⃣ ADMIN
            if ($user['role'] === 'admin') {
                header("Location: dashboard.php");
                exit;
            }

            // 6️⃣ Unknown role
            $errors[] = "Unauthorized role.";

        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - PEO Achievement System</title>
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

/* ===== LEFT PANEL ===== */
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

    /* OPTION B – SUBTLE INNER GLOW */
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
    font-size: 15px;
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

/* ===== LOGIN CARD (UNCHANGED DESIGN) ===== */
.login-card {
    background-color: #ffffff;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    width: 100%;
    max-width: 420px;
}

.login-card h3 {
    color: #4169E1;
    text-align: center;
    margin-bottom: 25px;
    font-weight: bold;
}

.btn-royal {
    background-color: #4169E1;
    color: white;
    border: none;
    font-weight: 500;
}

.btn-royal:hover {
    background-color: #3154B7;
}

.form-control:focus {
    border-color: #4169E1;
    box-shadow: 0 0 0 0.2rem rgba(65,105,225,0.25);
}

.text-link {
    color: #4169E1;
    text-decoration: none;
}

.text-link:hover {
    text-decoration: underline;
}

.alert {
    font-size: 14px;
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

    <!-- LEFT SIDE -->
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

    <!-- RIGHT SIDE (LOGIN FORM) -->
    <div class="auth-right">
        <div class="login-card">

            <h3>Login</h3>

            <?php if (!empty($errors)) : ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $e) echo htmlspecialchars($e) . "<br>"; ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control" 
                        placeholder="Enter email"
                        required
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-control" 
                        placeholder="Enter password"
                        required>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="showPass" onclick="togglePassword()">
                    <label class="form-check-label" for="showPass">
                        Show Password
                    </label>
                </div>

                <button class="btn btn-royal w-100" type="submit">Login</button>

                <div class="text-center mt-3">
                    <a href="forgot_password.php" class="text-link">Forgot Username/Password?</a>
                </div>

                <div class="text-center mt-2">
                    <a href="signup.php" class="text-link">Sign Up</a> (Create a new account)
                </div>

            </form>

        </div>
    </div>

</div>

<script>
function togglePassword() {
    let pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
