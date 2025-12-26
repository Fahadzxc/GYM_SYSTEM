<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminUserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $allowedFields = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'phone_no',
        'role',
        'status',
        'profile_picture',
        'verified',
        'last_login'
    ];

    protected $validationRules = [
        'first_name' => 'required|min_length[2]|max_length[50]',
        'last_name' => 'required|min_length[2]|max_length[50]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'permit_empty|min_length[6]',
    ];

    protected $validationMessages = [
        'first_name' => [
            'required' => 'First name is required',
            'min_length' => 'First name must be at least 2 characters',
        ],
        'last_name' => [
            'required' => 'Last name is required',
            'min_length' => 'Last name must be at least 2 characters',
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'Email address already exists',
        ],
        'password' => [
            'min_length' => 'Password must be at least 6 characters',
        ],
    ];

    /**
     * Get user by ID
     */
    public function getUserById($id)
    {
        return $this->find($id);
    }

    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Update last login
     */
    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }
}

