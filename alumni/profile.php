<?php
require_once "../alumni_check.php";
require_once "../config.php";

$email = $_SESSION['email'];

/* ============================================
   FETCH ALUMNI PROFILE (READ ONLY)
============================================ */
$stmt = $pdo->prepare("
    SELECT *
    FROM alumni_students
    WHERE student_email = ?
    LIMIT 1
");
$stmt->execute([$email]);
$alumni = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$alumni) {
    die("Alumni record not found.");
}
?>

<?php include "../layout/alumni/header.php"; ?>
<?php include "../layout/alumni/sidebar.php"; ?>

<div class="alumni-dashboard">

    <!-- PAGE HEADER -->
    <div class="profile-card profile-header">
        <h2>My Profile</h2>
        <p>Manage your personal information and preferences</p>
    </div>

    <div class="profile-container">

        <!-- LEFT COLUMN -->
        <div class="profile-left">

            <!-- PROFILE PICTURE -->
            <form method="POST" action="upload_photo.php" enctype="multipart/form-data">

                <div class="avatar-box">
                    <?php if (!empty($alumni['profile_photo'])): ?>
                        <img src="../uploads/profile/<?= $alumni['profile_photo'] ?>" class="avatar-img">
                    <?php else: ?>
                        <span class="avatar-icon">👤</span>
                    <?php endif; ?>
                </div>

                <!-- HIDDEN INPUT -->
                <input type="file" id="photoInput" name="photo" accept="image/*">

                <!-- CUSTOM BUTTON -->
                <div class="upload-actions">
                    <label for="photoInput" class="btn-select">Choose Photo</label>
                    <span id="fileName">No file chosen</span>
                </div>

                <button type="submit" class="btn-upload">Upload Photo</button>

            </form>

            <!-- QUICK CONTACT -->
            <div class="profile-card personal-accent">
                <h4>Quick Contact</h4>

                <div class="quick-item">
                    <span>📧</span>
                    <div>
                        <small>Email</small>
                        <p><?= htmlspecialchars($alumni['student_email']) ?></p>
                    </div>
                </div>

                <div class="quick-item">
                    <span>🎓</span>
                    <div>
                        <small>Programme</small>
                        <p><?= htmlspecialchars($alumni['programme']) ?></p>
                    </div>
                </div>

                <div class="quick-item">
                    <span>📅</span>
                    <div>
                        <small>Batch Year</small>
                        <p><?= htmlspecialchars($alumni['batch_year']) ?></p>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="profile-right">

            <!-- PERSONAL INFORMATION -->
            <div class="profile-card personal-accent">
                <h4>Personal Information</h4>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" value="<?= htmlspecialchars($alumni['student_name']) ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" value="<?= htmlspecialchars($alumni['student_email']) ?>" readonly>
                    </div>
                </div>
            </div>

            <!-- ACADEMIC INFORMATION -->
            <div class="profile-card academic-card">
                <h4>Academic Information</h4>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Programme</label>
                        <input type="text" value="<?= htmlspecialchars($alumni['programme']) ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Batch Year</label>
                        <input type="text" value="<?= htmlspecialchars($alumni['batch_year']) ?>" readonly>
                    </div>
                </div>
            </div>

            <!-- CURRENT EMPLOYMENT -->
            <form method="POST" action="save_employment.php">

    <div class="profile-card employment-card">
        <h4>
            <span class="employment-icon">💼</span>
            Current Employment
        </h4>

        <div class="form-grid">
            <div class="form-group">
                <label>Company Name</label>
                <input type="text" name="company_name"
                    value="<?= htmlspecialchars($alumni['company_name'] ?? '') ?>">
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Job Title</label>
                <input type="text" name="job_title"
                    value="<?= htmlspecialchars($alumni['job_title'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Employment Status</label>
                <select name="employment_status">
                    <option value="Full-time" <?= ($alumni['employment_status'] ?? '')=='Full-time'?'selected':'' ?>>Full-time</option>
                    <option value="Part-time" <?= ($alumni['employment_status'] ?? '')=='Part-time'?'selected':'' ?>>Part-time</option>
                    <option value="Contract" <?= ($alumni['employment_status'] ?? '')=='Contract'?'selected':'' ?>>Contract</option>
                    <option value="Self-employed" <?= ($alumni['employment_status'] ?? '')=='Self-employed'?'selected':'' ?>>Self-employed</option>
                </select>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Industry</label>
                <input type="text" name="industry"
                    value="<?= htmlspecialchars($alumni['industry'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Years in Current Role</label>
                <input type="number" name="years_experience"
                    value="<?= htmlspecialchars($alumni['years_experience'] ?? '') ?>">
            </div>
        </div>

        <button type="submit" class="btn-employment">
            Update Employment
        </button>
    </div>

    </form>

    </div>
</div>

<?php include "../layout/alumni/footer.php"; ?>

<!-- ================================
     PROFILE PHOTO DEMO SCRIPT
     (NO DATABASE / NO UPLOAD)
================================ -->
<script>
document.getElementById("uploadBtn").addEventListener("click", function () {
    document.getElementById("avatarInput").click();
});

document.getElementById("avatarInput").addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (!file) return;

    if (!file.type.startsWith("image/")) {
        alert("Please select an image file");
        return;
    }

    const reader = new FileReader();
    reader.onload = function (event) {
        const img = document.getElementById("avatarPreview");
        const icon = document.getElementById("avatarIcon");

        img.src = event.target.result;
        img.style.display = "block";
        icon.style.display = "none";
    };
    reader.readAsDataURL(file);
});
</script>
