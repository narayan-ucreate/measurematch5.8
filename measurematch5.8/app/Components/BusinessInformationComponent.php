<?php

namespace App\Components;

use Auth;
use App\Model\{BusinessAddress, BusinessInformation};
Class BusinessInformationComponent {

    public function saveBusinessAddress($user_data, $business_information, $sole_trader = false)
    {
        $business_address = $business_information->businessAddress;
        if (empty($business_address))
        {
            $business_address = new BusinessAddress;
        }
        $business_address->first_address = $user_data['first_address'];
        $business_address->second_address = $user_data['second_address'];
        $business_address->city = $user_data['business_city'];
        $business_address->state = $user_data['business_state'];
        $business_address->postal_code = $user_data['business_postal_code'];
        $business_address->country = $user_data['business_country'];
        if(array_key_exists('business_registered_country', $user_data))
            $business_address->business_registered_country = $user_data['business_registered_country'];
        $business_address->save();
        $business_information->updateBusinessInformation(Auth::user()->id,
            ['business_address_id' => $business_address->id, 'type' => $user_data['business_type']]);
        $response = ['successful' => true, 'msg' => config('constants.DETAILS_SAVED')];
        if($sole_trader == true)
            $response['business_type'] = config('constants.SOLE_TRADER');
        return response()->json($response);
    }
    
    public function storeBusinessInformation ($user_id, $type){
        $business_information = new BusinessInformation();
        $business_information->user_id = $user_id;
        $business_information->type = $type;
        $business_information->save();
        return $business_information;
    }
}
