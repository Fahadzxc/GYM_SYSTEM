<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RfidModel;
use App\Models\PaymentsModel;

class FacultyDashboard extends BaseController
{
    protected $userModel;
    protected $rfidModel;
    protected $paymentsModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->rfidModel = new RfidModel();
        $this->paymentsModel = new PaymentsModel();
    }

    public function index()
    {
        // Require login
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to access the dashboard');
        }

        // Allow only faculty
        $role = session()->get('role');
        $userType = session()->get('user_type');
        if ($role !== 'faculty' && $userType !== 'faculty') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        $memberId = session()->get('user_id');
        // Try to fetch member record
        $member = $this->userModel->find($memberId);
        if (! $member) {
            return redirect()->to('/login')->with('error', 'Member record not found');
        }

        // Attendance summary
        $attendanceRecords = $this->rfidModel->getMemberAttendance($memberId, 1000);
        $totalVisits = count($attendanceRecords);
        $lastVisit = $attendanceRecords ? $attendanceRecords[0]['scan_time'] : null;

        // Monthly attendance (current month)
        $currentMonth = date('Y-m');
        $monthlyCount = 0;
        foreach ($attendanceRecords as $r) {
            if (strpos($r['scan_time'], $currentMonth) === 0) {
                $monthlyCount++;
            }
        }

        // Payment / membership info (latest)
        $payment = $this->paymentsModel->getLatestPaymentForMember($memberId);

        $membership = [
            'package_name' => $payment['package_name'] ?? null,
            'amount_paid' => $payment['amount_paid'] ?? null,
            'payment_status' => $payment['status'] ?? 'unpaid',
            'start_date' => $payment['start_date'] ?? null,
            'end_date' => $payment['end_date'] ?? null,
        ];

        // Notifications
        $notifications = [];
        if (! empty($membership['end_date'])) {
            $end = strtotime($membership['end_date']);
            $daysLeft = ceil(($end - time()) / 86400);
            if ($daysLeft <= 7 && $daysLeft >= 0) {
                $notifications[] = "Your membership will expire in {$daysLeft} day(s).";
            } elseif ($daysLeft < 0) {
                $notifications[] = "Your membership has expired.";
            }
        }

        // Admin announcements - load from a simple table 'announcements' if available
        $db = 
            \Config\Database::connect();
        try {
            $ann = $db->table('announcements')->orderBy('created_at', 'DESC')->limit(5)->get()->getResultArray();
        } catch (\Exception $e) {
            $ann = [];
        }

        // Restriction: access allowed only if membership active and paid
        $hasActiveMembership = (! empty($membership['end_date']) && strtotime($membership['end_date']) >= time() && strtolower($membership['payment_status']) === 'paid');

        $data = [
            'member' => $member,
            'attendance' => [
                'total' => $totalVisits,
                'last_visit' => $lastVisit,
                'monthly' => $monthlyCount
            ],
            'membership' => $membership,
            'notifications' => array_merge($notifications, array_column($ann, 'message')),
            'hasActiveMembership' => $hasActiveMembership
        ];

        return view('members/faculty_dashboard', $data);
    }
}
