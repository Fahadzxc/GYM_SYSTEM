<?php

namespace App\Controllers;

use App\Models\AdminUserModel;
use App\Models\ActivityLogModel;
use App\Services\ActivityLogService;

class Profile extends BaseController
{
    protected $adminUserModel;
    protected $activityLogService;

    public function __construct()
    {
        $this->adminUserModel = new AdminUserModel();
        $this->activityLogService = new ActivityLogService();
    }

    /**
     * Display user profile
     */
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to access profile');
        }

        $userId = session()->get('user_id');
        $user = $this->adminUserModel->getUserById($userId);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'User not found');
        }

        // Always log current profile view first
        try {
            $this->activityLogService->log('profile_view', 'Viewed profile page', $userId, $user['email'] ?? session()->get('email'));
        } catch (\Exception $e) {
            log_message('error', 'Activity logging failed: ' . $e->getMessage());
        }
        
        // Get recent activities
        $activityLogModel = new ActivityLogModel();
        $activities = [];
        
        try {
            $activities = $activityLogModel->getUserActivities($userId, 10);
        } catch (\Exception $e) {
            log_message('error', 'Activity log fetch failed: ' . $e->getMessage());
            $activities = [];
        }

        // Format activities for display
        $formattedActivities = [];
        foreach ($activities as $activity) {
            $formattedActivities[] = [
                'description' => $activity['activity_description'] ?? 'Activity',
                'time' => $this->formatTimeAgo($activity['created_at'] ?? date('Y-m-d H:i:s'))
            ];
        }

        $data = [
            'title' => 'Profile',
            'user' => $user,
            'activities' => $formattedActivities,
            'full_name' => trim(($user['first_name'] ?? '') . ' ' . ($user['middle_name'] ?? '') . ' ' . ($user['last_name'] ?? '')),
            'role_display' => ucfirst($user['role'] ?? 'User'),
            'is_verified' => !empty($user['verified']),
            'profile_picture' => $user['profile_picture'] ?? null
        ];

        return view('admin/profile', $data);
    }

    /**
     * Update profile information
     */
    public function update()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ])->setStatusCode(401);
        }

        $userId = session()->get('user_id');
        $post = $this->request->getPost();

        // Prepare update data
        $updateData = [
            'first_name' => $post['first_name'] ?? null,
            'middle_name' => $post['middle_name'] ?? null,
            'last_name' => $post['last_name'] ?? null,
            'email' => $post['email'] ?? null,
            'phone_no' => $post['phone_no'] ?? null,
        ];

        // Remove empty values
        $updateData = array_filter($updateData, function($value) {
            return $value !== null && $value !== '';
        });

        // Validate email if provided
        if (isset($updateData['email'])) {
            $existingUser = $this->adminUserModel->getUserByEmail($updateData['email']);
            if ($existingUser && $existingUser['id'] != $userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Email address already exists'
                ]);
            }
        }

        try {
            // Validate required fields manually
            if (empty($updateData['first_name']) || empty($updateData['last_name']) || empty($updateData['email'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'First name, last name, and email are required'
                ]);
            }
            
            // Validate email format
            if (!filter_var($updateData['email'], FILTER_VALIDATE_EMAIL)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid email format'
                ]);
            }
            
            // Skip validation and update directly
            $this->adminUserModel->skipValidation(true);
            
            if (!$this->adminUserModel->update($userId, $updateData)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update profile'
                ]);
            }

            // Log activity
            $updatedFields = array_keys($updateData);
            $this->activityLogService->logProfileUpdate($userId, session()->get('email'), $updatedFields);

            // Update session data
            $updatedUser = $this->adminUserModel->getUserById($userId);
            session()->set([
                'first_name' => $updatedUser['first_name'],
                'last_name' => $updatedUser['last_name'],
                'email' => $updatedUser['email']
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $updatedUser
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Profile update error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update profile'
            ]);
        }
    }

    /**
     * Upload profile picture
     */
    public function uploadPicture()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ])->setStatusCode(401);
        }

        $userId = session()->get('user_id');
        $file = $this->request->getFile('profile_picture');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No file uploaded or file is invalid'
            ]);
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid file type. Only JPEG, PNG, and GIF are allowed.'
            ]);
        }

        // Validate file size (max 2MB)
        if ($file->getSize() > 2097152) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'File size exceeds 2MB limit'
            ]);
        }

        try {
            // Get current user to delete old picture
            $user = $this->adminUserModel->getUserById($userId);
            $oldPicture = $user['profile_picture'] ?? null;

            // Generate unique filename
            $newName = 'profile_' . $userId . '_' . time() . '.' . $file->getExtension();
            $publicPath = FCPATH . 'uploads/profiles/';

            // Create directory if it doesn't exist
            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            // Move file to public directory
            if ($file->move($publicPath, $newName)) {
                // Delete old picture if exists
                if ($oldPicture && file_exists($publicPath . $oldPicture)) {
                    @unlink($publicPath . $oldPicture);
                }

                // Update database
                $this->adminUserModel->update($userId, ['profile_picture' => $newName]);

                // Log activity
                $this->activityLogService->log('profile_picture_update', 'Profile picture updated', $userId, session()->get('email'));
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Profile picture uploaded successfully',
                    'picture_url' => base_url('uploads/profiles/' . $newName)
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to upload file'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Profile picture upload error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to upload profile picture'
            ]);
        }
    }

    /**
     * Change password
     */
    public function changePassword()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ])->setStatusCode(401);
        }

        $userId = session()->get('user_id');
        $post = $this->request->getPost();

        $currentPassword = $post['current_password'] ?? '';
        $newPassword = $post['new_password'] ?? '';
        $confirmPassword = $post['confirm_password'] ?? '';

        // Validate inputs
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'All password fields are required'
            ]);
        }

        if ($newPassword !== $confirmPassword) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'New password and confirm password do not match'
            ]);
        }

        if (strlen($newPassword) < 6) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Password must be at least 6 characters'
            ]);
        }

        // Get user and verify current password
        $user = $this->adminUserModel->getUserById($userId);
        if (!password_verify($currentPassword, $user['password'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Current password is incorrect'
            ]);
        }

        // Update password
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $this->adminUserModel->update($userId, ['password' => $hashedPassword]);

            // Log activity
            $this->activityLogService->logPasswordChange($userId, session()->get('email'));

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Password changed successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Password change error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to change password'
            ]);
        }
    }

    /**
     * Get activity logs
     */
    public function getActivities()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ])->setStatusCode(401);
        }

        $userId = session()->get('user_id');
        $limit = (int)($this->request->getGet('limit') ?? 10);

        $activityLogModel = new ActivityLogModel();
        $activities = [];
        
        try {
            // Ensure table exists
            $this->createActivityLogsTable();
            $activities = $activityLogModel->getUserActivities($userId, $limit);
        } catch (\Exception $e) {
            log_message('error', 'Activity log fetch failed: ' . $e->getMessage());
            // Try to create table and fetch again
            try {
                $this->createActivityLogsTable();
                $activities = $activityLogModel->getUserActivities($userId, $limit);
            } catch (\Exception $e2) {
                log_message('error', 'Failed to fetch activities: ' . $e2->getMessage());
            }
        }

        $formattedActivities = [];
        foreach ($activities as $activity) {
            $formattedActivities[] = [
                'description' => $activity['activity_description'] ?? 'Activity',
                'time' => $this->formatTimeAgo($activity['created_at'] ?? date('Y-m-d H:i:s')),
                'type' => $activity['activity_type'] ?? 'unknown'
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $formattedActivities
        ]);
    }

    /**
     * Create activity logs table if it doesn't exist
     */
    protected function createActivityLogsTable()
    {
        $db = \Config\Database::connect();
        
        // Check if table already exists
        if ($db->tableExists('activity_logs')) {
            return true;
        }
        
        // Create table
        $sql = "CREATE TABLE IF NOT EXISTS `activity_logs` (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `user_id` INT(11) UNSIGNED NULL,
            `user_email` VARCHAR(100) NULL,
            `activity_type` VARCHAR(50) NOT NULL,
            `activity_description` TEXT NOT NULL,
            `ip_address` VARCHAR(45) NULL,
            `user_agent` TEXT NULL,
            `created_at` DATETIME NULL,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`),
            KEY `activity_type` (`activity_type`),
            KEY `created_at` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        try {
            $db->query($sql);
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Failed to create activity_logs table: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Format time ago
     */
    protected function formatTimeAgo($datetime)
    {
        if (empty($datetime)) {
            return 'Unknown';
        }

        $time = strtotime($datetime);
        $diff = time() - $time;

        if ($diff < 60) {
            return $diff . ' seconds ago';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            return date('M d, Y', $time);
        }
    }
}

