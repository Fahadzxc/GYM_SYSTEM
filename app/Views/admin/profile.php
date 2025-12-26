<?= $this->extend('template') ?>

<?= $this->section('title') ?>
Profile
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="main-container">
    <?= $this->include('sidebar') ?>

    <div class="main-content">
        <div class="content-header">
            <h1 class="content-title">Profile</h1>
        </div>

        <div class="content-panel">
            <div class="profile-wrapper">
                <div class="profile-content">
                    <!-- User Profile Section -->
                    <div class="profile-section">
                        <div class="profile-info">
                            <div class="avatar-container">
                                <div class="avatar-wrapper">
                                    <?php if (!empty($profile_picture)): ?>
                                        <?php 
                                        $picturePath = FCPATH . 'uploads/profiles/' . $profile_picture;
                                        $pictureUrl = file_exists($picturePath) ? base_url('uploads/profiles/' . $profile_picture) : null;
                                        ?>
                                        <?php if ($pictureUrl): ?>
                                            <img src="<?= $pictureUrl ?>" alt="Profile Picture" class="profile-avatar" id="profileAvatar">
                                        <?php else: ?>
                                            <div class="avatar-placeholder" id="profileAvatarPlaceholder">
                                                <span><?= strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? '', 0, 1)) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="avatar-placeholder" id="profileAvatarPlaceholder">
                                            <span><?= strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? '', 0, 1)) ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="avatar-overlay" onclick="document.getElementById('profilePictureInput').click()">
                                        <span class="upload-icon">📷</span>
                                        <span class="upload-text">Change Photo</span>
                                    </div>
                                    <input type="file" id="profilePictureInput" accept="image/*" style="display: none;" onchange="uploadProfilePicture(this)">
                                </div>
                            </div>
                            <div class="user-details">
                                <h1 class="user-name"><?= esc($full_name) ?></h1>
                                <p class="user-role"><?= esc($role_display) ?></p>
                                <?php if ($is_verified): ?>
                                    <div class="verified-badge">
                                        <span>✓ Verified</span>
                                    </div>
                                <?php endif; ?>
                                <div class="profile-actions">
                                    <button class="edit-profile-btn" onclick="openEditProfileModal()">Edit Profile</button>
                                    <button class="change-password-btn" onclick="openChangePasswordModal()">Change Password</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information Section -->
                    <div class="info-section">
                        <h2 class="section-title">Account Information</h2>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Email</label>
                                <div class="info-value"><?= esc($user['email'] ?? 'N/A') ?></div>
                            </div>
                            <div class="info-item">
                                <label>Phone Number</label>
                                <div class="info-value"><?= esc($user['phone_no'] ?? 'N/A') ?></div>
                            </div>
                            <div class="info-item">
                                <label>Account Status</label>
                                <div class="info-value">
                                    <span class="status-badge status-<?= esc($user['status'] ?? 'active') ?>">
                                        <?= esc(ucfirst($user['status'] ?? 'active')) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="info-item">
                                <label>Last Login</label>
                                <div class="info-value"><?= $user['last_login'] ? date('M d, Y h:i A', strtotime($user['last_login'])) : 'Never' ?></div>
                            </div>
                            <div class="info-item">
                                <label>Member Since</label>
                                <div class="info-value"><?= $user['created_at'] ? date('M d, Y', strtotime($user['created_at'])) : 'N/A' ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="divider"></div>
                    <div class="refresh-icon refresh-icon-center" onclick="refreshActivities()">
                        <span>↻</span>
                    </div>

                    <!-- Recent Activity Section -->
                    <div class="activity-section">
                        <div class="activity-header">
                            <h2 class="activity-title">Recent Activity</h2>
                            <button class="export-btn" onclick="exportActivities()">Export</button>
                        </div>
                        
                        <div class="activity-list" id="activityList">
                            <?php if (empty($activities)): ?>
                                <div class="no-activities">No recent activities</div>
                            <?php else: ?>
                                <?php foreach ($activities as $activity): ?>
                                    <div class="activity-item">
                                        <div class="activity-content">
                                            <span class="activity-text"><?= esc($activity['description']) ?></span>
                                            <span class="activity-time"><?= esc($activity['time']) ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Profile</h2>
            <span class="close" onclick="closeEditProfileModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="editProfileForm">
                <?= csrf_field() ?>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_first_name">First Name *</label>
                        <input type="text" id="edit_first_name" name="first_name" value="<?= esc($user['first_name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_middle_name">Middle Name</label>
                        <input type="text" id="edit_middle_name" name="middle_name" value="<?= esc($user['middle_name'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_last_name">Last Name *</label>
                        <input type="text" id="edit_last_name" name="last_name" value="<?= esc($user['last_name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email *</label>
                        <input type="email" id="edit_email" name="email" value="<?= esc($user['email'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_phone_no">Phone Number</label>
                        <input type="text" id="edit_phone_no" name="phone_no" value="<?= esc($user['phone_no'] ?? '') ?>">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeEditProfileModal()">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="saveProfile()">Save Changes</button>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Change Password</h2>
            <span class="close" onclick="closeChangePasswordModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="changePasswordForm">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="current_password">Current Password *</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password *</label>
                    <input type="password" id="new_password" name="new_password" required minlength="6">
                    <small class="form-help">Password must be at least 6 characters</small>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeChangePasswordModal()">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="savePassword()">Change Password</button>
        </div>
    </div>
</div>

<style>
.profile-wrapper {
    padding: 20px 0;
}

.profile-content {
    max-width: 1200px;
    margin: 0 auto;
}

.profile-section {
    margin-bottom: 30px;
}

.profile-info {
    display: flex;
    align-items: flex-start;
    gap: 30px;
}

.avatar-container {
    position: relative;
}

.avatar-wrapper {
    position: relative;
    width: 160px;
    height: 160px;
    border-radius: 50%;
    overflow: hidden;
    cursor: pointer;
    border: 4px solid #0A2E73;
}

.profile-avatar {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    font-weight: 700;
    color: white;
}

.avatar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
    color: white;
}

.avatar-wrapper:hover .avatar-overlay {
    opacity: 1;
}

.upload-icon {
    font-size: 32px;
    margin-bottom: 5px;
}

.upload-text {
    font-size: 14px;
    font-weight: 600;
}

.user-details {
    flex: 1;
}

.user-name {
    font-size: 36px;
    font-weight: 700;
    color: #0A2E73;
    margin: 0 0 10px 0;
}

.user-role {
    font-size: 18px;
    color: #666;
    margin: 0 0 15px 0;
}

.verified-badge {
    display: inline-block;
    background-color: #28a745;
    color: white;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 20px;
}

.profile-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.edit-profile-btn, .change-password-btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.edit-profile-btn {
    background-color: #E0E0E0;
    color: #000;
}

.edit-profile-btn:hover {
    background-color: #D0D0D0;
}

.change-password-btn {
    background-color: #667eea;
    color: white;
}

.change-password-btn:hover {
    background-color: #5a67d8;
}

.info-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    color: #2d3748;
    margin: 0 0 20px 0;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
}

.info-item label {
    font-size: 12px;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
    font-weight: 600;
}

.info-value {
    font-size: 16px;
    color: #2d3748;
    font-weight: 500;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.status-active {
    background-color: #c6f6d5;
    color: #22543d;
}

.status-inactive {
    background-color: #fed7d7;
    color: #742a2a;
}

.divider {
    height: 1px;
    background-color: #E0E0E0;
    margin: 30px 0;
}

.refresh-icon-center {
    width: 32px;
    height: 32px;
    background-color: #E0E0E0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    margin: -16px auto 20px auto;
    cursor: pointer;
    transition: all 0.3s;
}

.refresh-icon-center:hover {
    background-color: #667eea;
    color: white;
    transform: rotate(180deg);
}

.activity-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.activity-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.activity-title {
    font-size: 20px;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.export-btn {
    background: white;
    color: #666;
    border: 1px solid #E0E0E0;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s;
}

.export-btn:hover {
    background: #f7fafc;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.activity-item {
    border-bottom: 1px solid #F0F0F0;
    padding: 15px 0;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.activity-text {
    font-size: 14px;
    color: #333;
    font-weight: 600;
}

.activity-time {
    font-size: 12px;
    color: #666;
    font-weight: 500;
}

.no-activities {
    text-align: center;
    padding: 40px;
    color: #718096;
}

.modal {
    display: none;
    position: fixed;
    z-index: 2000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: #FFF2DC;
    border-radius: 15px;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    position: relative;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 25px 30px 20px 30px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    color: #2c3e50;
    font-size: 24px;
    font-weight: 700;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    line-height: 1;
}

.close:hover {
    color: #000;
}

.modal-body {
    padding: 30px;
}

.form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    flex: 1;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #2c3e50;
    font-weight: 600;
    font-size: 14px;
}

.form-group input {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #0A2E73;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
    background-color: white;
}

.form-group input:focus {
    outline: none;
    border-color: #0A2E73;
    box-shadow: 0 0 0 3px rgba(10, 46, 115, 0.2);
}

.form-help {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    color: #718096;
}

.modal-footer {
    padding: 20px 30px 30px 30px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    border-top: 1px solid #e9ecef;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #667eea;
    color: white;
}

.btn-primary:hover {
    background-color: #5a67d8;
}

.btn-secondary {
    background-color: #718096;
    color: white;
}

.btn-secondary:hover {
    background-color: #4a5568;
}
</style>

<script>
function openEditProfileModal() {
    document.getElementById('editProfileModal').style.display = 'flex';
}

function closeEditProfileModal() {
    document.getElementById('editProfileModal').style.display = 'none';
}

function openChangePasswordModal() {
    document.getElementById('changePasswordModal').style.display = 'flex';
    document.getElementById('changePasswordForm').reset();
}

function closeChangePasswordModal() {
    document.getElementById('changePasswordModal').style.display = 'none';
}

function saveProfile() {
    const form = document.getElementById('editProfileForm');
    const formData = new FormData(form);
    
    fetch('<?= base_url('/profile/update') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Profile updated successfully!');
            location.reload();
        } else {
            alert(data.message || 'Failed to update profile');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating profile');
    });
}

function savePassword() {
    const form = document.getElementById('changePasswordForm');
    const formData = new FormData(form);
    
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        alert('New password and confirm password do not match');
        return;
    }
    
    fetch('<?= base_url('/profile/change-password') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Password changed successfully!');
            closeChangePasswordModal();
            form.reset();
        } else {
            alert(data.message || 'Failed to change password');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while changing password');
    });
}

function uploadProfilePicture(input) {
    if (!input.files || !input.files[0]) {
        return;
    }
    
    const formData = new FormData();
    formData.append('profile_picture', input.files[0]);
    
    fetch('<?= base_url('/profile/upload-picture') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update avatar display
            if (data.picture_url) {
                const avatar = document.getElementById('profileAvatar');
                const placeholder = document.getElementById('profileAvatarPlaceholder');
                if (avatar) {
                    avatar.src = data.picture_url + '?t=' + new Date().getTime();
                } else if (placeholder) {
                    placeholder.innerHTML = '<img src="' + data.picture_url + '?t=' + new Date().getTime() + '" style="width:100%;height:100%;object-fit:cover;">';
                }
            }
            alert('Profile picture uploaded successfully!');
        } else {
            alert(data.message || 'Failed to upload profile picture');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while uploading profile picture');
    });
}

function refreshActivities() {
    fetch('<?= base_url('/profile/activities') ?>')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const activityList = document.getElementById('activityList');
            if (data.data.length === 0) {
                activityList.innerHTML = '<div class="no-activities">No recent activities</div>';
            } else {
                activityList.innerHTML = data.data.map(activity => `
                    <div class="activity-item">
                        <div class="activity-content">
                            <span class="activity-text">${activity.description}</span>
                            <span class="activity-time">${activity.time}</span>
                        </div>
                    </div>
                `).join('');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function exportActivities() {
    // Export activities to CSV
    fetch('<?= base_url('/profile/activities') ?>?limit=1000')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let csv = 'Activity,Time\n';
            data.data.forEach(activity => {
                csv += `"${activity.description}","${activity.time}"\n`;
            });
            
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('hidden', '');
            a.setAttribute('href', url);
            a.setAttribute('download', 'activity_log_' + new Date().toISOString().split('T')[0] + '.csv');
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    });
}

// Close modals when clicking outside
window.onclick = function(event) {
    const editModal = document.getElementById('editProfileModal');
    const passwordModal = document.getElementById('changePasswordModal');
    if (event.target === editModal) {
        closeEditProfileModal();
    }
    if (event.target === passwordModal) {
        closeChangePasswordModal();
    }
}
</script>

<?= $this->endSection() ?>
