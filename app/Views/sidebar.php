<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo-placeholder">
            <span>LOGO</span>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">
                <a href="<?= base_url('/dashboard') ?>" class="nav-link">
                    Dashboard
                </a>
            </li>
            <li class="nav-item <?= (uri_string() == 'manage-users') ? 'active' : '' ?>">
                <a href="<?= base_url('/manage-users') ?>" class="nav-link">
                    Manage Users
                </a>
            </li>
            <li class="nav-item <?= (uri_string() == 'profile') ? 'active' : '' ?>">
                <a href="<?= base_url('/profile') ?>" class="nav-link">
                    Profile
                </a>
            </li>
            <li class="nav-item <?= (uri_string() == 'reports') ? 'active' : '' ?>">
                <a href="<?= base_url('/reports') ?>" class="nav-link">
                    Generate Reports
                </a>
            </li>
            <li class="nav-item <?= (uri_string() == 'settings') ? 'active' : '' ?>">
                <a href="<?= base_url('/settings') ?>" class="nav-link">
                    Settings
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <a href="<?= base_url('/logout') ?>" class="logout-btn">
            Logout
        </a>
    </div>
</div>
