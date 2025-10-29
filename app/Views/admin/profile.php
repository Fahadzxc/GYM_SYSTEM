<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="main-content">
    <?= $this->include('sidebar') ?>
    <div class="content-area">
        <div class="profile-container">
        <div class="profile-content">
        <!-- User Profile Section -->
        <div class="profile-section">
            <div class="profile-info">
                <div class="avatar-placeholder">
                    <span>LOGO</span>
                </div>
                <div class="user-details">
                    <h1 class="user-name">Jul Pacis Patotoya ya</h1>
                    <p class="user-role">Administrator</p>
                    <div class="verified-badge">
                        <span>Verified</span>
                    </div>
                    <button class="edit-profile-btn">Edit Profile</button>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="divider"></div>
        <div class="refresh-icon refresh-icon-center"><span>↻</span></div>

        <!-- Recent Activity Section -->
        <div class="activity-section">
            <div class="activity-header">
                <h2 class="activity-title">Recent Activity</h2>
                <button class="export-btn">Export</button>
            </div>
            
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-content">
                        <span class="activity-text">Successful login</span>
                        <span class="activity-time">2 minutes ago</span>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-content">
                        <span class="activity-text">Added new user</span>
                        <span class="activity-time">5 minutes ago</span>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-content">
                        <span class="activity-text">Generated Reports</span>
                        <span class="activity-time">1 hour ago</span>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>

<?= $this->endSection() ?>
