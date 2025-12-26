<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminUserModel;
use App\Services\ActivityLogService;

class Settings extends BaseController
{
    protected $adminUserModel;
    protected $activityLogService;

    public function __construct()
    {
        $this->adminUserModel = new AdminUserModel();
        $this->activityLogService = new ActivityLogService();
    }

    /**
     * Display settings page
     */
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/admin-login')->with('error', 'Please login to access settings');
        }

        $userId = session()->get('user_id');
        $user = $this->adminUserModel->getUserById($userId);

        if (!$user) {
            session()->destroy();
            return redirect()->to('/admin-login')->with('error', 'User not found. Please login again.');
        }

        $data = [
            'title' => 'Settings',
            'user' => $user,
        ];

        return view('admin/settings', $data);
    }

    /**
     * Update system settings (placeholder for future implementation)
     */
    public function updateSystemSettings()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ])->setStatusCode(400);
        }

        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ])->setStatusCode(401);
        }

        // Placeholder for system settings update
        // This can be expanded to update app config, database settings, etc.

        try {
            $this->activityLogService->log('settings_update', 'Updated system settings', 
                session()->get('user_id'), session()->get('email'));
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Settings updated successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Settings update failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update settings'
            ])->setStatusCode(500);
        }
    }
}

