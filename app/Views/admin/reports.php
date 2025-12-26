<?= $this->extend('template') ?>

<?= $this->section('title') ?>
Generate Reports
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="main-container">
    <?= $this->include('sidebar') ?>

    <div class="main-content">
        <div class="content-header">
            <h1 class="content-title">Generate Reports</h1>
        </div>

        <div class="content-panel">
            <p class="muted">Generate and download reports for new users and attendance</p>

            <div class="reports-container">
        <!-- New User Report Card -->
        <div class="report-card">
            <div class="report-card-header">
                <h2>📋 New User Report</h2>
                <p>Generate a report of newly registered users within a date range</p>
            </div>
            <div class="report-card-body">
                <form id="newUserReportForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="new_user_start_date">Start Date</label>
                            <input type="date" id="new_user_start_date" name="start_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="new_user_end_date">End Date</label>
                            <input type="date" id="new_user_end_date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <span class="btn-icon">📊</span> Generate Report
                    </button>
                </form>
                
                <div id="newUserReportResult" class="report-result" style="display: none;">
                    <div class="report-summary">
                        <h3>Report Summary</h3>
                        <p><strong>Total New Users:</strong> <span id="newUserCount">0</span></p>
                        <p><strong>Date Range:</strong> <span id="newUserDateRange">-</span></p>
                    </div>
                    <div class="report-actions">
                        <button onclick="downloadNewUserReport()" class="btn btn-success">
                            <span class="btn-icon">⬇️</span> Download CSV
                        </button>
                        <button onclick="printNewUserReport()" class="btn btn-secondary">
                            <span class="btn-icon">🖨️</span> Print
                        </button>
                    </div>
                    <div class="report-table-wrapper">
                        <table id="newUserTable" class="report-table">
                            <thead>
                                <tr>
                                    <th>Member ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>User Type</th>
                                    <th>Registration Date</th>
                                </tr>
                            </thead>
                            <tbody id="newUserTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Report Card -->
        <div class="report-card">
            <div class="report-card-header">
                <h2>📅 Attendance Report</h2>
                <p>Generate a report of gym attendance within a date range</p>
            </div>
            <div class="report-card-body">
                <form id="attendanceReportForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="attendance_start_date">Start Date</label>
                            <input type="date" id="attendance_start_date" name="start_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="attendance_end_date">End Date</label>
                            <input type="date" id="attendance_end_date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <span class="btn-icon">📊</span> Generate Report
                    </button>
                </form>
                
                <div id="attendanceReportResult" class="report-result" style="display: none;">
                    <div class="report-summary">
                        <h3>Report Summary</h3>
                        <p><strong>Total Scans:</strong> <span id="totalScans">0</span></p>
                        <p><strong>Unique Members:</strong> <span id="uniqueMembers">0</span></p>
                        <p><strong>Date Range:</strong> <span id="attendanceDateRange">-</span></p>
                    </div>
                    <div class="report-actions">
                        <button onclick="downloadAttendanceReport()" class="btn btn-success">
                            <span class="btn-icon">⬇️</span> Download CSV
                        </button>
                        <button onclick="printAttendanceReport()" class="btn btn-secondary">
                            <span class="btn-icon">🖨️</span> Print
                        </button>
                    </div>
                    <div class="report-table-wrapper">
                        <table id="attendanceTable" class="report-table">
                            <thead>
                                <tr>
                                    <th>Member ID</th>
                                    <th>Name</th>
                                    <th>User Type</th>
                                    <th>Scan Time</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="attendanceTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>
</div>

<style>
.reports-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 30px;
    margin-top: 30px;
}

.report-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.report-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px;
}

.report-card-header h2 {
    margin: 0 0 10px 0;
    font-size: 24px;
}

.report-card-header p {
    margin: 0;
    opacity: 0.9;
    font-size: 14px;
}

.report-card-body {
    padding: 25px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.form-control {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a67d8;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-success {
    background: #48bb78;
    color: white;
}

.btn-success:hover {
    background: #38a169;
}

.btn-secondary {
    background: #718096;
    color: white;
}

.btn-secondary:hover {
    background: #4a5568;
}

.btn-icon {
    font-size: 16px;
}

.report-result {
    margin-top: 30px;
    padding-top: 30px;
    border-top: 2px solid #e2e8f0;
}

.report-summary {
    background: #f7fafc;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.report-summary h3 {
    margin: 0 0 15px 0;
    color: #2d3748;
}

.report-summary p {
    margin: 8px 0;
    color: #4a5568;
}

.report-summary strong {
    color: #2d3748;
}

.report-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.report-table-wrapper {
    overflow-x: auto;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
}

.report-table thead {
    background: #f7fafc;
}

.report-table th {
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    color: #2d3748;
    border-bottom: 2px solid #e2e8f0;
}

.report-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e2e8f0;
    color: #4a5568;
}

.report-table tbody tr:hover {
    background: #f7fafc;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .report-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
let newUserReportData = [];
let attendanceReportData = [];

// Set default date range (last 30 days to today)
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    const thirtyDaysAgo = new Date();
    thirtyDaysAgo.setDate(today.getDate() - 30);
    
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };
    
    const startDateInput = document.getElementById('new_user_start_date');
    const endDateInput = document.getElementById('new_user_end_date');
    const attendanceStartInput = document.getElementById('attendance_start_date');
    const attendanceEndInput = document.getElementById('attendance_end_date');
    
    if (startDateInput && !startDateInput.value) {
        startDateInput.value = formatDate(thirtyDaysAgo);
    }
    if (endDateInput && !endDateInput.value) {
        endDateInput.value = formatDate(today);
    }
    if (attendanceStartInput && !attendanceStartInput.value) {
        attendanceStartInput.value = formatDate(thirtyDaysAgo);
    }
    if (attendanceEndInput && !attendanceEndInput.value) {
        attendanceEndInput.value = formatDate(today);
    }
});

// New User Report Form Submit
document.getElementById('newUserReportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?= base_url('/reports/new-user-report') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            newUserReportData = data.data;
            displayNewUserReport(data);
            if (data.count === 0) {
                alert('No members found in the selected date range. Try selecting a wider date range.');
            }
        } else {
            alert(data.message || 'Failed to generate report');
            console.error('Report error:', data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while generating the report. Check console for details.');
    });
});

// Attendance Report Form Submit
document.getElementById('attendanceReportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?= base_url('/reports/attendance-report') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            attendanceReportData = data.data;
            displayAttendanceReport(data);
        } else {
            alert(data.message || 'Failed to generate report');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while generating the report');
    });
});

function displayNewUserReport(data) {
    document.getElementById('newUserCount').textContent = data.count;
    document.getElementById('newUserDateRange').textContent = `${data.date_range.start} to ${data.date_range.end}`;
    
    const tbody = document.getElementById('newUserTableBody');
    tbody.innerHTML = '';
    
    data.data.forEach(user => {
        const row = tbody.insertRow();
        row.innerHTML = `
            <td>${user.id}</td>
            <td>${user.first_name} ${user.middle_name || ''} ${user.last_name}</td>
            <td>${user.email || 'N/A'}</td>
            <td>${user.user_type}</td>
            <td>${new Date(user.created_at).toLocaleString()}</td>
        `;
    });
    
    document.getElementById('newUserReportResult').style.display = 'block';
}

function displayAttendanceReport(data) {
    document.getElementById('totalScans').textContent = data.total_scans;
    document.getElementById('uniqueMembers').textContent = data.unique_members;
    document.getElementById('attendanceDateRange').textContent = `${data.date_range.start} to ${data.date_range.end}`;
    
    const tbody = document.getElementById('attendanceTableBody');
    tbody.innerHTML = '';
    
    data.data.forEach(record => {
        const row = tbody.insertRow();
        const scanDate = new Date(record.scan_time);
        row.innerHTML = `
            <td>${record.member_id}</td>
            <td>${record.first_name} ${record.middle_name || ''} ${record.last_name}</td>
            <td>${record.user_type || 'N/A'}</td>
            <td>${scanDate.toLocaleTimeString()}</td>
            <td>${scanDate.toLocaleDateString()}</td>
        `;
    });
    
    document.getElementById('attendanceReportResult').style.display = 'block';
}

function downloadNewUserReport() {
    if (newUserReportData.length === 0) {
        alert('No data to download');
        return;
    }
    
    let csv = 'Member ID,First Name,Middle Name,Last Name,Email,User Type,Registration Date\n';
    
    newUserReportData.forEach(user => {
        csv += `${user.id},"${user.first_name}","${user.middle_name || ''}","${user.last_name}","${user.email || 'N/A'}","${user.user_type}","${user.created_at}"\n`;
    });
    
    downloadCSV(csv, 'new_user_report.csv');
}

function downloadAttendanceReport() {
    if (attendanceReportData.length === 0) {
        alert('No data to download');
        return;
    }
    
    let csv = 'Member ID,First Name,Middle Name,Last Name,User Type,Scan Time\n';
    
    attendanceReportData.forEach(record => {
        csv += `${record.member_id},"${record.first_name}","${record.middle_name || ''}","${record.last_name}","${record.user_type || 'N/A'}","${record.scan_time}"\n`;
    });
    
    downloadCSV(csv, 'attendance_report.csv');
}

function downloadCSV(csv, filename) {
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', filename);
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

function printNewUserReport() {
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>New User Report</title>');
    printWindow.document.write('<style>table{width:100%;border-collapse:collapse;}th,td{border:1px solid #ddd;padding:8px;text-align:left;}th{background:#667eea;color:white;}</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1>New User Report</h1>');
    printWindow.document.write(document.getElementById('newUserTable').outerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}

function printAttendanceReport() {
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Attendance Report</title>');
    printWindow.document.write('<style>table{width:100%;border-collapse:collapse;}th,td{border:1px solid #ddd;padding:8px;text-align:left;}th{background:#667eea;color:white;}</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1>Attendance Report</h1>');
    printWindow.document.write(document.getElementById('attendanceTable').outerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>

<?= $this->endSection() ?>

