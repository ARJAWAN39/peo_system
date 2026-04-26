<div class="profile-container">

    <!-- PAGE HEADER -->
    <div class="profile-header">
        <h2>PROFILE & SETTINGS</h2>
        <p>Manage your account and system preferences</p>
    </div>

    <div class="profile-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">

        <!-- LEFT SIDE -->
        <div>

            <!-- PERSONAL INFORMATION -->
            <div class="settings-section">
                <h4 class="section-title">PERSONAL INFORMATION</h4>

                <form class="settings-form" method="post">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>">
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
                    </div>

                    <div class="form-group">
                        <label>Department</label>
                        <input type="text" name="department" value="<?= htmlspecialchars($user['department']) ?>">
                    </div>

                    <button class="save-btn" type="submit" name="update_profile">UPDATE PROFILE</button>
                </form>
            </div>

            <!-- SECURITY -->
            <div class="settings-section">
                <h4 class="section-title">SECURITY</h4>

                <form class="settings-form" method="post">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password">
                    </div>

                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password">
                    </div>

                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password">
                    </div>

                    <button class="save-btn" type="submit" name="change_password">CHANGE PASSWORD</button>
                </form>
            </div>

            <!-- SYSTEM SETTINGS -->
            <div class="settings-section">
                <h4 class="section-title">SYSTEM SETTINGS</h4>

                <div class="toggle-group">
                    <span>Survey Auto-Assignment</span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="auto_assign" <?= $user['auto_assign'] ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-group">
                    <span>Email Reminders</span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="email_reminders" <?= $user['email_reminders'] ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="form-group">
                    <label>Data Export Format</label>
                    <select name="export_format">
                        <option>PDF</option>
                        <option>Excel</option>
                    </select>
                </div>
            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div>

            <!-- PROFILE PICTURE -->
            <div class="profile-pic-box">
                <h4 class="section-title">PROFILE PICTURE</h4>
                <img src="assets/img/profile.png" alt="profile picture">
                <button class="save-btn" type="button">UPLOAD PHOTO</button>
                <button class="save-btn" type="button">REMOVE</button>
            </div>

            <!-- NOTIFICATIONS -->
            <div class="right-box">
                <h4 class="section-title">NOTIFICATIONS</h4>
                <label><input type="checkbox" checked> Survey responses</label><br>
                <label><input type="checkbox" checked> System alerts</label><br>
                <label><input type="checkbox" checked> Weekly summaries</label><br>
                <label><input type="checkbox" checked> Low response rates</label>
            </div>

            <!-- LANGUAGE & TIMEZONE -->
            <div class="right-box">
                <h4 class="section-title">LANGUAGE</h4>
                <select class="settings-select">
                    <option>English</option>
                </select>

                <h4 class="section-title" style="margin-top:20px;">TIMEZONE</h4>
                <select class="settings-select">
                    <option>GMT +8 Kuala Lumpur</option>
                </select>
            </div>

        </div>

    </div>

</div>
