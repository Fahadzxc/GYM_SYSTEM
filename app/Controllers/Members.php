<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PaymentsModel;

class Members extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // If faculty is logged in, send to faculty dashboard
        if (session()->get('isLoggedIn') && (session()->get('role') === 'faculty' || session()->get('user_type') === 'faculty')) {
            return redirect()->to('/members/dashboard');
        }

        // Public members listing with payment details
        $db = \Config\Database::connect();
        try {
            $members = $db->table('gym_members m')
                ->select('m.id, m.first_name, m.middle_name, m.last_name, m.address, m.user_type, m.email, m.phone_no, m.department, p.package_name, p.start_date, p.end_date')
                ->join('payments p', 'p.member_id = m.id', 'left')
                ->where('m.status', 'active')
                ->get()
                ->getResultArray();
        } catch (\Exception $e) {
            // If payments table doesn't exist, just fetch members
            $members = $this->userModel->getActiveMembers();
        }

        $data = [
            'members' => $members
        ];

        return view('members/list', $data);
    }

    /**
     * Handle adding a new member (separate from admin add user)
     */
    public function add()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $post = $this->request->getPost();

        $data = [
            'id' => isset($post['id']) ? $post['id'] : null,
            'first_name' => isset($post['first_name']) ? $post['first_name'] : null,
            'middle_name' => isset($post['middle_name']) ? $post['middle_name'] : null,
            'last_name' => isset($post['last_name']) ? $post['last_name'] : null,
            'address' => isset($post['address']) ? $post['address'] : null,
            'phone_no' => isset($post['phone_no']) ? $post['phone_no'] : null,
            'email' => isset($post['email']) ? $post['email'] : null,
            'user_type' => isset($post['user_type']) ? $post['user_type'] : 'faculty',
            'status' => 'active'
        ];

        $userModel = new UserModel();
        try {
            if (!$userModel->insert($data)) {
                $errors = $userModel->errors();
                return $this->response->setJSON(['success' => false, 'message' => 'Validation failed', 'errors' => $errors]);
            }

            // Insert payment if faculty and payment info provided
            if (!empty($data['user_type']) && $data['user_type'] === 'faculty' && !empty($post['package_name'])) {
                try {
                    $payments = new PaymentsModel();
                    // enforce sensible defaults: payment status paid, start_date = today, end_date computed by package
                    $pkg = $post['package_name'];
                    $amount = isset($post['amount_paid']) ? $post['amount_paid'] : null;
                    if (empty($amount)) {
                        // map default amounts
                        if ($pkg === 'Monthly') $amount = 800;
                        elseif ($pkg === 'Semester') $amount = 3000;
                        elseif ($pkg === 'Annual') $amount = 6000;
                        else $amount = 0;
                    }

                    $start = !empty($post['start_date']) ? $post['start_date'] : date('Y-m-d');
                    $end = !empty($post['end_date']) ? $post['end_date'] : null;
                    if (empty($end)) {
                        // compute end date based on package
                        $sd = strtotime($start);
                        if ($pkg === 'Monthly') $ed = strtotime('+1 month', $sd);
                        elseif ($pkg === 'Semester') $ed = strtotime('+6 months', $sd);
                        elseif ($pkg === 'Annual') $ed = strtotime('+1 year', $sd);
                        else $ed = false;
                        if ($ed) $end = date('Y-m-d', $ed);
                    }

                    $paymentData = [
                        'member_id' => $data['id'],
                        'package_name' => $pkg ?? null,
                        'amount_paid' => $amount,
                        'status' => !empty($post['payment_status']) ? $post['payment_status'] : 'paid',
                        'start_date' => $start,
                        'end_date' => $end,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $payments->insert($paymentData);
                } catch (\Exception $e) {
                    // Log but don't fail member creation
                    log_message('error', 'Payments insert failed: ' . $e->getMessage());
                }
            }

            // Log activity
            try {
                $activityLogService = new \App\Services\ActivityLogService();
                $userId = session()->get('user_id');
                $userEmail = session()->get('email');
                
                if (empty($userId)) {
                    log_message('error', 'Cannot log activity: user_id is empty in session');
                } else {
                    $result = $activityLogService->log('member_creation', 'Added new member (ID: ' . $data['id'] . ', Name: ' . $data['first_name'] . ' ' . $data['last_name'] . ')',
                        $userId, $userEmail);
                    if (!$result) {
                        log_message('error', 'Activity log insert returned false for member creation');
                    } else {
                        log_message('info', 'Activity logged successfully: Added member ' . $data['id']);
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'Activity log failed: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Member created']);
        } catch (\Exception $e) {
            log_message('error', 'Member add error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Server error']);
        }
    }

    /**
     * Handle editing a member (ID, start_date, end_date are readonly)
     */
    public function edit()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $post = $this->request->getPost();
        $memberId = $post['id'] ?? null;

        if (!$memberId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Member ID required']);
        }

        $userModel = new UserModel();
        
        // Get existing member to preserve user_type and other fields
        $existingMember = $userModel->find($memberId);
        if (!$existingMember) {
            return $this->response->setJSON(['success' => false, 'message' => 'Member not found']);
        }

        // Basic validation for required fields
        if (empty($post['first_name']) || empty($post['last_name'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'First name and last name are required']);
        }

        // Validate email format if provided
        if (!empty($post['email']) && !filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid email format']);
        }

        $data = [
            'first_name' => $post['first_name'],
            'middle_name' => $post['middle_name'] ?? null,
            'last_name' => $post['last_name'],
            'address' => $post['address'] ?? null,
            'phone_no' => $post['phone_no'] ?? null,
            'email' => $post['email'] ?? null,
            'department' => $post['department'] ?? null,
        ];

        try {
            // Skip validation for updates since we're only updating specific fields
            $userModel->skipValidation(true);
            if (!$userModel->update($memberId, $data)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to update member']);
            }

            // Update payment if package is provided
            if (!empty($post['package_name'])) {
                try {
                    $payments = new PaymentsModel();
                    $pkg = $post['package_name'];
                    $amount = $post['amount_paid'] ?? null;
                    
                    // Set default amount based on package if not provided or is 0
                    if (empty($amount) || $amount == 0) {
                        if ($pkg === 'Monthly') $amount = 800;
                        elseif ($pkg === 'Semester') $amount = 3000;
                        elseif ($pkg === 'Annual') $amount = 6000;
                        else $amount = 0;
                    }
                    
                    $paymentData = [
                        'package_name' => $pkg,
                        'amount_paid' => $amount,
                    ];
                    $payments->where('member_id', $memberId)->update(null, $paymentData);
                } catch (\Exception $e) {
                    log_message('error', 'Payment update failed: ' . $e->getMessage());
                }
            }

            // Log activity
            try {
                $activityLogService = new \App\Services\ActivityLogService();
                $memberName = ($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '');
                $userId = session()->get('user_id');
                $userEmail = session()->get('email');
                
                if (empty($userId)) {
                    log_message('error', 'Cannot log activity: user_id is empty in session');
                } else {
                    $result = $activityLogService->log('member_update', 'Updated member (ID: ' . $memberId . ', Name: ' . trim($memberName) . ')',
                        $userId, $userEmail);
                    if (!$result) {
                        log_message('error', 'Activity log insert returned false for member update');
                    } else {
                        log_message('info', 'Activity logged successfully: Updated member ' . $memberId);
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'Activity log failed: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Member updated']);
        } catch (\Exception $e) {
            log_message('error', 'Member edit error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Server error']);
        }
    }
}
