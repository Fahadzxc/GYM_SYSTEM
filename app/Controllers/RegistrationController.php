<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RegistrationModel;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Gym Registration Controller
 * 
 * Handles school ID scanning and member registration
 * This controller is modular and does not interfere with existing functionality
 */
class RegistrationController extends BaseController
{
    /**
     * Load URL helper for base_url() function in views
     */
    protected $helpers = ['url'];

    protected $userModel;
    protected $registrationModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->registrationModel = new RegistrationModel();
    }

    /**
     * Display the registration page
     */
    public function index()
    {
        $data = [
            'title' => 'Gym Registration - School ID'
        ];

        return view('registration', $data);
    }

    /**
     * Check school ID and eligibility
     * 
     * POST /registration/check
     * 
     * Expected POST data:
     * - school_id: The school ID code scanned from the ID card
     * 
     * Returns JSON response:
     * - success: boolean
     * - message: string
     * - already_registered: boolean
     * - member_data: array (if already registered)
     * - eligible: boolean
     * - requires_registration: boolean
     */
    public function check()
    {
        // Only allow POST requests
        if (!$this->request->is('post')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method. Only POST is allowed.'
            ])->setStatusCode(405);
        }

        // Get school ID from POST data
        $schoolId = $this->request->getPost('school_id');

        // Validate school ID
        if (empty($schoolId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'School ID is required.'
            ])->setStatusCode(400);
        }

        // Trim whitespace from school ID
        $schoolId = trim($schoolId);

        // ============================================
        // STEP 1: Check if already registered
        // ============================================
        $existingMember = $this->userModel->find($schoolId);

        if ($existingMember) {
            // Member already exists
            return $this->response->setJSON([
                'success' => true,
                'message' => 'You are already registered.',
                'already_registered' => true,
                'member_data' => [
                    'id' => $existingMember['id'],
                    'name' => trim($existingMember['first_name'] . ' ' . ($existingMember['middle_name'] ?? '') . ' ' . $existingMember['last_name']),
                    'user_type' => $existingMember['user_type'] ?? 'N/A',
                    'status' => $existingMember['status'] ?? 'N/A',
                    'email' => $existingMember['email'] ?? 'N/A',
                    'phone_no' => $existingMember['phone_no'] ?? 'N/A'
                ],
                'eligible' => true,
                'requires_registration' => false
            ]);
        }

        // ============================================
        // STEP 2: Check eligibility
        // ============================================
        // This checks if the school ID belongs to an eligible role
        // Eligible roles: Faculty, Admin (staff), Athlete
        $eligibilityCheck = $this->registrationModel->checkEligibility($schoolId);

        if (!$eligibilityCheck['eligible']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are not allowed to register for gym access. Only Faculty, Admin, and Athletes can register.',
                'eligible' => false,
                'requires_registration' => false,
                'reason' => $eligibilityCheck['reason'] ?? 'Role not eligible'
            ])->setStatusCode(403);
        }

        // ============================================
        // STEP 3: Get member info from school database (if available)
        // ============================================
        // If you have a school database/API, you can fetch member info here
        // For now, we'll return that registration is needed
        $memberInfo = $this->registrationModel->getSchoolMemberInfo($schoolId);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'School ID verified. Please complete registration.',
            'already_registered' => false,
            'eligible' => true,
            'requires_registration' => true,
            'school_id' => $schoolId,
            'member_info' => $memberInfo, // Pre-filled info if available
            'user_type' => $eligibilityCheck['user_type'] ?? null
        ]);
    }

    /**
     * Register a new member
     * 
     * POST /registration/register
     * 
     * Expected POST data:
     * - school_id: The school ID
     * - first_name: First name
     * - middle_name: Middle name (optional)
     * - last_name: Last name
     * - email: Email address (optional)
     * - phone_no: Phone number (optional)
     * - address: Address (optional)
     * - user_type: staff|athlete|faculty
     * - emergency_contact: Emergency contact (optional)
     */
    public function register()
    {
        // Only allow POST requests
        if (!$this->request->is('post')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method. Only POST is allowed.'
            ])->setStatusCode(405);
        }

        // Get form data
        $schoolId = trim($this->request->getPost('school_id') ?? '');
        $userType = $this->request->getPost('user_type') ?? '';

        // Validate required fields
        if (empty($schoolId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'School ID is required.'
            ])->setStatusCode(400);
        }

        // Check if already registered
        $existingMember = $this->userModel->find($schoolId);
        if ($existingMember) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This school ID is already registered.',
                'already_registered' => true
            ])->setStatusCode(409);
        }

        // Check eligibility again
        $eligibilityCheck = $this->registrationModel->checkEligibility($schoolId);
        if (!$eligibilityCheck['eligible']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are not allowed to register for gym access.',
                'eligible' => false
            ])->setStatusCode(403);
        }

        // Prepare member data
        $memberData = [
            'id' => $schoolId,
            'first_name' => $this->request->getPost('first_name') ?? '',
            'middle_name' => $this->request->getPost('middle_name') ?? null,
            'last_name' => $this->request->getPost('last_name') ?? '',
            'email' => $this->request->getPost('email') ?? null,
            'phone_no' => $this->request->getPost('phone_no') ?? null,
            'address' => $this->request->getPost('address') ?? null,
            'user_type' => $userType ?: ($eligibilityCheck['user_type'] ?? 'athlete'),
            'status' => 'active'
        ];

        // Validate and insert
        if (!$this->userModel->insert($memberData)) {
            $errors = $this->userModel->errors();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Registration failed. ' . (!empty($errors) ? implode(', ', $errors) : 'Please check your input.'),
                'errors' => $errors
            ])->setStatusCode(400);
        }

        // Get the newly created member
        $newMember = $this->userModel->find($schoolId);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Registration successful – you can now use the gym!',
            'member_data' => [
                'id' => $newMember['id'],
                'name' => trim($newMember['first_name'] . ' ' . ($newMember['middle_name'] ?? '') . ' ' . $newMember['last_name']),
                'user_type' => $newMember['user_type'],
                'status' => $newMember['status']
            ]
        ]);
    }

    /**
     * Get registration form data (for pre-filling)
     * 
     * GET /registration/form-data?school_id=XXX
     */
    public function getFormData()
    {
        $schoolId = $this->request->getGet('school_id');

        if (empty($schoolId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'School ID is required.'
            ])->setStatusCode(400);
        }

        $memberInfo = $this->registrationModel->getSchoolMemberInfo($schoolId);

        return $this->response->setJSON([
            'success' => true,
            'data' => $memberInfo
        ]);
    }
}

