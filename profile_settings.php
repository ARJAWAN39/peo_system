<?php
session_start();

require_once "db.php";

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'layout/header.php';
include 'layout/sidebar.php';
?>

<style>
/* =========================
   PAGE CONTAINER
========================= */
.profile-container {
    max-width: 1100px;
    margin: auto;
    padding: 1px;
}

/* =========================
   HEADER
========================= */
.profile-header {
    margin-bottom: 20px;
}

.profile-header h2 {
    margin: 0;
    font-size: 26px;
    font-weight: 700;
    color: #1f2937;
}

.profile-header p {
    margin: 4px 0 20px;
    color: #6b7280;
    font-size: 14px;
}

/* =========================
   CARD
========================= */
.card {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    margin-bottom: 25px;
    transition: 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

/* =========================
   CARD HEADER
========================= */
.card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
}

.icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.icon.blue { background:#e0ecff; color:#2563eb; }
.icon.purple { background:#f3e8ff; color:#9333ea; }
.icon.green { background:#e7f9ed; color:#16a34a; }

.card-title {
    font-weight: 600;
    font-size: 16px;
    color: #111827;
}

/* =========================
   🔥 PERSONAL INFO GRID (FIXED)
========================= */
.profile-grid {
    display: grid !important;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    width: 100%;
}

/* EACH FIELD */
.form-group {
    display: flex;
    flex-direction: column;
}

/* LABEL */
.form-group label {
    font-size: 13px;
    margin-bottom: 6px;
    color: #374151;
}

/* INPUT */
.form-group input,
.form-group select {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    font-size: 14px;
    outline: none;
    transition: 0.2s;
    box-sizing: border-box;
}

/* FOCUS */
.form-group input:focus,
.form-group select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37,99,235,0.1);
}

/* =========================
   BUTTONS
========================= */
.btn {
    border: none;
    border-radius: 12px;
    padding: 12px 18px;
    font-size: 14px;
    cursor: pointer;
    color: #fff;
    font-weight: 600;
    transition: 0.2s;
}

.btn.blue { background:#2563eb; }
.btn.blue:hover { background:#1d4ed8; }

.btn.purple { background:#9333ea; }
.btn.purple:hover { background:#7e22ce; }

.btn.green { background:#16a34a; }
.btn.green:hover { background:#15803d; }

/* =========================
   BUTTON ALIGNMENT
========================= */
.card-actions {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}

/* =========================
   SECOND ROW GRID
========================= */
.row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* =========================
   TOGGLE ROW
========================= */
.toggle-row {
    background: #f9fafb;
    border-radius: 12px;
    padding: 14px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
    transition: 0.2s;
}

.toggle-row:hover {
    background: #f3f4f6;
}

/* =========================
   TOGGLE SWITCH
========================= */
.toggle-switch {
    position: relative;
    width: 46px;
    height: 24px;
}

.toggle-switch input {
    display: none;
}

.slider {
    position: absolute;
    inset: 0;
    background: #d1d5db;
    border-radius: 24px;
    transition: 0.3s;
}

.slider::before {
    content: "";
    width: 18px;
    height: 18px;
    background: #fff;
    border-radius: 50%;
    position: absolute;
    top: 3px;
    left: 3px;
    transition: 0.3s;
}

.toggle-switch input:checked + .slider {
    background: #2563eb;
}

.toggle-switch input:checked + .slider::before {
    transform: translateX(22px);
}

/* =========================
    RESPONSIVE FIX
========================= */
@media (max-width: 900px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }

    .row-2 {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="profile-container">

    <!-- HEADER -->
    <div class="profile-header">
        <h2>PROFILE & SETTINGS</h2>
        <p>Manage your account and preferences</p>
    </div>

    <!-- PERSONAL INFORMATION -->
    <div class="card">
        <div class="card-header">
            <div class="icon blue">👤</div>
            <div class="card-title">Personal Information</div>
        </div>

        <form action="save_profile.php" method="POST">
            <div class="profile-grid">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" value="Priyanga Ravi">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" value="adminpeo@gmail.com">
            </div>

            <div class="form-group">
                <label>Department</label>
                <input type="text" value="FSKTM">
            </div>

        </div>

            <div class="card-actions">
                <button class="btn blue">Update Profile</button>
            </div>
        </form>
    </div>

    <!-- ROW 2 -->
    <div class="row-2">

        <!-- NOTIFICATIONS -->
        <div class="card">
            <div class="card-header">
                <div class="icon purple">🔔</div>
                <div class="card-title">Notifications</div>
            </div>

            <form action="save_notifications.php" method="POST">
                <div class="toggle-row">
                    <span>Survey responses</span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="survey_notif"
                            <?= !empty($_SESSION['survey_notif']) ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="toggle-row">
                    <span>System alerts</span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="system_notif"
                            <?= !empty($_SESSION['system_notif']) ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                </div>

                <button class="btn purple" style="width:100%;margin-top:25px;">
                    Update Notifications
                </button>
            </form>
        </div>

        <!-- SYSTEM SETTINGS -->
        <div class="card">
            <div class="card-header">
                <div class="icon green">⚙️</div>
                <div class="card-title">System Settings</div>
            </div>

            <form action="save_system.php" method="POST">
                <div class="toggle-row" style="background:#ecfdf5;">
                    <span>Survey Auto-Assignment</span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="auto_assign"
                            <?= !empty($_SESSION['auto_assign']) ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="form-group">
                    <label>Data Export Format</label>
                    <select name="export_format">
                        <option value="PDF" <?= ($_SESSION['export_format'] ?? '')==='PDF'?'selected':'' ?>>PDF</option>
                        <option value="CSV" <?= ($_SESSION['export_format'] ?? '')==='CSV'?'selected':'' ?>>CSV</option>
                    </select>
                </div>

                <button class="btn green" style="width:100%;margin-top:12px;">
                    Update Settings
                </button>
            </form>
        </div>

    </div>
</div>

<?php include 'layout/footer.php'; ?>
