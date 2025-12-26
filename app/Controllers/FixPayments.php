<?php

namespace App\Controllers;

use App\Models\PaymentsModel;
use Config\Database;

/**
 * Utility controller to fix existing payment records with 0.00 amount
 * Run this once to update existing records
 */
class FixPayments extends BaseController
{
    public function index()
    {
        // Only allow admin access
        if (!session()->get('isLoggedIn') || (session()->get('role') !== 'admin' && session()->get('user_type') !== 'staff')) {
            return redirect()->to('/login')->with('error', 'Unauthorized access');
        }

        $db = Database::connect();
        $paymentsModel = new PaymentsModel();
        
        // Get all payments with 0.00 amount
        $payments = $db->table('payments')
            ->where('amount_paid', 0.00)
            ->orWhere('amount_paid IS NULL')
            ->get()
            ->getResultArray();
        
        $updated = 0;
        $errors = [];
        
        foreach ($payments as $payment) {
            $package = $payment['package_name'];
            $amount = 0;
            
            // Set amount based on package
            if ($package === 'Monthly') {
                $amount = 800;
            } elseif ($package === 'Semester') {
                $amount = 3000;
            } elseif ($package === 'Annual') {
                $amount = 6000;
            }
            
            if ($amount > 0) {
                try {
                    $paymentsModel->update($payment['id'], ['amount_paid' => $amount]);
                    $updated++;
                } catch (\Exception $e) {
                    $errors[] = "Failed to update payment ID {$payment['id']}: " . $e->getMessage();
                }
            }
        }
        
        $message = "Updated {$updated} payment records.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', $errors);
        }
        
        return redirect()->to('/reports')->with('success', $message);
    }
}

