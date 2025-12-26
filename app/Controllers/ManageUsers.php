<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RegistrationModel;
use App\Models\RfidModel;
use App\Models\PaymentsModel;

class ManageUsers extends BaseController
{
    protected $userModel;
    protected $registrationModel;
    protected $rfidModel;
    protected $paymentsModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->registrationModel = new RegistrationModel();
        $this->rfidModel = new RfidModel();
        $this->paymentsModel = new PaymentsModel();
    }

    public function index()
    {
        $data = [
            'members' => $this->userModel->findAll()
        ];
        return view('admin/manage_users', $data);
    }

    /**
     * Check school ID for registration
     * 
     * POST /manage-users/check-school-id
     */
    public function checkSchoolId()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ])->setStatusCode(405);
        }

        $schoolId = trim($this->request->getPost('school_id') ?? '');

        if (empty($schoolId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'School ID is required.'
            ])->setStatusCode(400);
        }

        // Check if already registered
        $existingMember = $this->userModel->find($schoolId);
        if ($existingMember) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This ID is already registered.',
                'already_registered' => true,
                'member_data' => $existingMember
            ])->setStatusCode(409);
        }

        // Check eligibility
        $eligibilityCheck = $this->registrationModel->checkEligibility($schoolId);
        if (!$eligibilityCheck['eligible']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are not allowed to register for gym access.',
                'eligible' => false,
                'reason' => $eligibilityCheck['reason'] ?? 'Role not eligible'
            ])->setStatusCode(403);
        }

        // Get school member info (if available)
        $memberInfo = $this->registrationModel->getSchoolMemberInfo($schoolId);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'School ID verified. Please complete registration.',
            'eligible' => true,
            'school_id' => $schoolId,
            'member_info' => $memberInfo,
            'suggested_user_type' => $eligibilityCheck['user_type'] ?? null
        ]);
    }

    public function addUser()
    {
        // Check if request is POST
        if (!$this->request->is('post')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        // Log the request for debugging
        log_message('info', 'Add user request received: ' . json_encode($this->request->getPost()));

        $schoolId = trim($this->request->getPost('id') ?? '');

        // Check if already registered
        $existingMember = $this->userModel->find($schoolId);
        if ($existingMember) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This school ID is already registered.',
                'already_registered' => true
            ])->setStatusCode(409);
        }

        // Check eligibility
        $eligibilityCheck = $this->registrationModel->checkEligibility($schoolId);
        if (!$eligibilityCheck['eligible']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are not allowed to register for gym access.',
                'eligible' => false
            ])->setStatusCode(403);
        }

        // Get form data
        $data = [
            'id' => $schoolId,
            'first_name' => $this->request->getPost('first_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'last_name' => $this->request->getPost('last_name'),
            'address' => $this->request->getPost('address'),
            'phone_no' => $this->request->getPost('phone_no'),
            'email' => $this->request->getPost('email'),
            'user_type' => $this->request->getPost('user_type'),
            'status' => 'active'
        ];

        // Validate data
        if (!$this->userModel->insert($data)) {
            log_message('error', 'Validation failed: ' . json_encode($this->userModel->errors()));
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->userModel->errors()
            ]);
        }

        // If this is a faculty member, save payment/membership info
        try {
            if (isset($data['user_type']) && $data['user_type'] === 'faculty' && $this->request->getPost('package_name')) {
                $pkg = $this->request->getPost('package_name');
                $amount = $this->request->getPost('amount_paid');
                
                // Set default amount based on package if not provided
                if (empty($amount) || $amount == 0) {
                    if ($pkg === 'Monthly') $amount = 800;
                    elseif ($pkg === 'Semester') $amount = 3000;
                    elseif ($pkg === 'Annual') $amount = 6000;
                    else $amount = 0;
                }
                
                $paymentData = [
                    'member_id' => $schoolId,
                    'package_name' => $pkg,
                    'amount_paid' => $amount,
                    'status' => $this->request->getPost('payment_status') ?: 'paid',
                    'start_date' => $this->request->getPost('start_date') ?: null,
                    'end_date' => $this->request->getPost('end_date') ?: null,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $this->paymentsModel->insert($paymentData);
                log_message('info', 'Payment record added for member: ' . $schoolId . ' with amount: ' . $amount);
            }
        } catch (\Exception $e) {
            // Log and continue — member was created but payments table may not exist yet
            log_message('error', 'Failed to insert payment record: ' . $e->getMessage());
        }

        // Log activity
        try {
            $activityLogService = new \App\Services\ActivityLogService();
            $activityLogService->logUserCreation(
                session()->get('user_id'),
                session()->get('email'),
                $schoolId
            );
        } catch (\Exception $e) {
            log_message('error', 'Activity log failed: ' . $e->getMessage());
        }

        log_message('info', 'User added successfully: ' . json_encode($data));

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Registration successful – this member can now enter the gym using their school ID!',
            'data' => $data
        ]);
    }

    public function editUser()
    {
        // Check if request is POST
        if (!$this->request->is('post')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        // Log the request for debugging
        log_message('info', 'Edit user request received: ' . json_encode($this->request->getPost()));

        $oldUserId = $this->request->getPost('edit_user_id');
        $newUserId = trim($this->request->getPost('edit_id'));
        
        // Get form data
        $data = [
            'id' => $newUserId,
            'first_name' => $this->request->getPost('edit_first_name'),
            'middle_name' => $this->request->getPost('edit_middle_name'),
            'last_name' => $this->request->getPost('edit_last_name'),
            'address' => $this->request->getPost('edit_address'),
            'phone_no' => $this->request->getPost('edit_phone_no'),
            'email' => $this->request->getPost('edit_email'),
            'user_type' => $this->request->getPost('edit_user_type'),
            'status' => 'active'
        ];

        // Check if user exists
        $existingUser = $this->userModel->find($oldUserId);
        if (!$existingUser) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        // If ID is changing, check if new ID already exists
        if ($oldUserId !== $newUserId) {
            $userWithNewId = $this->userModel->find($newUserId);
            if ($userWithNewId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'This ID is already taken by another member.',
                    'errors' => ['id' => 'ID already exists']
                ]);
            }
        }

        // Validate data for editing
        if (!$this->userModel->validateEdit($data, $oldUserId)) {
            log_message('error', 'Validation failed: ' . json_encode($this->userModel->errors()));
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->userModel->errors()
            ]);
        }

        // If ID is changing, we need to delete old and insert new
        if ($oldUserId !== $newUserId) {
            // Get the old user data to preserve timestamps if needed
            $oldData = $existingUser;
            
            // Start database transaction for data integrity
            $db = \Config\Database::connect();
            $db->transStart();
            
            try {
                // Update RFID attendance records first
                $rfidUpdated = $db->table('rfid_attendance')
                    ->where('member_id', $oldUserId)
                    ->update(['member_id' => $newUserId]);
                
                log_message('info', "Updated {$rfidUpdated} RFID attendance records from {$oldUserId} to {$newUserId}");
                
                // Delete the old record
                if (!$this->userModel->delete($oldUserId)) {
                    throw new \Exception('Failed to delete old user record');
                }
                
                // Insert new record with new ID
                if (!$this->userModel->insert($data)) {
                    throw new \Exception('Failed to insert new user record');
                }
                
                // Commit transaction
                $db->transComplete();
                
                if ($db->transStatus() === false) {
                    throw new \Exception('Transaction failed');
                }
                
            } catch (\Exception $e) {
                // Rollback transaction on error
                $db->transRollback();
                log_message('error', 'Update failed: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update member: ' . $e->getMessage(),
                    'errors' => $this->userModel->errors()
                ]);
            }
        } else {
            // ID is not changing, just update normally
            if (!$this->userModel->update($oldUserId, $data)) {
                log_message('error', 'Update failed');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update member',
                    'errors' => $this->userModel->errors()
                ]);
            }
        }

        // Log activity
        try {
            $activityLogService = new \App\Services\ActivityLogService();
            $description = 'Updated user/member';
            if ($oldUserId !== $newUserId) {
                $description .= ' (ID changed: ' . $oldUserId . ' → ' . $newUserId . ')';
            } else {
                $description .= ' (ID: ' . $oldUserId . ')';
            }
            $activityLogService->log('user_update', $description, 
                session()->get('user_id'), session()->get('email'));
        } catch (\Exception $e) {
            log_message('error', 'Activity log failed: ' . $e->getMessage());
        }

        log_message('info', 'User updated successfully: ' . json_encode($data));

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Member updated successfully!',
            'data' => $data
        ]);
    }

    public function getAllUsers()
    {
        $members = $this->userModel->findAll();
        return $this->response->setJSON([
            'success' => true,
            'data' => $members
        ]);
    }

    public function deleteUser()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $userId = $this->request->getJSON(true)['user_id'] ?? null;
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User ID is required'
            ]);
        }

        log_message('info', 'Delete user request received for ID: ' . $userId);

        // Check if user exists
        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        // Delete the user
        if ($this->userModel->delete($userId)) {
            log_message('info', 'User deleted successfully: ' . $userId);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Member deleted successfully!'
            ]);
        } else {
            log_message('error', 'Failed to delete user: ' . $userId);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete member'
            ]);
        }
    }
}
