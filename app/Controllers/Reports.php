<?php

namespace App\Controllers;

use App\Services\ReportsService;

class Reports extends BaseController
{
    protected $reportsService;

    public function __construct()
    {
        $this->reportsService = new ReportsService();
    }

    /**
     * Check if user has access to reports
     * Admin and staff have full access, faculty have limited access
     */
    protected function checkAccess()
    {
        if (!session()->get('isLoggedIn')) {
            return false;
        }

        $role = session()->get('role');
        $userType = session()->get('user_type');

        // Admin and staff have full access
        if ($role === 'admin' || $role === 'staff' || $userType === 'staff') {
            return true;
        }

        // Faculty can view their own reports only (limited access)
        if ($role === 'faculty' || $userType === 'faculty') {
            return 'limited';
        }

        return false;
    }

    /**
     * Main reports page
     */
    public function index()
    {
        $access = $this->checkAccess();
        
        if ($access === false) {
            return redirect()->to('/login')->with('error', 'Please login to access reports');
        }

        $data = [
            'title' => 'Reports Management',
            'user_role' => session()->get('role'),
            'user_type' => session()->get('user_type'),
            'has_full_access' => $access === true
        ];

        return view('admin/reports_unified', $data);
    }

    /**
     * Generate report (unified endpoint)
     */
    public function generate()
    {
        $access = $this->checkAccess();
        
        if ($access === false) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ])->setStatusCode(401);
        }

        $reportType = $this->request->getPost('report_type') ?? $this->request->getGet('report_type');
        $startDate = $this->request->getPost('start_date') ?? $this->request->getGet('start_date');
        $endDate = $this->request->getPost('end_date') ?? $this->request->getGet('end_date');
        $userType = $this->request->getPost('user_type') ?? $this->request->getGet('user_type');
        $page = (int)($this->request->getPost('page') ?? $this->request->getGet('page') ?? 1);
        $perPage = (int)($this->request->getPost('per_page') ?? $this->request->getGet('per_page') ?? 50);

        // Validate dates
        if (empty($startDate) || empty($endDate)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please provide start and end dates'
            ]);
        }

        // Validate report type
        $allowedTypes = ['new_users', 'attendance', 'membership', 'payments'];
        if (!in_array($reportType, $allowedTypes)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid report type'
            ]);
        }

        // For limited access (faculty), restrict to their own data
        if ($access === 'limited') {
            $userType = session()->get('user_type');
            // Additional filtering will be done in the service if needed
        }

        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'user_type' => $userType ?? 'all'
        ];

        $result = $this->reportsService->generateReport($reportType, $filters, $page, $perPage);

        // Log activity
        try {
            $activityLogService = new \App\Services\ActivityLogService();
            $activityLogService->logReportGeneration(
                session()->get('user_id'),
                session()->get('email'),
                $reportType
            );
        } catch (\Exception $e) {
            log_message('error', 'Activity log failed: ' . $e->getMessage());
        }

        return $this->response->setJSON($result);
    }

    /**
     * Export report to CSV
     */
    public function exportCSV()
    {
        $access = $this->checkAccess();
        
        if ($access === false) {
            return redirect()->to('/login')->with('error', 'Unauthorized access');
        }

        $reportType = $this->request->getGet('report_type');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $userType = $this->request->getGet('user_type') ?? 'all';

        // Validate
        if (empty($reportType) || empty($startDate) || empty($endDate)) {
            return redirect()->back()->with('error', 'Missing required parameters');
        }

        // Get all data (no pagination for export)
        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'user_type' => $userType
        ];

        $result = $this->reportsService->generateReport($reportType, $filters, 1, 10000);
        
        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        // Define headers based on report type
        $headers = $this->getReportHeaders($reportType);
        
        // Prepare filename
        $filename = $reportType . '_report_' . $startDate . '_to_' . $endDate;

        // Export
        $this->reportsService->exportToCSV($result['data'], $headers, $filename);
    }

    /**
     * Export report to PDF
     */
    public function exportPDF()
    {
        $access = $this->checkAccess();
        
        if ($access === false) {
            return redirect()->to('/login')->with('error', 'Unauthorized access');
        }

        $reportType = $this->request->getGet('report_type');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $userType = $this->request->getGet('user_type') ?? 'all';

        // Validate
        if (empty($reportType) || empty($startDate) || empty($endDate)) {
            return redirect()->back()->with('error', 'Missing required parameters');
        }

        // Get all data
        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'user_type' => $userType
        ];

        $result = $this->reportsService->generateReport($reportType, $filters, 1, 10000);
        
        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        // For now, redirect to print view (PDF generation can be added later with a library like TCPDF or DomPDF)
        $data = [
            'report_type' => $reportType,
            'report_data' => $result,
            'headers' => $this->getReportHeaders($reportType)
        ];

        return view('admin/reports_pdf', $data);
    }

    /**
     * Get report headers based on type
     */
    protected function getReportHeaders($reportType)
    {
        $headers = [
            'new_users' => ['id', 'first_name', 'middle_name', 'last_name', 'email', 'user_type', 'department', 'created_at'],
            'attendance' => ['member_id', 'first_name', 'middle_name', 'last_name', 'user_type', 'scan_time'],
            'membership' => ['id', 'first_name', 'middle_name', 'last_name', 'user_type', 'package_name', 'package_start_date', 'package_end_date', 'payment_status'],
            'payments' => ['member_id', 'first_name', 'middle_name', 'last_name', 'user_type', 'package_name', 'amount_paid', 'status', 'created_at']
        ];

        return $headers[$reportType] ?? [];
    }

    /**
     * Legacy endpoints for backward compatibility
     */
    public function newUserReport()
    {
        $this->request->setGlobal('post', array_merge($this->request->getPost(), ['report_type' => 'new_users']));
        return $this->generate();
    }

    public function attendanceReport()
    {
        $this->request->setGlobal('post', array_merge($this->request->getPost(), ['report_type' => 'attendance']));
        return $this->generate();
    }
}
