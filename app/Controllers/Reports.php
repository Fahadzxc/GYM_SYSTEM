<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RfidModel;

class Reports extends BaseController
{
    protected $userModel;
    protected $rfidModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->rfidModel = new RfidModel();
    }

    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to access reports');
        }

        $data = [
            'title' => 'Generate Reports'
        ];

        return view('admin/reports', $data);
    }

    /**
     * Generate New User Report
     */
    public function newUserReport()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ])->setStatusCode(401);
        }

        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');

        // Validate dates
        if (empty($startDate) || empty($endDate)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please provide start and end dates'
            ]);
        }

        // Get users registered within date range
        $users = $this->userModel
            ->where('created_at >=', $startDate . ' 00:00:00')
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $users,
            'count' => count($users),
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ]);
    }

    /**
     * Generate Attendance Report
     */
    public function attendanceReport()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ])->setStatusCode(401);
        }

        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');

        // Validate dates
        if (empty($startDate) || empty($endDate)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please provide start and end dates'
            ]);
        }

        // Get attendance records within date range with member details
        $db = \Config\Database::connect();
        $builder = $db->table('rfid_attendance');
        $builder->select('rfid_attendance.*, gym_members.first_name, gym_members.middle_name, gym_members.last_name, gym_members.user_type, gym_members.email');
        $builder->join('gym_members', 'gym_members.id = rfid_attendance.member_id', 'left');
        $builder->where('DATE(rfid_attendance.scan_time) >=', $startDate);
        $builder->where('DATE(rfid_attendance.scan_time) <=', $endDate);
        $builder->orderBy('rfid_attendance.scan_time', 'DESC');
        
        $attendance = $builder->get()->getResultArray();

        // Count unique members
        $uniqueMembers = array_unique(array_column($attendance, 'member_id'));

        return $this->response->setJSON([
            'success' => true,
            'data' => $attendance,
            'total_scans' => count($attendance),
            'unique_members' => count($uniqueMembers),
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ]);
    }
}

