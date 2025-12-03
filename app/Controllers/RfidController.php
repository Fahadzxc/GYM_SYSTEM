<?php

namespace App\Controllers;

use App\Models\RfidModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * RFID Attendance Controller
 * 
 * Handles RFID scanning and attendance recording
 * This controller is modular and does not interfere with existing functionality
 */
class RfidController extends BaseController
{
    /**
     * Load URL helper for base_url() function in views
     */
    protected $helpers = ['url'];

    /**
     * Display the RFID scanner page
     */
    public function index()
    {
        $data = [
            'title' => 'Gym Management System'
        ];

        // Load the combined login view (RFID Scanner + Admin Login tabs)
        return view('login_combined', $data);
    }

    /**
     * Process RFID scan via AJAX POST request
     * 
     * Expected POST data:
     * - rfid_code: The RFID code scanned from the device
     * 
     * Returns JSON response:
     * - success: boolean
     * - message: string
     * - member_data: array (if member found)
     */
    public function scan()
    {
        // Only allow POST requests
        if (!$this->request->is('post')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method. Only POST is allowed.'
            ])->setStatusCode(405);
        }

        // Get RFID code from POST data
        $rfidCode = $this->request->getPost('rfid_code');

        // Log the received RFID code for debugging
        log_message('info', 'RFID Scan attempt - Received code: ' . ($rfidCode ?? 'NULL'));

        // Validate RFID code
        if (empty($rfidCode)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'RFID code is required. Please scan your card.'
            ])->setStatusCode(400);
        }

        // Trim whitespace from RFID code
        $rfidCode = trim($rfidCode);
        
        log_message('info', 'RFID Scan - Trimmed code: ' . $rfidCode);

        // Load models
        $userModel = new UserModel();
        $rfidModel = new RfidModel();

        // ============================================
        // CONFIGURATION: Adjust these field names if needed
        // ============================================
        // Option 1: Check if RFID code matches the 'id' field in gym_members table
        // Option 2: If you have a separate 'rfid_code' field, uncomment the second option below
        
        // Option 1: Using 'id' field (current setup)
        $member = $userModel->where('id', $rfidCode)
                           ->where('status', 'active')
                           ->first();

        // Option 2: Using separate 'rfid_code' field (if you add this column later)
        // Uncomment the line below and comment out Option 1 if you use a separate RFID field
        // $member = $userModel->where('rfid_code', $rfidCode)
        //                    ->where('status', 'active')
        //                    ->first();

        // ============================================

        // Check if member exists
        if (!$member) {
            log_message('warning', 'RFID Scan - Member not found: ' . $rfidCode);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Member ID "' . $rfidCode . '" not found or inactive. Please register first.',
                'rfid_code' => $rfidCode
            ])->setStatusCode(404);
        }
        
        log_message('info', 'RFID Scan - Member found: ' . $member['id'] . ' - ' . $member['first_name'] . ' ' . $member['last_name']);

        // Check if member already scanned today (optional - prevents duplicate scans)
        // You can remove this check if you want to allow multiple scans per day
        $today = date('Y-m-d');
        $existingAttendance = $rfidModel->where('member_id', $member['id'])
                                        ->where('DATE(scan_time)', $today)
                                        ->first();

        if ($existingAttendance) {
            // Member already scanned today - return success but with info message
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Attendance already recorded today.',
                'member_data' => [
                    'id' => $member['id'],
                    'name' => trim($member['first_name'] . ' ' . ($member['middle_name'] ?? '') . ' ' . $member['last_name']),
                    'user_type' => $member['user_type'] ?? 'N/A',
                    'previous_scan' => $existingAttendance['scan_time']
                ],
                'already_scanned' => true
            ]);
        }

        // Record attendance
        $attendanceData = [
            'member_id' => $member['id'],
            'scan_time' => date('Y-m-d H:i:s'),
            // Add any additional fields you need here
        ];

        // Insert attendance record
        if ($rfidModel->insert($attendanceData)) {
            log_message('info', 'RFID Scan - Attendance recorded successfully for member: ' . $member['id']);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Attendance recorded successfully!',
                'member_data' => [
                    'id' => $member['id'],
                    'name' => trim($member['first_name'] . ' ' . ($member['middle_name'] ?? '') . ' ' . $member['last_name']),
                    'user_type' => $member['user_type'] ?? 'N/A',
                    'scan_time' => $attendanceData['scan_time']
                ]
            ]);
        } else {
            // Get validation errors if any
            $errors = $rfidModel->errors();
            log_message('error', 'RFID Scan - Failed to insert attendance: ' . json_encode($errors));
            log_message('error', 'RFID Scan - Attempted data: ' . json_encode($attendanceData));
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to record attendance. ' . (!empty($errors) ? implode(', ', $errors) : 'Database error. Please check if the rfid_attendance table exists.'),
                'errors' => $errors,
                'debug_data' => $attendanceData
            ])->setStatusCode(500);
        }
    }

    /**
     * Get attendance history (optional endpoint for viewing records)
     * 
     * GET /rfid/attendance?limit=50&offset=0
     */
    public function attendance()
    {
        $rfidModel = new RfidModel();
        
        $limit = (int)($this->request->getGet('limit') ?? 50);
        $offset = (int)($this->request->getGet('offset') ?? 0);
        
        $attendance = $rfidModel->getAttendanceHistory($limit, $offset);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $attendance,
            'total' => $rfidModel->countAllResults()
        ]);
    }
}

