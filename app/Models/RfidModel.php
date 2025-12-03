<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * RFID Attendance Model
 * 
 * Handles all database operations for RFID attendance records
 * This model is completely independent and does not modify existing tables
 */
class RfidModel extends Model
{
    // ============================================
    // CONFIGURATION: Table and field names
    // ============================================
    // If you need to rename the table or fields, update these constants
    
    protected $table = 'rfid_attendance';
    protected $primaryKey = 'attendance_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    // Allowed fields for insertion/update
    // Adjust these if you add/remove columns from rfid_attendance table
    protected $allowedFields = [
        'member_id',        // Foreign key to gym_members.id
        'scan_time',        // Timestamp of the scan
        // Add more fields here if you extend the table
        // 'location',      // Example: if you track scan location
        // 'device_id',     // Example: if you track which scanner was used
    ];

    // Dates
    protected $useTimestamps = false; // We manually set scan_time
    protected $dateFormat = 'datetime';
    
    // Validation rules
    protected $validationRules = [
        'member_id' => 'required|max_length[50]',
        'scan_time' => 'required|valid_date[Y-m-d H:i:s]',
    ];

    protected $validationMessages = [
        'member_id' => [
            'required' => 'Member ID is required',
            'max_length' => 'Member ID cannot exceed 50 characters'
        ],
        'scan_time' => [
            'required' => 'Scan time is required',
            'valid_date' => 'Invalid scan time format'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get attendance history with member details
     * 
     * @param int $limit Number of records to return
     * @param int $offset Offset for pagination
     * @return array Attendance records with member information
     */
    public function getAttendanceHistory($limit = 50, $offset = 0)
    {
        // Join with gym_members table to get member details
        // ============================================
        // CONFIGURATION: Adjust table/field names if needed
        // ============================================
        return $this->select('rfid_attendance.*, 
                             gym_members.first_name, 
                             gym_members.middle_name, 
                             gym_members.last_name, 
                             gym_members.user_type,
                             gym_members.status')
                    ->join('gym_members', 'gym_members.id = rfid_attendance.member_id', 'left')
                    ->orderBy('rfid_attendance.scan_time', 'DESC')
                    ->limit($limit, $offset)
                    ->findAll();
    }

    /**
     * Get attendance for a specific member
     * 
     * @param string $memberId Member ID
     * @param int $limit Number of records to return
     * @return array Attendance records for the member
     */
    public function getMemberAttendance($memberId, $limit = 30)
    {
        return $this->where('member_id', $memberId)
                   ->orderBy('scan_time', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get attendance statistics for a date range
     * 
     * @param string $startDate Start date (Y-m-d)
     * @param string $endDate End date (Y-m-d)
     * @return array Statistics
     */
    public function getAttendanceStats($startDate, $endDate)
    {
        $builder = $this->db->table($this->table);
        
        $stats = $builder->select('DATE(scan_time) as date, COUNT(*) as count')
                        ->where('DATE(scan_time) >=', $startDate)
                        ->where('DATE(scan_time) <=', $endDate)
                        ->groupBy('DATE(scan_time)')
                        ->orderBy('date', 'ASC')
                        ->get()
                        ->getResultArray();
        
        return $stats;
    }

    /**
     * Check if member has scanned today
     * 
     * @param string $memberId Member ID
     * @return bool|array False if not scanned, array with attendance data if scanned
     */
    public function hasScannedToday($memberId)
    {
        $today = date('Y-m-d');
        return $this->where('member_id', $memberId)
                   ->where('DATE(scan_time)', $today)
                   ->first();
    }
}

