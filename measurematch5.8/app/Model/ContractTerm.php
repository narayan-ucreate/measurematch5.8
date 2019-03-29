<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ContractTerm extends Model
{
    protected $fillable = [
        'contract_id',
        'term'
    ];
    
    public function deleteTerms($contract_id) {
        return self::where(['contract_id' => $contract_id])->delete();
    }
}
