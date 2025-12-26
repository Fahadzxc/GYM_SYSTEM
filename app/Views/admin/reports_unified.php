<?= $this->extend('template') ?>

<?= $this->section('title') ?>
Reports Management
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="main-container">
    <?= $this->include('sidebar') ?>

    <div class="main-content">
        <div class="content-header">
            <h1 class="content-title">Reports Management</h1>
        </div>

        <div class="content-panel">
            <!-- Centralized Filter Section -->
            <div class="reports-filter-section">
                <h2 class="filter-title">📊 Report Filters</h2>
                <form id="reportsFilterForm" class="filter-form">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="report_type">Report Type *</label>
                            <select id="report_type" name="report_type" class="form-control" required>
                                <option value="">Select Report Type</option>
                                <option value="new_users">New Users</option>
                                <option value="attendance">Attendance</option>
                                <option value="membership">Membership</option>
                                <option value="payments">Payments</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="user_type_filter">User Type</label>
                            <select id="user_type_filter" name="user_type" class="form-control">
                                <option value="all">All Types</option>
                                <option value="faculty">Faculty</option>
                                <option value="staff">Staff</option>
                                <option value="athlete">Athlete</option>
                            </select>
                        </div>
                    </div>
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="start_date">Start Date *</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" required>
                        </div>
                        <div class="filter-group">
                            <label for="end_date">End Date *</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary" id="generateReportBtn">
                            <span class="btn-icon">📊</span> Generate Report
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="resetFilters()">
                            <span class="btn-icon">🔄</span> Reset
                        </button>
                    </div>
                </form>
            </div>

            <!-- Report Results Section -->
            <div id="reportResults" class="report-results" style="display: none;">
                <!-- Report Summary -->
                <div class="report-summary-card">
                    <h3 class="summary-title">Report Summary</h3>
                    <div class="summary-grid" id="summaryGrid">
                        <!-- Summary will be populated dynamically -->
                    </div>
                </div>

                <!-- Export Actions -->
                <div class="export-actions">
                    <button onclick="exportReport('csv')" class="btn btn-success">
                        <span class="btn-icon">⬇️</span> Download CSV
                    </button>
                    <button onclick="exportReport('pdf')" class="btn btn-danger">
                        <span class="btn-icon">📄</span> Export PDF
                    </button>
                    <button onclick="printReport()" class="btn btn-secondary">
                        <span class="btn-icon">🖨️</span> Print
                    </button>
                </div>

                <!-- Pagination Info -->
                <div class="pagination-info" id="paginationInfo"></div>

                <!-- Report Table -->
                <div class="report-table-wrapper">
                    <table id="reportTable" class="report-table">
                        <thead id="reportTableHead"></thead>
                        <tbody id="reportTableBody"></tbody>
                    </table>
                </div>

                <!-- Pagination Controls -->
                <div class="pagination-controls" id="paginationControls"></div>
            </div>

            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="loading-indicator" style="display: none;">
                <div class="spinner"></div>
                <p>Generating report...</p>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="empty-state">
                <div class="empty-icon">📋</div>
                <h3>No Report Generated</h3>
                <p>Select filters above and click "Generate Report" to view data.</p>
            </div>
        </div>
    </div>
</div>

<style>
.reports-filter-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
}

.filter-title {
    margin: 0 0 20px 0;
    font-size: 20px;
    font-weight: 700;
    color: #2d3748;
}

.filter-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.filter-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    margin-bottom: 8px;
    font-weight: 600;
    color: #4a5568;
    font-size: 14px;
}

.filter-actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.report-results {
    margin-top: 30px;
}

.report-summary-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
}

.summary-title {
    margin: 0 0 20px 0;
    font-size: 20px;
    font-weight: 700;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.summary-item {
    background: rgba(255, 255, 255, 0.2);
    padding: 15px;
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

.summary-label {
    font-size: 12px;
    opacity: 0.9;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.summary-value {
    font-size: 24px;
    font-weight: 700;
}

.export-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.pagination-info {
    margin-bottom: 15px;
    color: #4a5568;
    font-size: 14px;
}

.report-table-wrapper {
    overflow-x: auto;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    margin-bottom: 20px;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
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
    font-size: 14px;
    white-space: nowrap;
}

.report-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e2e8f0;
    color: #4a5568;
    font-size: 14px;
}

.report-table tbody tr:hover {
    background: #f7fafc;
}

.pagination-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 20px;
}

.pagination-btn {
    padding: 8px 16px;
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
}

.pagination-btn:hover:not(:disabled) {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-info-text {
    margin: 0 10px;
    color: #4a5568;
}

.loading-indicator {
    text-align: center;
    padding: 40px;
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #718096;
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.empty-state h3 {
    margin: 0 0 10px 0;
    color: #2d3748;
}

.empty-state p {
    margin: 0;
    color: #718096;
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

.btn-danger {
    background: #f56565;
    color: white;
}

.btn-danger:hover {
    background: #e53e3e;
}

.btn-secondary {
    background: #718096;
    color: white;
}

.btn-secondary:hover {
    background: #4a5568;
}

.form-control {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s;
    width: 100%;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

@media (max-width: 768px) {
    .filter-row {
        grid-template-columns: 1fr;
    }
    
    .export-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .summary-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
let currentReportData = null;
let currentFilters = {};
let currentPage = 1;
const perPage = 50;

// Set default date range (last 30 days)
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
    
    document.getElementById('start_date').value = formatDate(thirtyDaysAgo);
    document.getElementById('end_date').value = formatDate(today);
    
    // Form submit handler
    document.getElementById('reportsFilterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        generateReport();
    });
});

function generateReport(page = 1) {
    const form = document.getElementById('reportsFilterForm');
    const formData = new FormData(form);
    
    currentPage = page;
    currentFilters = {
        report_type: formData.get('report_type'),
        start_date: formData.get('start_date'),
        end_date: formData.get('end_date'),
        user_type: formData.get('user_type') || 'all',
        page: page,
        per_page: perPage
    };
    
    if (!currentFilters.report_type) {
        alert('Please select a report type');
        return;
    }
    
    // Show loading
    document.getElementById('loadingIndicator').style.display = 'block';
    document.getElementById('reportResults').style.display = 'none';
    document.getElementById('emptyState').style.display = 'none';
    
    // Fetch report
    fetch('<?= base_url('/reports/generate') ?>', {
        method: 'POST',
        body: new URLSearchParams(currentFilters),
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('loadingIndicator').style.display = 'none';
        
        if (data.success) {
            currentReportData = data;
            displayReport(data);
        } else {
            alert(data.message || 'Failed to generate report');
            document.getElementById('emptyState').style.display = 'block';
        }
    })
    .catch(error => {
        document.getElementById('loadingIndicator').style.display = 'none';
        console.error('Error:', error);
        alert('An error occurred while generating the report');
        document.getElementById('emptyState').style.display = 'block';
    });
}

function displayReport(data) {
    // Show results
    document.getElementById('reportResults').style.display = 'block';
    document.getElementById('emptyState').style.display = 'none';
    
    // Display summary
    displaySummary(data);
    
    // Display table
    displayTable(data);
    
    // Display pagination
    displayPagination(data);
}

function displaySummary(data) {
    const summaryGrid = document.getElementById('summaryGrid');
    const reportType = currentFilters.report_type;
    
    let summaryItems = [];
    
    if (reportType === 'new_users') {
        summaryItems = [
            { label: 'Total New Users', value: data.total_records || data.count },
            { label: 'Current Page', value: data.count },
            { label: 'Date Range', value: `${data.date_range.start} to ${data.date_range.end}` }
        ];
    } else if (reportType === 'attendance') {
        summaryItems = [
            { label: 'Total Scans', value: data.total_records || data.count },
            { label: 'Unique Members', value: data.unique_members || 0 },
            { label: 'Current Page', value: data.count },
            { label: 'Date Range', value: `${data.date_range.start} to ${data.date_range.end}` }
        ];
    } else if (reportType === 'membership') {
        summaryItems = [
            { label: 'Total Memberships', value: data.total_records || data.count },
            { label: 'Current Page', value: data.count },
            { label: 'Date Range', value: `${data.date_range.start} to ${data.date_range.end}` }
        ];
    } else if (reportType === 'payments') {
        summaryItems = [
            { label: 'Total Payments', value: data.total_records || data.count },
            { label: 'Total Amount', value: '₱' + parseFloat(data.total_amount || 0).toFixed(2) },
            { label: 'Paid', value: data.paid_count || 0 },
            { label: 'Unpaid', value: data.unpaid_count || 0 },
            { label: 'Date Range', value: `${data.date_range.start} to ${data.date_range.end}` }
        ];
    }
    
    summaryGrid.innerHTML = summaryItems.map(item => `
        <div class="summary-item">
            <div class="summary-label">${item.label}</div>
            <div class="summary-value">${item.value}</div>
        </div>
    `).join('');
}

function displayTable(data) {
    const thead = document.getElementById('reportTableHead');
    const tbody = document.getElementById('reportTableBody');
    const reportType = currentFilters.report_type;
    
    // Define headers based on report type
    const headers = {
        'new_users': ['Member ID', 'Name', 'Email', 'User Type', 'Department', 'Registration Date'],
        'attendance': ['Member ID', 'Name', 'User Type', 'Scan Time', 'Date'],
        'membership': ['Member ID', 'Name', 'User Type', 'Package', 'Start Date', 'End Date', 'Status'],
        'payments': ['Member ID', 'Name', 'User Type', 'Package', 'Amount', 'Status', 'Payment Date']
    };
    
    // Set headers
    thead.innerHTML = '<tr>' + headers[reportType].map(h => `<th>${h}</th>`).join('') + '</tr>';
    
    // Set data
    tbody.innerHTML = data.data.map(row => {
        if (reportType === 'new_users') {
            return `<tr>
                <td>${row.id || ''}</td>
                <td>${(row.first_name || '') + ' ' + (row.middle_name || '') + ' ' + (row.last_name || '')}</td>
                <td>${row.email || 'N/A'}</td>
                <td>${row.user_type || ''}</td>
                <td>${row.department || 'N/A'}</td>
                <td>${row.created_at ? new Date(row.created_at).toLocaleString() : 'N/A'}</td>
            </tr>`;
        } else if (reportType === 'attendance') {
            const scanDate = new Date(row.scan_time);
            return `<tr>
                <td>${row.member_id || ''}</td>
                <td>${(row.first_name || '') + ' ' + (row.middle_name || '') + ' ' + (row.last_name || '')}</td>
                <td>${row.user_type || 'N/A'}</td>
                <td>${scanDate.toLocaleTimeString()}</td>
                <td>${scanDate.toLocaleDateString()}</td>
            </tr>`;
        } else if (reportType === 'membership') {
            return `<tr>
                <td>${row.id || ''}</td>
                <td>${(row.first_name || '') + ' ' + (row.middle_name || '') + ' ' + (row.last_name || '')}</td>
                <td>${row.user_type || ''}</td>
                <td>${row.package_name || 'N/A'}</td>
                <td>${row.package_start_date || 'N/A'}</td>
                <td>${row.package_end_date || 'N/A'}</td>
                <td>${row.payment_status || 'N/A'}</td>
            </tr>`;
        } else if (reportType === 'payments') {
            return `<tr>
                <td>${row.member_id || ''}</td>
                <td>${(row.first_name || '') + ' ' + (row.middle_name || '') + ' ' + (row.last_name || '')}</td>
                <td>${row.user_type || 'N/A'}</td>
                <td>${row.package_name || 'N/A'}</td>
                <td>₱${parseFloat(row.amount_paid || 0).toFixed(2)}</td>
                <td>${row.status || 'N/A'}</td>
                <td>${row.created_at ? new Date(row.created_at).toLocaleString() : 'N/A'}</td>
            </tr>`;
        }
    }).join('');
}

function displayPagination(data) {
    const paginationInfo = document.getElementById('paginationInfo');
    const paginationControls = document.getElementById('paginationControls');
    
    if (!data.total_pages || data.total_pages <= 1) {
        paginationInfo.innerHTML = `Showing ${data.count} of ${data.total_records} records`;
        paginationControls.innerHTML = '';
        return;
    }
    
    paginationInfo.innerHTML = `Showing page ${data.page} of ${data.total_pages} (${data.total_records} total records)`;
    
    const prevDisabled = data.page <= 1 ? 'disabled' : '';
    const nextDisabled = data.page >= data.total_pages ? 'disabled' : '';
    
    paginationControls.innerHTML = `
        <button class="pagination-btn" ${prevDisabled} onclick="generateReport(${data.page - 1})">Previous</button>
        <span class="pagination-info-text">Page ${data.page} of ${data.total_pages}</span>
        <button class="pagination-btn" ${nextDisabled} onclick="generateReport(${data.page + 1})">Next</button>
    `;
}

function exportReport(format) {
    if (!currentFilters.report_type) {
        alert('Please generate a report first');
        return;
    }
    
    const params = new URLSearchParams({
        report_type: currentFilters.report_type,
        start_date: currentFilters.start_date,
        end_date: currentFilters.end_date,
        user_type: currentFilters.user_type || 'all'
    });
    
    if (format === 'csv') {
        window.location.href = '<?= base_url('/reports/export-csv') ?>?' + params.toString();
    } else if (format === 'pdf') {
        window.location.href = '<?= base_url('/reports/export-pdf') ?>?' + params.toString();
    }
}

function printReport() {
    const printWindow = window.open('', '', 'height=600,width=800');
    const reportType = currentFilters.report_type;
    const reportTitle = {
        'new_users': 'New Users Report',
        'attendance': 'Attendance Report',
        'membership': 'Membership Report',
        'payments': 'Payments Report'
    }[reportType] || 'Report';
    
    printWindow.document.write('<html><head><title>' + reportTitle + '</title>');
    printWindow.document.write('<style>table{width:100%;border-collapse:collapse;}th,td{border:1px solid #ddd;padding:8px;text-align:left;}th{background:#667eea;color:white;}</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1>' + reportTitle + '</h1>');
    printWindow.document.write(document.getElementById('reportTable').outerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}

function resetFilters() {
    document.getElementById('reportsFilterForm').reset();
    document.getElementById('reportResults').style.display = 'none';
    document.getElementById('emptyState').style.display = 'block';
    currentReportData = null;
    currentFilters = {};
    currentPage = 1;
    
    // Reset dates to default
    const today = new Date();
    const thirtyDaysAgo = new Date();
    thirtyDaysAgo.setDate(today.getDate() - 30);
    
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };
    
    document.getElementById('start_date').value = formatDate(thirtyDaysAgo);
    document.getElementById('end_date').value = formatDate(today);
}
</script>

<?= $this->endSection() ?>

