<?= $this->extend('template') ?>

<?= $this->section('title') ?>
Faculty Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="main-container">
    <?= $this->include('sidebar') ?>

    <div class="main-content">
        <div class="content-header">
            <h1 class="content-title">Faculty Dashboard</h1>
        </div>

        <div class="content-panel">
            <h2>Profile</h2>
            <p><strong>Name:</strong> <?= esc($member['first_name'].' '.$member['middle_name'].' '.$member['last_name']) ?></p>
            <p><strong>Faculty ID:</strong> <?= esc($member['id']) ?></p>
            <p><strong>Membership Type:</strong> <?= esc($membership['package_name'] ?? 'N/A') ?></p>
            <p><strong>Status:</strong> <?= esc((!$hasActiveMembership) ? 'Expired / Inactive' : 'Active') ?></p>
            <p class="muted">Start: <?= esc($membership['start_date'] ?? 'N/A') ?> — End: <?= esc($membership['end_date'] ?? 'N/A') ?></p>

            <div style="display:flex;gap:20px;margin-top:20px;flex-wrap:wrap">
                <div style="flex:1;min-width:220px">
                    <h3>Attendance Summary</h3>
                    <p><strong>Total Visits:</strong> <?= esc($attendance['total']) ?></p>
                    <p><strong>Last Visit:</strong> <?= esc($attendance['last_visit'] ?? 'Never') ?></p>
                    <p><strong>This Month:</strong> <?= esc($attendance['monthly']) ?></p>
                </div>

                <div style="flex:1;min-width:220px">
                    <h3>Membership & Payment</h3>
                    <p><strong>Package:</strong> <?= esc($membership['package_name'] ?? 'N/A') ?></p>
                    <p><strong>Amount Paid:</strong> <?= esc($membership['amount_paid'] ?? 'N/A') ?></p>
                    <p><strong>Payment Status:</strong> <?= esc(ucfirst($membership['payment_status'] ?? 'unpaid')) ?></p>
                    <p><strong>Next Renewal:</strong> <?= esc($membership['end_date'] ?? 'N/A') ?></p>
                </div>
            </div>

            <div style="margin-top:20px">
                <h3>Notifications</h3>
                <?php if (empty($notifications)): ?>
                    <p class="muted">No notifications</p>
                <?php else: ?>
                    <?php foreach ($notifications as $note): ?>
                        <div class="warning"><?= esc($note) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php if (! $hasActiveMembership): ?>
                <div class="card danger" style="margin-top:16px">
                    <strong>Access Restricted:</strong> Your membership is not active. RFID access is disabled until your membership is active and paid.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
