<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'gym_members';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id',
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'phone_no',
        'email',
        'user_type',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'id' => 'required|is_unique[gym_members.id]',
        'first_name' => 'required|min_length[2]|max_length[50]',
        'last_name' => 'required|min_length[2]|max_length[50]',
        'email' => 'permit_empty|valid_email|is_unique[gym_members.email]',
        'phone_no' => 'permit_empty|min_length[10]|max_length[20]',
        'user_type' => 'required|in_list[staff,athlete,faculty]'
    ];

    // Validation rules for editing (allows same ID and email for current user)
    protected $validationRulesEdit = [
        'id' => 'required',
        'first_name' => 'required|min_length[2]|max_length[50]',
        'last_name' => 'required|min_length[2]|max_length[50]',
        'email' => 'permit_empty|valid_email',
        'phone_no' => 'permit_empty|min_length[10]|max_length[20]',
        'user_type' => 'required|in_list[staff,athlete,faculty]'
    ];

    protected $validationMessages = [
        'id' => [
            'required' => 'ID is required',
            'is_unique' => 'ID already exists'
        ],
        'first_name' => [
            'required' => 'First name is required',
            'min_length' => 'First name must be at least 2 characters',
            'max_length' => 'First name cannot exceed 50 characters'
        ],
        'last_name' => [
            'required' => 'Last name is required',
            'min_length' => 'Last name must be at least 2 characters',
            'max_length' => 'Last name cannot exceed 50 characters'
        ],
        'email' => [
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'Email address already exists'
        ],
        'phone_no' => [
            'min_length' => 'Phone number must be at least 10 characters',
            'max_length' => 'Phone number cannot exceed 20 characters'
        ],
        'user_type' => [
            'required' => 'User type is required',
            'in_list' => 'Please select a valid user type'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    // Private property for edit validation
    private $oldUserId = null;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get all active members
     */
    public function getActiveMembers()
    {
        return $this->where('status', 'active')->findAll();
    }

    /**
     * Get members by type
     */
    public function getMembersByType($type)
    {
        return $this->where('user_type', $type)->findAll();
    }

    /**
     * Search members by name or member ID
     */
    public function searchMembers($searchTerm)
    {
        return $this->groupStart()
            ->like('first_name', $searchTerm)
            ->orLike('last_name', $searchTerm)
            ->orLike('member_id', $searchTerm)
            ->groupEnd()
            ->findAll();
    }

    /**
     * Validate data for editing (allows same ID and email for current user)
     */
    public function validateEdit($data, $oldUserId = null)
    {
        $this->oldUserId = $oldUserId;
        $this->validationRules = $this->validationRulesEdit;
        
        // If ID is changing, check uniqueness
        if ($oldUserId && isset($data['id']) && $data['id'] !== $oldUserId) {
            $existingUser = $this->find($data['id']);
            if ($existingUser) {
                $this->errors['id'] = 'ID already exists';
                return false;
            }
        }
        
        // If email is provided and changing, check uniqueness
        if (!empty($data['email']) && $oldUserId) {
            $existingUser = $this->where('email', $data['email'])->first();
            if ($existingUser && $existingUser['id'] !== $oldUserId) {
                $this->errors['email'] = 'Email address already exists';
                return false;
            }
        }
        
        return $this->validate($data);
    }
}
