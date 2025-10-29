<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ManageUsers extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'members' => $this->userModel->findAll()
        ];
        return view('admin/manage_users', $data);
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

        // Get form data
        $data = [
            'id' => $this->request->getPost('id'),
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

        log_message('info', 'User added successfully: ' . json_encode($data));

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Member added successfully!',
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

        $userId = $this->request->getPost('edit_user_id');
        
        // Get form data
        $data = [
            'id' => $this->request->getPost('edit_id'),
            'first_name' => $this->request->getPost('edit_first_name'),
            'middle_name' => $this->request->getPost('edit_middle_name'),
            'last_name' => $this->request->getPost('edit_last_name'),
            'address' => $this->request->getPost('edit_address'),
            'phone_no' => $this->request->getPost('edit_phone_no'),
            'email' => $this->request->getPost('edit_email'),
            'user_type' => $this->request->getPost('edit_user_type'),
            'status' => 'active'
        ];

        // Validate data for editing
        if (!$this->userModel->validateEdit($data)) {
            log_message('error', 'Validation failed: ' . json_encode($this->userModel->errors()));
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->userModel->errors()
            ]);
        }

        // Update the user
        if (!$this->userModel->update($userId, $data)) {
            log_message('error', 'Update failed');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update member'
            ]);
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
