<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function login()
    {
        // Check if user is already logged in
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        
        // Load the login view
        return view('auth/login');
    }
    
    public function authenticate()
    {
        // Only allow POST requests
        if (!$this->request->is('post')) {
            return redirect()->to('/login')->with('error', 'Invalid request method');
        }
        
        try {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
                'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
            $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        // Check against database
        $db = \Config\Database::connect();
            
            // Check if database connection is working
            if (!$db) {
                throw new \Exception('Database connection failed');
            }
            
            $user = $db->table('users')->where('email', $email)->get()->getRowArray();
        
            if (!$user) {
                return redirect()->back()->withInput()->with('error', 'Invalid email or password');
            }
            
            // Verify password
            if (!password_verify($password, $user['password'])) {
                return redirect()->back()->withInput()->with('error', 'Invalid email or password');
            }
            
            // Check if user is active
            if ($user['status'] !== 'active') {
                return redirect()->back()->withInput()->with('error', 'Your account is inactive. Please contact administrator.');
            }
            
            $sessionData = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'role' => $user['role'],
                'isLoggedIn' => true
            ];
            
            session()->set($sessionData);
            
            // Update last login (check if column exists first)
            try {
                $db->table('users')->where('id', $user['id'])->update(['last_login' => date('Y-m-d H:i:s')]);
            } catch (\Exception $e) {
                // Column might not exist yet - try to add it
                try {
                    $db->query("ALTER TABLE `users` ADD COLUMN `last_login` DATETIME NULL AFTER `updated_at`");
                    $db->table('users')->where('id', $user['id'])->update(['last_login' => date('Y-m-d H:i:s')]);
                } catch (\Exception $e2) {
                    // Ignore if column addition fails
                    log_message('debug', 'Last login column update failed: ' . $e2->getMessage());
                }
            }
            
            // Log activity
            try {
                $activityLogService = new \App\Services\ActivityLogService();
                $activityLogService->logLogin($user['id'], $user['email'], true);
            } catch (\Exception $e) {
                // Log but don't fail login
                log_message('error', 'Activity log failed: ' . $e->getMessage());
            }
            
            return redirect()->to('/dashboard')->with('success', 'Login successful!');
            
        } catch (\Exception $e) {
            log_message('error', 'Login error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // Always show the actual error for debugging
            $errorMessage = 'Error: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ')';
            
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }
    
    public function logout()
    {
        // Log activity before destroying session
        try {
            if (session()->get('isLoggedIn')) {
                $activityLogService = new \App\Services\ActivityLogService();
                $activityLogService->logLogout(
                    session()->get('user_id'),
                    session()->get('email')
                );
            }
        } catch (\Exception $e) {
            log_message('error', 'Activity log failed: ' . $e->getMessage());
        }
        
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out successfully');
    }
    
    public function dashboard()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to access dashboard');
        }
        
        // Get today's attendance records with member details
        $db = \Config\Database::connect();
        $today = date('Y-m-d');
        
        $builder = $db->table('rfid_attendance');
        $builder->select('rfid_attendance.*, 
                         gym_members.first_name, 
                         gym_members.middle_name, 
                         gym_members.last_name, 
                         gym_members.user_type,
                         gym_members.status');
        $builder->join('gym_members', 'gym_members.id = rfid_attendance.member_id', 'left');
        $builder->where('DATE(rfid_attendance.scan_time)', $today);
        $builder->orderBy('rfid_attendance.scan_time', 'DESC');
        
        $todayAttendance = $builder->get()->getResultArray();
        
        // Get statistics
        $totalToday = count($todayAttendance);
        $uniqueMembers = count(array_unique(array_column($todayAttendance, 'member_id')));
        
        $data = [
            'attendance' => $todayAttendance,
            'total_today' => $totalToday,
            'unique_members' => $uniqueMembers
        ];
        
        return view('auth/dashboard', $data);
    }
    
    public function profile()
    {
        // Redirect to Profile controller
        return redirect()->to('/profile');
    }
}