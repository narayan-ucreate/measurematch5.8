<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PromotionalCoupon extends Model
{
    public static function incrementPromotionalCoupon($promotional_coupon_id){
         return PromotionalCoupon::where('id', $promotional_coupon_id)->increment('number_of_times_redeemed', 1);
    }
    public static function getFirstPromotionalCoupon($coupon_code_applied){
        return PromotionalCoupon::where('coupon_code', $coupon_code_applied)->first();
    }
}
