<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BusinessInformation extends Model
{
    protected $table = 'business_informations';

    public function businessDetails() {
        return $this->hasOne('App\Model\BusinessDetails', 'id', 'business_detail_id');
    }

    public function businessAddress() {
        return $this->hasOne('App\Model\BusinessAddress', 'id', 'business_address_id');
    }

    public function getUserBusinessInformation($user_id) {
        return $this->whereUserId($user_id)->with(['businessDetails', 'businessAddress'])->first();
    }
    
    public function updateBusinessInformation($user_id, $data) {
        return self::where('user_id', $user_id)->update($data);
    }
    
    public function getUserBusinessDetailId($user_id) {
        return $this->whereUserId($user_id)->value('business_detail_id');
    }
    
    public function getUserBusinessAddressId($user_id) {
        return $this->whereUserId($user_id)->value('business_address_id');
    }
}
