<?php

namespace App\Services;

use App\Models\UserModel;
use App\Models\PaymentsModel;
use App\Models\RfidModel;
use Config\Database;

/**
 * Reports Service
 * 
 * Centralized service for generating all types of reports
 * Provides a clean, reusable structure for report generation
 */
class ReportsService
{
    protected $db;
    protected $userModel;
    protected $paymentsModel;
    protected $rfidModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->userModel = new UserModel();
        $this->paymentsModel = new PaymentsModel();
        $this->rfidModel = new RfidModel();
    }

    /**
     * Generate report based on type
     * 
     * @param string $reportType - new_users, attendance, membership, payments
     * @param array $filters - date range, user type, etc.
     * @param int $page - page number for pagination
     * @param int $perPage - items per page
     * @return array
     */
    public function generateReport($reportType, $filters = [], $page = 1, $perPage = 50)
    {
        $startDate = $filters['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $filters['end_date'] ?? date('Y-m-d');
        $userType = $filters['user_type'] ?? null;

        switch ($reportType) {
            case 'new_users':
                return $this->generateNewUsersReport($startDate, $endDate, $userType, $page, $perPage);
            
            case 'attendance':
                return $this->generateAttendanceReport($startDate, $endDate, $userType, $page, $perPage);
            
            case 'membership':
                return $this->generateMembershipReport($startDate, $endDate, $userType, $page, $perPage);
            
            case 'payments':
                return $this->generatePaymentsReport($startDate, $endDate, $userType, $page, $perPage);
            
            default:
                return [
                    'success' => false,
                    'message' => 'Invalid report type'
                ];
        }
    }

    /**
     * Generate New Users Report
     */
    protected function generateNewUsersReport($startDate, $endDate, $userType = null, $page = 1, $perPage = 50)
    {
        $builder = $this->db->table('gym_members');
        $builder->select('gym_members.*');
        
        // Date filter
        $builder->where('created_at IS NOT NULL', null, false);
        $builder->where('DATE(created_at) >=', $startDate);
        $builder->where('DATE(created_at) <=', $endDate);
        
        // User type filter
        if ($userType && $userType !== 'all') {
            $builder->where('user_type', $userType);
        }
        
        // Get total count for pagination (before limit)
        $totalRecords = $builder->countAllResults(false);
        
        // Apply pagination
        $offset = ($page - 1) * $perPage;
        $builder->orderBy('created_at', 'DESC');
        $builder->limit($perPage, $offset);
        
        $data = $builder->get()->getResultArray();
        
        return [
            'success' => true,
            'data' => $data,
            'count' => count($data),
            'total_records' => $totalRecords,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($totalRecords / $perPage),
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate
            ],
            'filters' => [
                'user_type' => $userType
            ]
        ];
    }

    /**
     * Generate Attendance Report
     */
    protected function generateAttendanceReport($startDate, $endDate, $userType = null, $page = 1, $perPage = 50)
    {
        $builder = $this->db->table('rfid_attendance');
        $builder->select('rfid_attendance.*, gym_members.first_name, gym_members.middle_name, gym_members.last_name, gym_members.user_type, gym_members.email');
        $builder->join('gym_members', 'gym_members.id = rfid_attendance.member_id', 'left');
        
        // Date filter
        $builder->where('DATE(rfid_attendance.scan_time) >=', $startDate);
        $builder->where('DATE(rfid_attendance.scan_time) <=', $endDate);
        
        // User type filter
        if ($userType && $userType !== 'all') {
            $builder->where('gym_members.user_type', $userType);
        }
        
        // Get total count
        $totalRecords = $builder->countAllResults(false);
        
        // Apply pagination
        $offset = ($page - 1) * $perPage;
        $builder->orderBy('rfid_attendance.scan_time', 'DESC');
        $builder->limit($perPage, $offset);
        
        $data = $builder->get()->getResultArray();
        
        // Count unique members
        $uniqueMembers = [];
        foreach ($data as $record) {
            if (!empty($record['member_id'])) {
                $uniqueMembers[$record['member_id']] = true;
            }
        }
        
        return [
            'success' => true,
            'data' => $data,
            'count' => count($data),
            'total_records' => $totalRecords,
            'unique_members' => count($uniqueMembers),
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($totalRecords / $perPage),
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate
            ],
            'filters' => [
                'user_type' => $userType
            ]
        ];
    }

    /**
     * Generate Membership Report
     */
    protected function generateMembershipReport($startDate, $endDate, $userType = null, $page = 1, $perPage = 50)
    {
        $builder = $this->db->table('gym_members');
        $builder->select('gym_members.*, payments.package_name, payments.start_date as package_start_date, payments.end_date as package_end_date, payments.status as payment_status');
        $builder->join('payments', 'payments.member_id = gym_members.id', 'left');
        
        // Filter by membership start date
        $builder->where('payments.start_date IS NOT NULL', null, false);
        $builder->where('DATE(payments.start_date) >=', $startDate);
        $builder->where('DATE(payments.start_date) <=', $endDate);
        
        // User type filter
        if ($userType && $userType !== 'all') {
            $builder->where('gym_members.user_type', $userType);
        }
        
        // Get total count
        $totalRecords = $builder->countAllResults(false);
        
        // Apply pagination
        $offset = ($page - 1) * $perPage;
        $builder->orderBy('payments.start_date', 'DESC');
        $builder->limit($perPage, $offset);
        
        $data = $builder->get()->getResultArray();
        
        return [
            'success' => true,
            'data' => $data,
            'count' => count($data),
            'total_records' => $totalRecords,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($totalRecords / $perPage),
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate
            ],
            'filters' => [
                'user_type' => $userType
            ]
        ];
    }

    /**
     * Generate Payments Report
     */
    protected function generatePaymentsReport($startDate, $endDate, $userType = null, $page = 1, $perPage = 50)
    {
        $builder = $this->db->table('payments');
        $builder->select('payments.*, gym_members.first_name, gym_members.middle_name, gym_members.last_name, gym_members.user_type, gym_members.email');
        $builder->join('gym_members', 'gym_members.id = payments.member_id', 'left');
        
        // Date filter - by payment creation date
        $builder->where('payments.created_at IS NOT NULL', null, false);
        $builder->where('DATE(payments.created_at) >=', $startDate);
        $builder->where('DATE(payments.created_at) <=', $endDate);
        
        // User type filter
        if ($userType && $userType !== 'all') {
            $builder->where('gym_members.user_type', $userType);
        }
        
        // Get total count
        $totalRecords = $builder->countAllResults(false);
        
        // Apply pagination
        $offset = ($page - 1) * $perPage;
        $builder->orderBy('payments.created_at', 'DESC');
        $builder->limit($perPage, $offset);
        
        $data = $builder->get()->getResultArray();
        
        // Calculate totals
        $totalAmount = 0;
        $paidCount = 0;
        $unpaidCount = 0;
        
        foreach ($data as $record) {
            $totalAmount += floatval($record['amount_paid'] ?? 0);
            if ($record['status'] === 'paid') {
                $paidCount++;
            } else {
                $unpaidCount++;
            }
        }
        
        return [
            'success' => true,
            'data' => $data,
            'count' => count($data),
            'total_records' => $totalRecords,
            'total_amount' => $totalAmount,
            'paid_count' => $paidCount,
            'unpaid_count' => $unpaidCount,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($totalRecords / $perPage),
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate
            ],
            'filters' => [
                'user_type' => $userType
            ]
        ];
    }

    /**
     * Export data to CSV format
     */
    public function exportToCSV($data, $headers, $filename)
    {
        $output = fopen('php://output', 'w');
        
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Write headers
        fputcsv($output, $headers);
        
        // Write data
        foreach ($data as $row) {
            $csvRow = [];
            foreach ($headers as $header) {
                $csvRow[] = $row[$header] ?? '';
            }
            fputcsv($output, $csvRow);
        }
        
        fclose($output);
        exit;
    }
}

