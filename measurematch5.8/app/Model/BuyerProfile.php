<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BuyerProfile extends Model {

    protected $table = 'buyer_profile';

    /**
     * Type of organization
     * 
     * @return type
     */
    public function type_of_organisation() {
        return $this->hasOne('App\Model\TypeOfOrganization', 'id', 'type_of_organization_id');
    }

    public static function getPostCompany($company_id) {
       return BuyerProfile::where('id', $company_id)
           ->with(['type_of_organisation' => function($query) {
             return $query->select('name', 'id');
           }])
           ->first(['company_name', 'type_of_organization_id', 'user_id']);
        
    }  
    public static function scopeBuyer($query,$user_id) {
       return $query->where('buyer_profile.user_id', $user_id);
        
    }

    public static function getBuyerDetail($user_id) {
        return BuyerProfile::buyer($user_id)->orderBy('id', 'desc')->first();
    }

    public static function getBuyerInformation($user_id) {
        return BuyerProfile::buyer($user_id)->get()->toArray();
    }

    public static function getBuyerProfile($id) {
        return BuyerProfile::buyer($id);
    }

    public static function getBuyerProfileList($id) {
        return BuyerProfile::buyer($id)->pluck('id')->all();
    }

    public static function countBuyerProfileList($id) {
        return BuyerProfile::buyer($id)->count();
    }

    public static function getColumnFromBuyerProfileTable($user_id, $pluck) {
        return BuyerProfile::buyer($user_id)->pluck($pluck)->toArray();
    }

    public static function getBuyerInformationInObject($user_id) {
        return BuyerProfile::buyer($user_id)->get();
    }

    public static function getBuyerProfileWithJobs($id) {
        return BuyerProfile::join('post_jobs', 'post_jobs.company_id', '=', 'buyer_profile.id')
            ->buyer($id)
            ->get()->toArray();  
    }
    public static function getBuyerProfileWithUserId($id,$publish){
        return BuyerProfile::join('post_jobs', 'post_jobs.user_id', '=', 'buyer_profile.user_id')
            ->buyer($id)
            ->where('post_jobs.publish', $publish)
            ->get()->toArray();
    }
    public static function updateBuyerInformation($id,$update_data){
        return BuyerProfile::where('user_id', $id)->update($update_data);
    }
    public static function updateBuyerProfile($condition,$update_data){
        return BuyerProfile::where($condition)->update($update_data);
    }

    public static function findByCondition($conditions = [], $withs = [], $query_options = []) {
        if (is_array($conditions) && _count($conditions)) {
            $conitions_array = [];
            foreach ($conditions as $key => $val) {
                $conitions_array[$key] = $val;
            }
        }
        $query = BuyerProfile::where($conitions_array);
        if (isset($withs) && !empty($withs)) {
            foreach ($withs as $with) {
                $query = $query->with($with);
            }
        }
        
        if (isset($query_options['order_by'])) {
            $query->orderBy($query_options['order_by'][0], $query_options['order_by'][1]);
        }
        
        return $query->get();
    }
    
    public static function getCompanyNameByBuyerId($buyer_id) {
        return BuyerProfile::select(['company_name','office_location', 'company_url'])->where('user_id', $buyer_id)->first();
        
    }  
    public static function getTypeOfOrganizationByBuyerId($buyer_id) {
        return BuyerProfile::select("type_of_organization_id")->where('user_id', $buyer_id)->first()->type_of_organization_id;
        
    }  
    
    public function buyerNameAndCompany($id)
    {
        return self::select('company_name', 'first_name', 'last_name')->where('user_id', $id)->first();
    }
}
