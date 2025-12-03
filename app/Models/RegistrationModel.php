<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Registration Model
 * 
 * Handles school ID eligibility checking and member information retrieval
 * This model is modular and does not modify existing tables
 */
class RegistrationModel extends Model
{
    protected $table = 'gym_members';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';

    /**
     * Check if a school ID is eligible for gym registration
     * 
     * Eligible roles: Faculty, Admin (staff), Athlete
     * 
     * @param string $schoolId The school ID to check
     * @return array ['eligible' => bool, 'user_type' => string|null, 'reason' => string]
     */
    public function checkEligibility($schoolId)
    {
        // ============================================
        // CONFIGURATION: Eligibility Checking
        // ============================================
        // Option 1: Check against a school database/API
        // If you have a school database, query it here to get the member's role
        // 
        // Option 2: Manual verification (current implementation)
        // Admin manually selects the role during registration
        // This allows any school ID to be registered if admin verifies eligibility
        //
        // Option 3: Pattern-based checking
        // Check school ID format/pattern to determine eligibility
        // ============================================

        // For now, we'll allow any school ID to proceed
        // The admin will manually verify and select the correct role
        // You can add your own eligibility logic here
        
        // Example: If you have a school database table
        // $schoolMember = $this->db->table('school_members')
        //                          ->where('school_id', $schoolId)
        //                          ->get()
        //                          ->getRowArray();
        //
        // if (!$schoolMember) {
        //     return [
        //         'eligible' => false,
        //         'user_type' => null,
        //         'reason' => 'School ID not found in school database'
        //     ];
        // }
        //
        // $role = $schoolMember['role'];
        // $eligibleRoles = ['faculty', 'staff', 'athlete'];
        //
        // if (!in_array(strtolower($role), $eligibleRoles)) {
        //     return [
        //         'eligible' => false,
        //         'user_type' => null,
        //         'reason' => 'Role "' . $role . '" is not eligible for gym access'
        //     ];
        // }
        //
        // return [
        //     'eligible' => true,
        //     'user_type' => strtolower($role),
        //     'reason' => 'Eligible'
        // ];

        // Current implementation: Allow all (admin verifies manually)
        return [
            'eligible' => true,
            'user_type' => null, // Admin will select during registration
            'reason' => 'Eligible - Admin verification required'
        ];
    }

    /**
     * Get member information from school database (if available)
     * 
     * This method can be extended to fetch data from your school's database
     * 
     * @param string $schoolId The school ID
     * @return array Member information or empty array
     */
    public function getSchoolMemberInfo($schoolId)
    {
        // ============================================
        // CONFIGURATION: School Database Integration
        // ============================================
        // If you have a school database, query it here to pre-fill the form
        // 
        // Example:
        // $schoolMember = $this->db->table('school_members')
        //                          ->where('school_id', $schoolId)
        //                          ->get()
        //                          ->getRowArray();
        //
        // if ($schoolMember) {
        //     return [
        //         'first_name' => $schoolMember['first_name'],
        //         'middle_name' => $schoolMember['middle_name'] ?? null,
        //         'last_name' => $schoolMember['last_name'],
        //         'email' => $schoolMember['email'] ?? null,
        //         'phone_no' => $schoolMember['phone'] ?? null,
        //         'address' => $schoolMember['address'] ?? null,
        //         'department' => $schoolMember['department'] ?? null,
        //         'user_type' => $this->mapRoleToUserType($schoolMember['role'])
        //     ];
        // }
        // ============================================

        // Return empty array - no pre-fill data available
        // Admin will manually enter the information
        return [];
    }

    /**
     * Map school role to gym user type
     * 
     * @param string $schoolRole The role from school database
     * @return string The corresponding user_type for gym system
     */
    protected function mapRoleToUserType($schoolRole)
    {
        $roleMap = [
            'faculty' => 'faculty',
            'teacher' => 'faculty',
            'professor' => 'faculty',
            'staff' => 'staff',
            'admin' => 'staff',
            'administrator' => 'staff',
            'athlete' => 'athlete',
            'student-athlete' => 'athlete'
        ];

        $schoolRoleLower = strtolower(trim($schoolRole));
        
        return $roleMap[$schoolRoleLower] ?? 'athlete'; // Default to athlete
    }

    /**
     * Check if school ID is already registered
     * 
     * @param string $schoolId The school ID to check
     * @return array|null Member data if found, null otherwise
     */
    public function isAlreadyRegistered($schoolId)
    {
        return $this->find($schoolId);
    }
}
