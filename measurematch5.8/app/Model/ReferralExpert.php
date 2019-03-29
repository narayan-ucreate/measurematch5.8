<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ReferralExpert extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'referral_experts';
    protected $fillable = [ 'expert_id','referral_expert_email','referral_status','created_at','updated_at'
        
    ];/**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    
    private static function updateData($condition, $data_to_update){
        self::where($condition)->update($data_to_update);
    }
}
