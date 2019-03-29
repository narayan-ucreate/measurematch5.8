<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TemporaryProposal extends Model
{
    protected $table = 'temporary_proposal';
    protected $fillable = [
        'communication_id',
        'details'
    ];
    
    public function getDetails($communication_id)
    {
        return $this
            ->whereCommunicationId($communication_id)
            ->first();
    }
}
