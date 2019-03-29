<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CouponAppliedByExpert extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'coupon_applied_by_experts';
    protected $fillable = [
        'created_at'
    ];/**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public static function isRefferalCouponAppliedByExpert($expertId, $contract_id) {
        return CouponAppliedByExpert::where('expert_id', $expertId)->where('contract_id', $contract_id)->get()->toArray();
    }

    public static function findByCondition($conditions = [], $withs = [], $query_options = []) {
        if (is_array($conditions) && _count($conditions)) {
            $conitions_array = [];
            foreach ($conditions as $key => $val) {
                $conitions_array[$key] = $val;
            }
        }
        $query = CouponAppliedByExpert::where($conitions_array);
        if (isset($withs) && !empty($withs)) {
            foreach ($withs as $with) {
                $query = $query->with($with);
            }
        }
        return $query->get();
    }

}
