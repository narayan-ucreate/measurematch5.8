<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $fillable = [
        'post_job_id', 'contract_id', 'amount', 'application_fee', 'remaining_unpaid_amount', 'due_date', 'is_paid'
    ];
    
    public static function fetchInvoice($contract_id){
        return Invoice::where('contract_id', $contract_id)->first();
    }
}
