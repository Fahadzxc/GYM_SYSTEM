<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentsModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'member_id', 'package_name', 'amount_paid', 'status', 'start_date', 'end_date', 'created_at'
    ];

    /**
     * Get latest payment record for a member
     */
    public function getLatestPaymentForMember($memberId)
    {
        try {
            return $this->where('member_id', $memberId)
                        ->orderBy('created_at', 'DESC')
                        ->limit(1)
                        ->first();
        } catch (\Exception $e) {
            // Table may not exist — handle gracefully
            return [];
        }
    }
}
