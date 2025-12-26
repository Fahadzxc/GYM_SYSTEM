<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table = 'activity_logs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false; // We're manually setting created_at
    protected $createdField = 'created_at';
    protected $updatedField = null;
    
    protected $allowedFields = [
        'user_id',
        'user_email',
        'activity_type',
        'activity_description',
        'ip_address',
        'user_agent',
        'created_at'
    ];

    /**
     * Log an activity
     */
    public function logActivity($activityType, $description, $userId = null, $userEmail = null)
    {
        $request = \Config\Services::request();
        
        $data = [
            'user_id' => $userId ?? session()->get('user_id'),
            'user_email' => $userEmail ?? session()->get('email'),
            'activity_type' => $activityType,
            'activity_description' => $description,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString(),
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->insert($data);
    }

    /**
     * Get user's recent activities
     */
    public function getUserActivities($userId, $limit = 10)
    {
        try {
            return $this->where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->limit($limit)
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'getUserActivities failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all activities with pagination
     */
    public function getAllActivities($limit = 50, $offset = 0)
    {
        return $this->orderBy('created_at', 'DESC')
            ->limit($limit, $offset)
            ->findAll();
    }
}

