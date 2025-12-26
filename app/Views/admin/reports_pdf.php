<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($report_type) ?> Report - Gym Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #667eea;
        }
        .summary {
            background: #f7fafc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 30px;
            margin-bottom: 10px;
        }
        .summary-label {
            font-size: 12px;
            color: #718096;
            text-transform: uppercase;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #2d3748;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        tr:nth-child(even) {
            background: #f7fafc;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= esc(ucfirst(str_replace('_', ' ', $report_type))) ?> Report</h1>
        <p>Generated on <?= date('F d, Y h:i A') ?></p>
    </div>

    <div class="summary">
        <?php if ($report_type === 'new_users'): ?>
            <div class="summary-item">
                <div class="summary-label">Total Records</div>
                <div class="summary-value"><?= $report_data['total_records'] ?? count($report_data['data']) ?></div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Date Range</div>
                <div class="summary-value"><?= $report_data['date_range']['start'] ?> to <?= $report_data['date_range']['end'] ?></div>
            </div>
        <?php elseif ($report_type === 'attendance'): ?>
            <div class="summary-item">
                <div class="summary-label">Total Scans</div>
                <div class="summary-value"><?= $report_data['total_records'] ?? count($report_data['data']) ?></div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Unique Members</div>
                <div class="summary-value"><?= $report_data['unique_members'] ?? 0 ?></div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Date Range</div>
                <div class="summary-value"><?= $report_data['date_range']['start'] ?> to <?= $report_data['date_range']['end'] ?></div>
            </div>
        <?php elseif ($report_type === 'payments'): ?>
            <div class="summary-item">
                <div class="summary-label">Total Payments</div>
                <div class="summary-value"><?= $report_data['total_records'] ?? count($report_data['data']) ?></div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Amount</div>
                <div class="summary-value">₱<?= number_format($report_data['total_amount'] ?? 0, 2) ?></div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Paid</div>
                <div class="summary-value"><?= $report_data['paid_count'] ?? 0 ?></div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Unpaid</div>
                <div class="summary-value"><?= $report_data['unpaid_count'] ?? 0 ?></div>
            </div>
        <?php else: ?>
            <div class="summary-item">
                <div class="summary-label">Total Records</div>
                <div class="summary-value"><?= $report_data['total_records'] ?? count($report_data['data']) ?></div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Date Range</div>
                <div class="summary-value"><?= $report_data['date_range']['start'] ?> to <?= $report_data['date_range']['end'] ?></div>
            </div>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <?php foreach ($headers as $header): ?>
                    <th><?= esc(ucfirst(str_replace('_', ' ', $header))) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($report_data['data'] as $row): ?>
                <tr>
                    <?php foreach ($headers as $header): ?>
                        <td>
                            <?php
                            if ($header === 'name') {
                                // Combine name fields
                                $name = trim(($row['first_name'] ?? '') . ' ' . ($row['middle_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
                                echo esc($name ?: 'N/A');
                            } elseif ($header === 'created_at' || $header === 'scan_time' || $header === 'payment_date') {
                                $value = $row[$header] ?? '';
                                echo esc($value ? date('M d, Y h:i A', strtotime($value)) : 'N/A');
                            } elseif ($header === 'amount_paid' || $header === 'amount') {
                                $value = $row[$header] ?? $row['amount_paid'] ?? 0;
                                echo '₱' . number_format(floatval($value), 2);
                            } else {
                                $value = $row[$header] ?? 'N/A';
                                echo esc($value);
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Gym Management System - <?= date('Y') ?></p>
        <p>This is a computer-generated report.</p>
    </div>
</body>
</html>

