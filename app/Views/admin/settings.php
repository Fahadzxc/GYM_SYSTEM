<?= $this->extend('template') ?>

<?= $this->section('title') ?>
Settings
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="main-container">
    <?= $this->include('sidebar') ?>

    <div class="main-content">
        <div class="content-header">
            <h1 class="content-title">Settings</h1>
        </div>

        <div class="content-panel">
            <div class="settings-wrapper">
                <!-- System Settings Section -->
                <div class="settings-section">
                    <div class="settings-section-header">
                        <h2 class="settings-section-title">System Settings</h2>
                        <p class="settings-section-description">Configure system-wide settings and preferences</p>
                    </div>

                    <div class="settings-card">
                        <div class="settings-item">
                            <div class="settings-item-label">
                                <label>Gym Name</label>
                                <span class="settings-item-hint">The name of your gym facility</span>
                            </div>
                            <div class="settings-item-control">
                                <input type="text" class="form-control" id="gymName" value="Gym Management System" placeholder="Enter gym name">
                            </div>
                        </div>

                        <div class="settings-item">
                            <div class="settings-item-label">
                                <label>System Email</label>
                                <span class="settings-item-hint">Email address for system notifications</span>
                            </div>
                            <div class="settings-item-control">
                                <input type="email" class="form-control" id="systemEmail" value="<?= esc($user['email'] ?? 'admin@gym.com') ?>" placeholder="Enter system email">
                            </div>
                        </div>

                        <div class="settings-item">
                            <div class="settings-item-label">
                                <label>Timezone</label>
                                <span class="settings-item-hint">Default timezone for the system</span>
                            </div>
                            <div class="settings-item-control">
                                <select class="form-control" id="timezone">
                                    <option value="Asia/Manila" selected>Asia/Manila (GMT+8)</option>
                                    <option value="UTC">UTC (GMT+0)</option>
                                    <option value="America/New_York">America/New_York (GMT-5)</option>
                                    <option value="Europe/London">Europe/London (GMT+0)</option>
                                </select>
                            </div>
                        </div>

                        <div class="settings-item">
                            <div class="settings-item-label">
                                <label>Date Format</label>
                                <span class="settings-item-hint">How dates are displayed throughout the system</span>
                            </div>
                            <div class="settings-item-control">
                                <select class="form-control" id="dateFormat">
                                    <option value="Y-m-d" selected>YYYY-MM-DD</option>
                                    <option value="m/d/Y">MM/DD/YYYY</option>
                                    <option value="d/m/Y">DD/MM/YYYY</option>
                                    <option value="M d, Y">Dec 26, 2025</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings Section -->
                <div class="settings-section">
                    <div class="settings-section-header">
                        <h2 class="settings-section-title">Notification Settings</h2>
                        <p class="settings-section-description">Manage how you receive notifications</p>
                    </div>

                    <div class="settings-card">
                        <div class="settings-item">
                            <div class="settings-item-label">
                                <label>Email Notifications</label>
                                <span class="settings-item-hint">Receive email notifications for important events</span>
                            </div>
                            <div class="settings-item-control">
                                <label class="switch">
                                    <input type="checkbox" id="emailNotifications" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="settings-item">
                            <div class="settings-item-label">
                                <label>New Member Alerts</label>
                                <span class="settings-item-hint">Get notified when new members register</span>
                            </div>
                            <div class="settings-item-control">
                                <label class="switch">
                                    <input type="checkbox" id="newMemberAlerts" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="settings-item">
                            <div class="settings-item-label">
                                <label>Payment Reminders</label>
                                <span class="settings-item-hint">Receive reminders for pending payments</span>
                            </div>
                            <div class="settings-item-control">
                                <label class="switch">
                                    <input type="checkbox" id="paymentReminders" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Settings Section -->
                <div class="settings-section">
                    <div class="settings-section-header">
                        <h2 class="settings-section-title">Security Settings</h2>
                        <p class="settings-section-description">Manage security and privacy settings</p>
                    </div>

                    <div class="settings-card">
                        <div class="settings-item">
                            <div class="settings-item-label">
                                <label>Session Timeout</label>
                                <span class="settings-item-hint">Automatic logout after inactivity (minutes)</span>
                            </div>
                            <div class="settings-item-control">
                                <input type="number" class="form-control" id="sessionTimeout" value="30" min="5" max="120">
                            </div>
                        </div>

                        <div class="settings-item">
                            <div class="settings-item-label">
                                <label>Require Password Change</label>
                                <span class="settings-item-hint">Force password change every 90 days</span>
                            </div>
                            <div class="settings-item-control">
                                <label class="switch">
                                    <input type="checkbox" id="requirePasswordChange">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="settings-item">
                            <div class="settings-item-label">
                                <label>Two-Factor Authentication</label>
                                <span class="settings-item-hint">Add an extra layer of security to your account</span>
                            </div>
                            <div class="settings-item-control">
                                <label class="switch">
                                    <input type="checkbox" id="twoFactorAuth">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="settings-actions">
                    <button type="button" class="btn btn-primary" id="saveSettingsBtn">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                    <button type="button" class="btn btn-secondary" id="resetSettingsBtn">
                        <i class="fas fa-undo"></i> Reset to Default
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.settings-wrapper {
    padding: 20px 0;
}

.settings-section {
    margin-bottom: 40px;
}

.settings-section-header {
    margin-bottom: 20px;
}

.settings-section-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.settings-section-description {
    color: #666;
    font-size: 14px;
    margin: 0;
}

.settings-card {
    background: #fff;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.settings-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 20px 0;
    border-bottom: 1px solid #eee;
}

.settings-item:last-child {
    border-bottom: none;
}

.settings-item-label {
    flex: 1;
    margin-right: 20px;
}

.settings-item-label label {
    display: block;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 4px;
    font-size: 15px;
}

.settings-item-hint {
    display: block;
    color: #666;
    font-size: 13px;
    margin-top: 4px;
}

.settings-item-control {
    flex: 0 0 300px;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #0056b3;
}

/* Toggle Switch */
.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #0056b3;
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.settings-actions {
    display: flex;
    gap: 12px;
    margin-top: 30px;
    padding-top: 30px;
    border-top: 1px solid #eee;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background-color: #0056b3;
    color: white;
}

.btn-primary:hover {
    background-color: #004494;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

@media (max-width: 768px) {
    .settings-item {
        flex-direction: column;
    }

    .settings-item-control {
        flex: 1;
        width: 100%;
        margin-top: 12px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const saveBtn = document.getElementById('saveSettingsBtn');
    const resetBtn = document.getElementById('resetSettingsBtn');

    saveBtn.addEventListener('click', function() {
        const settings = {
            gymName: document.getElementById('gymName').value,
            systemEmail: document.getElementById('systemEmail').value,
            timezone: document.getElementById('timezone').value,
            dateFormat: document.getElementById('dateFormat').value,
            emailNotifications: document.getElementById('emailNotifications').checked,
            newMemberAlerts: document.getElementById('newMemberAlerts').checked,
            paymentReminders: document.getElementById('paymentReminders').checked,
            sessionTimeout: document.getElementById('sessionTimeout').value,
            requirePasswordChange: document.getElementById('requirePasswordChange').checked,
            twoFactorAuth: document.getElementById('twoFactorAuth').checked
        };

        // Save to localStorage for now (can be changed to save to database)
        localStorage.setItem('gymSettings', JSON.stringify(settings));

        alert('Settings saved successfully!');
    });

    resetBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to reset all settings to default?')) {
            // Reset form values
            document.getElementById('gymName').value = 'Gym Management System';
            document.getElementById('systemEmail').value = '<?= esc($user['email'] ?? 'admin@gym.com') ?>';
            document.getElementById('timezone').value = 'Asia/Manila';
            document.getElementById('dateFormat').value = 'Y-m-d';
            document.getElementById('emailNotifications').checked = true;
            document.getElementById('newMemberAlerts').checked = true;
            document.getElementById('paymentReminders').checked = true;
            document.getElementById('sessionTimeout').value = 30;
            document.getElementById('requirePasswordChange').checked = false;
            document.getElementById('twoFactorAuth').checked = false;

            localStorage.removeItem('gymSettings');
            alert('Settings reset to default!');
        }
    });

    // Load saved settings
    const savedSettings = localStorage.getItem('gymSettings');
    if (savedSettings) {
        try {
            const settings = JSON.parse(savedSettings);
            if (settings.gymName) document.getElementById('gymName').value = settings.gymName;
            if (settings.systemEmail) document.getElementById('systemEmail').value = settings.systemEmail;
            if (settings.timezone) document.getElementById('timezone').value = settings.timezone;
            if (settings.dateFormat) document.getElementById('dateFormat').value = settings.dateFormat;
            if (settings.emailNotifications !== undefined) document.getElementById('emailNotifications').checked = settings.emailNotifications;
            if (settings.newMemberAlerts !== undefined) document.getElementById('newMemberAlerts').checked = settings.newMemberAlerts;
            if (settings.paymentReminders !== undefined) document.getElementById('paymentReminders').checked = settings.paymentReminders;
            if (settings.sessionTimeout) document.getElementById('sessionTimeout').value = settings.sessionTimeout;
            if (settings.requirePasswordChange !== undefined) document.getElementById('requirePasswordChange').checked = settings.requirePasswordChange;
            if (settings.twoFactorAuth !== undefined) document.getElementById('twoFactorAuth').checked = settings.twoFactorAuth;
        } catch (e) {
            console.error('Error loading settings:', e);
        }
    }
});
</script>

<?= $this->endSection() ?>

