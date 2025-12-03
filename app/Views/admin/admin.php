<div class="main-container">
    <?= $this->include('sidebar') ?>
    
    <div class="main-content">
        <div class="content-header">
            <h1 class="content-title">DASHBOARD</h1>
            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $total_today ?? 0 ?></div>
                        <div class="stat-label">Total Scans Today</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $unique_members ?? 0 ?></div>
                        <div class="stat-label">Unique Members</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📅</div>
                    <div class="stat-info">
                        <div class="stat-value"><?= date('M d, Y') ?></div>
                        <div class="stat-label">Today's Date</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="content-panel">
            <div class="panel-header">
                <h2>Today's Attendance</h2>
                <button onclick="refreshAttendance()" class="btn-refresh">🔄 Refresh</button>
            </div>
            
            <?php if (empty($attendance)): ?>
                <div class="no-data">
                    <div class="no-data-icon">📋</div>
                    <p>No attendance records for today yet.</p>
                    <small>Records will appear here when members scan their RFID cards.</small>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>User Type</th>
                                <th>Time In</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendance as $record): ?>
                                <tr>
                                    <td><?= esc($record['member_id']) ?></td>
                                    <td>
                                        <?= esc($record['first_name']) ?> 
                                        <?= !empty($record['middle_name']) ? esc($record['middle_name'][0]) . '.' : '' ?> 
                                        <?= esc($record['last_name']) ?>
                                    </td>
                                    <td>
                                        <span class="user-type-badge <?= strtolower($record['user_type'] ?? 'member') ?>">
                                            <?= esc($record['user_type'] ?? 'Member') ?>
                                        </span>
                                    </td>
                                    <td><?= date('h:i A', strtotime($record['scan_time'])) ?></td>
                                    <td>
                                        <span class="status-badge status-inside">Inside</span>
                                    </td>
                                    <td>
                                        <span class="status-dot dot-green" title="Active"></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 15px;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.stat-icon {
    font-size: 40px;
    opacity: 0.8;
}

.stat-info {
    flex: 1;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.stat-label {
    font-size: 13px;
    color: #718096;
    margin-top: 5px;
}

.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e2e8f0;
}

.panel-header h2 {
    margin: 0;
    color: #2d3748;
    font-size: 20px;
}

.btn-refresh {
    padding: 8px 16px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s;
}

.btn-refresh:hover {
    background: #5a67d8;
}

.table-wrapper {
    overflow-x: auto;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.data-table thead {
    background: #f7fafc;
}

.data-table th {
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    color: #2d3748;
    border-bottom: 2px solid #e2e8f0;
    font-size: 14px;
}

.data-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e2e8f0;
    color: #4a5568;
    font-size: 14px;
}

.data-table tbody tr:hover {
    background: #f7fafc;
}

.user-type-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-transform: capitalize;
}

.user-type-badge.athlete {
    background: #e6fffa;
    color: #047857;
}

.user-type-badge.staff {
    background: #fef3c7;
    color: #92400e;
}

.user-type-badge.faculty {
    background: #dbeafe;
    color: #1e40af;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.status-inside {
    background: #d1fae5;
    color: #065f46;
}

.status-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.dot-green {
    background: #10b981;
}

.no-data {
    text-align: center;
    padding: 60px 20px;
    color: #718096;
}

.no-data-icon {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.no-data p {
    font-size: 18px;
    margin: 10px 0;
    color: #4a5568;
}

.no-data small {
    font-size: 14px;
    color: #a0aec0;
}

@media (max-width: 768px) {
    .dashboard-stats {
        grid-template-columns: 1fr;
    }
    
    .panel-header {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    
    .btn-refresh {
        width: 100%;
    }
}
</style>

<script>
function refreshAttendance() {
    location.reload();
}

// Auto-refresh every 30 seconds
setInterval(function() {
    location.reload();
}, 30000);
</script>
