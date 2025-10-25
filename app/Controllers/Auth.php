<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function login()
    {
        // Check if user is already logged in
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        
        // Load the login view
        return view('auth/login');
    }
    
    public function authenticate()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        // Here you would typically check against your database
        // For demo purposes, using hardcoded credentials
        if ($email === 'admin@gym.com' && $password === 'admin123') {
            $sessionData = [
                'user_id' => 1,
                'email' => $email,
                'isLoggedIn' => true
            ];
            
            session()->set($sessionData);
            return redirect()->to('/dashboard')->with('success', 'Login successful!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password');
        }
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out successfully');
    }
    
    public function dashboard()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to access dashboard');
        }
        
        return view('auth/dashboard');
    }
}