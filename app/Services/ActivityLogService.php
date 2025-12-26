<?php

namespace App\Services;

use App\Models\ActivityLogModel;

/**
 * Activity Log Service
 * Centralized service for logging user activities
 */
class ActivityLogService
{
    protected $activityLogModel;

    public function __construct()
    {
        $this->activityLogModel = new ActivityLogModel();
    }

    /**
     * Log user activity
     */
    public function log($activityType, $description, $userId = null, $userEmail = null)
    {
        try {
            $request = \Config\Services::request();
            
            // Skip if no user ID
            $finalUserId = $userId ?? session()->get('user_id');
            if (empty($finalUserId)) {
                return false;
            }
            
            $data = [
                'user_id' => $finalUserId,
                'user_email' => $userEmail ?? session()->get('email') ?? 'unknown',
                'activity_type' => $activityType,
                'activity_description' => $description,
                'ip_address' => $request->getIPAddress() ?? 'unknown',
                'user_agent' => $request->getUserAgent() ? $request->getUserAgent()->getAgentString() : 'unknown',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $result = $this->activityLogModel->insert($data);
            if (!$result) {
                $errors = $this->activityLogModel->errors();
                log_message('error', 'Activity log insert failed: ' . json_encode($errors));
            }
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Activity log failed: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Log login activity
     */
    public function logLogin($userId, $userEmail, $success = true)
    {
        $description = $success ? 'Successful login' : 'Failed login attempt';
        return $this->log('login', $description, $userId, $userEmail);
    }

    /**
     * Log logout activity
     */
    public function logLogout($userId, $userEmail)
    {
        return $this->log('logout', 'User logged out', $userId, $userEmail);
    }

    /**
     * Log profile update
     */
    public function logProfileUpdate($userId, $userEmail, $fields = [])
    {
        $description = 'Profile updated';
        if (!empty($fields)) {
            $description .= ': ' . implode(', ', $fields);
        }
        return $this->log('profile_update', $description, $userId, $userEmail);
    }

    /**
     * Log password change
     */
    public function logPasswordChange($userId, $userEmail)
    {
        return $this->log('password_change', 'Password changed', $userId, $userEmail);
    }

    /**
     * Log user creation
     */
    public function logUserCreation($userId, $userEmail, $createdUserId = null)
    {
        $description = 'New user created';
        if ($createdUserId) {
            $description .= ' (ID: ' . $createdUserId . ')';
        }
        return $this->log('user_creation', $description, $userId, $userEmail);
    }

    /**
     * Log report generation
     */
    public function logReportGeneration($userId, $userEmail, $reportType)
    {
        return $this->log('report_generation', 'Generated ' . $reportType . ' report', $userId, $userEmail);
    }
}

