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

    public function getAllUsers()
    {
        $members = $this->userModel->findAll();
        return $this->response->setJSON([
            'success' => true,
            'data' => $members
        ]);
    }
}
