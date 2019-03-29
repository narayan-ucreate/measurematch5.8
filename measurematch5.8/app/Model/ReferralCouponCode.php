<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ReferralCouponCode extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'referral_coupon_codes';
    protected $fillable = [
         'created_at'
    ];/**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    
   public static function isRefferalCouponCodeAppliedByExpert($user_id,$coupon_code){
      return ReferralCouponCode::where('expert_id', $user_id)->where('coupon_code', $coupon_code)->get();
   } 
    
    
}
