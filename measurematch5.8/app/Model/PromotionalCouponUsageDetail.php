<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PromotionalCouponUsageDetail extends Model
{
    public function promotionalCouponDetail(){
        return $this->belongsTo('App\Model\PromotionalCoupon', 'promotional_coupon_id');
    }
    
    
    public static function isPromotionalCouponApplied($contract_id) {
       return PromotionalCouponUsageDetail::where('contract_id', $contract_id)->with('promotionalCouponDetail')->first();
        
    } 
    public static function updatePromotionalCouponUsageRedeemStatus($contract_id){
       return PromotionalCouponUsageDetail::where('contract_id', $contract_id)->update(['is_redeemed' => TRUE]);
    }
    public static function getFirstPromotionalCouponUsageDetail($contract_id){
       return PromotionalCouponUsageDetail::where('contract_id', $contract_id)->first(); 
    }
    public static function getPromotionCouponCodeCount($promotional_coupon){
        return PromotionalCouponUsageDetail::where('promotional_coupon_id', $promotional_coupon)->count();
    }
    public static function deletePromotionCouponUsageCode($contract_id){
        return PromotionalCouponUsageDetail::where('contract_id', $contract_id)->delete();
    }
    
}
