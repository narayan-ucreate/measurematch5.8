<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Mockery\Exception;
use Redirect;
use Session;
use Validator;
use DB;
use Carbon\Carbon;
use Mail;
use App\Model\{
    User,
    BuyerProfile,
    Communication,
    UsersCategory,
    Stripe,
    UsersCommunication,
    Contract,
    BusinessInformation,
    BusinessDetails,
    CountryVatDetails
};
use App\Components\BusinessInformationComponent;
class BuyerAccountController extends Controller {

    /**
     *  Construct Method
     */
    public function __construct() {
        $this->middleware('auth', ['except' => ['BuyerSignUp']]);
    }

    /**
     * Basic info Method
     *
     * @return type
     */
    public function basicinfo()
    {
        $id = Auth::user()->id;
        $user_data = (new User)->buyerProfileWithCategories($id);
        $usercategory = [];

        $user_communication = [];
        if (empty($user_data)) {
            return redirect('buyer/signup-step1')->with('status', __('custom_validation_messages.buyer_profile.signup_step_1'));
        }

        if (!empty($user_data[0]['users_communication'])) {
            $user_communication_arr = $user_data[0]['users_communication'];
            foreach ($user_communication_arr as $user_communication_arr) {
                $user_communication[] = $user_communication_arr['email_subscription'];
            }
        }
        if (!empty($user_data[0]['users_category'])) {
            $usercategory_arr = $user_data[0]['users_category'];
            foreach ($usercategory_arr as $usercategory_arr) {
                $usercategory[] = $usercategory_arr['category_id'];
            }
        }
        $city = '';
        if(!empty($user_data[0]['buyer_profile']['billing_address_city'])){
            $city = ucfirst($user_data[0]['buyer_profile']['billing_address_city']);
        }

        if(!empty($user_data[0]['buyer_profile']['billing_address_country'])){
            $city.= ', '.ucfirst($user_data[0]['buyer_profile']['billing_address_country']);
        }
        $business_information = (new BusinessInformation)->getUserBusinessInformation($id);
        if (_count($business_information)) {
            $business_type = $business_information->type;
            $business_details = $business_information->businessDetails;
            $business_address = $business_information->businessAddress;
        }
        $company_name = $company_website = '';
        if (isset($business_details->company_name))
        {
            $company_name = $business_details->company_name;
            $company_website = $business_details->company_website;
        }
        if (!isset($business_details->company_name)
            && isset($user_data[0]['buyer_profile']['company_name'])
            && array_key_exists(0, $user_data)
            && array_key_exists('buyer_profile', $user_data[0])
            )
        {
            $company_name = $user_data[0]['buyer_profile']['company_name'];
            $company_website = $user_data[0]['buyer_profile']['company_url'];
        }
        $countries =(new CountryVatDetails)->getAllCountryVatDetails();
        $contracts= Contract::findByCondition(['buyer_id'=>$id,'status'=>1,'parent_contract_id'=>null], ['communication.extensionContracts']);
        return view('buyeraccount.basicinfo', compact('user_data',
                                                    'user_communication',
                                                    'city','contracts',
                                                    'countries',
                                                    'business_information',
                                                    'business_type',
                                                    'business_details',
                                                    'company_name',
                                                    'company_website',
                                                    'business_address'));
    }

    /**
     * Email update check Method
     *
     * @param Request $request
     *
     * @return int
     */
    public function emailUpdateCheck(Request $request) {
        if ($request->isMethod('post')) {
            $formData = $request->all();
            $email = strtolower($formData['email']);
            $email_chk = User::where('email', $email)->get()->toArray();
            $id = Auth::user()->id;
            $userdata = User::find($id);
            if (isset($email_chk[0]['email'])) {
                if (trim($email_chk[0]['email']) != trim($userdata->email)) {
                    return 1;
                }
            }
        }
    }

    /**
     * Update Basic info Method
     *
     * @param Request $request
     *
     * @return type
     */
    public function updateBasicInfo(Request $request) {
        $form_data = $request->all();
        $rules = array(
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,'.Auth::user()->id,
            'phone_num' => 'required|regex:/[+,0-9]/'
        );
        $validator = Validator::make($form_data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $id = Auth::user()->id;
            $user = User::find($id);
            $user->name = stripScriptingTagsInline($form_data['first_name']);
            $user->last_name = stripScriptingTagsInline($form_data['last_name']);
            $user->email = stripScriptingTagsInline(strtolower($form_data['email']));
            $user->phone_num = (!empty($form_data['country_code']))? $form_data['country_code'].'-'.$form_data['phone_num'] : $form_data['phone_num'];
            if ($user->save()) {
                $response = BuyerProfile::updateBuyerInformation($id,['first_name' => stripScriptingTagsInline($form_data['first_name']), 'last_name' => stripScriptingTagsInline($form_data['last_name'])]);
                if ($response) {
                    return Redirect::To('/buyer/settings')->with('status', 'Basic information updated.');
                } else {
                    return Redirect::To('/buyer/settings')->with('status', 'Please try again,due to some problem unable to update.');
                }
            }
            else{
                return Redirect::To('/buyer/settings')->with('status', 'Please try again,due to some problem unable to update.');
            }
        }
    }

    /**
     * Address List Method
     *
     * @param Request $request
     *
     * @return string
     */
    public function addresslist(Request $request) {
        $post_code = $request['code'];
        $countryId = $request['countryId'];
        $code = $post_code . "," . $countryId;
        $getaddress = $this->lookup($code);
        $datashow = "";
        if (!empty($getaddress)) {
            $datashow .= '<option value="">Select address</option>';
            foreach ($getaddress as $data) {
                $datashow .= '<option value="' . $data . '">' . $data . '</option>';
            }
        }
        return $datashow;
    }

    /**
     * Lookup Method
     *
     * @param type $string
     *
     * @return type
     */
    function lookup($string) {
        $string = str_replace(" ", "+", urlencode($string));
        $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $string . "&sensor=false";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $details_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);
        // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
        if ($response['status'] != 'OK') {
            return null;
        }
        $address = array();
        if (!empty($response['results'])) {
            foreach ($response['results'] as $res) {
                $address[] = $res['formatted_address'];
            }
        }
        return $address;
    }

    /**
     * Update Business info Method
     *
     * @param Request $request
     *
     * @return type
     */
    public function updateBuisnessInfo(Request $request) {
        $form_data = $request->all();
        $rules = array(
            'post_code' => 'required',
        );
        $validator = Validator::make($form_data, $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $result = array('success' => '0', 'msg' => 'Please try again,due to some problem unable to update.');
        } else {
            $id = Auth::user()->id;
            $bussiness_data=['first_address' => $form_data['first_address'], 'second_address' => $form_data['second_address'], 'city' => $form_data['city_name'], 'country' => $form_data['country_name'], 'post_code' => trim($form_data['post_code'])];
            $response = BuyerProfile::updateBuyerInformation($id, stripScriptingTags($bussiness_data));
            if ($response == true) {
                $result = array('success' => '1', 'msg' => 'Business address updated');

            } else {
                $result = array('success' => '0', 'msg' => 'Please try again,due to some problem unable to update.');
            }
        }
        return $result;
    }

    /**
     * Update Buyer Account Info Method
     *
     * @param Request $postdata
     *
     * @return type
     */
    public function updateBuyerAccountInfo(Request $postdata) {
        $formData = $postdata->all();
        $id = Auth::user()->id;
        $users = User::find($id);
        if (isset($formData['hide_profile']) && !empty($formData['hide_profile'])) {
            $users->hide_profile = 1;
            $response = $users->save();
        } else {
            $users->hide_profile = 0;
            $response = $users->save();
        }
        if (isset($formData['hide_company_name']) && !empty($formData['hide_company_name'])) {
            BuyerProfile::updateBuyerInformation($id,['hide_company_name'=>1]);
        } else {
            BuyerProfile::updateBuyerInformation($id,['hide_company_name'=>0]);
        }
        $result = array('success' => '1', 'msg' => 'Account information updated.');
        return $result;
    }

    /**
     * Update Buyer Password Method
     *
     * @param Request $request
     *
     * @return type
     */
    public function updateBuyerPassword(Request $request) {
        $id = Auth::user()->id;
        $form_data = $request->all();
        $new_password = stripScriptingTagsInline($form_data['new_password']);
        $confirm_password = stripScriptingTagsInline($form_data['confirm_password']);
        if (Auth::attempt(array('email' => Auth::user()->email, 'password' => $request->old_password))) {
            if ($new_password == $confirm_password) {
                $user = User::find($id);
                $user->password = bcrypt($form_data['confirm_password']);
                return $user->save()
                    ? array('success' => '1', 'msg' => 'Your password has been updated.')
                    : array('success' => '0', 'msg' => 'Problem in update password. Please try again.');
            } else {
                return array('success' => '0', 'msg' => 'New password and Confirm password did not match');
            }
        } else {
            return array('success' => '0', 'msg' => 'Wrong Current password.');

        }
    }

    /**
     * Signout Account Method
     *
     * @return type
     */
    public function signoutAccount() {
        //logout after password update
        Auth::logout();
        return Redirect::To('/')->with('message', 'Password changed. Please login again!!!');
    }


    /**
     * Update Buyer Communication Method
     *
     * Function to save Records for Buyer communication (By Rahul)
     *
     * @param Request $request
     * @return type
     */
    public function updateBuyerCommunication(Request $request) {
        $id = Auth::user()->id;
        $formData = $request->all();
        if (!empty($formData)) {
            if (!empty($formData['user_communication'])) {
                $email_subscription = '1';
            } else {
                $email_subscription = '0';
            }
            $created = Carbon::now();
            $userComm = UsersCommunication::where('user_id', $id)->get();

            if (!empty($userComm[0]->id)) {
                $userCommId = $userComm[0]->id;
                $usrComm = UsersCommunication::find($userCommId);
                $usrComm->email_subscription = $email_subscription;
                return $usrComm->save()
                    ? array('success' => '1', 'msg' => 'Communications details updated.')
                    : array('success' => '0', 'msg' => 'Communications details not updated. Please try again.');
            } else {
                return array('success' => '0', 'msg' => 'Communications details not updated. User not found.');

            }
        } else {
            return array('success' => '0', 'msg' => 'Communications details not updated. Please try again.');

        }
    }

    public function saveBusinessAddress(Request $request, $id)
    {   
        $form_data = $request->all();
        $form_data['business_type'] = config('constants.REGISTERD_COMPANY');
        $messages = [
            'first_address.required' => __('custom_validation_messages.business_information.first_address'),
            'business_city.required' => __('custom_validation_messages.business_information.business_city'),
            'business_state.required' => __('custom_validation_messages.business_information.business_state'),
            'business_country.required' => __('custom_validation_messages.business_information.business_country'),
            'business_postal_code.required' => __('custom_validation_messages.business_information.business_postal_code'),
        ];
        $rules = array(
            'first_address' => 'required',
            'business_city' => 'required',
            'business_state' => 'required',
            'business_postal_code' => 'required',
            'business_country' => 'required',
            'stay_safe_confirm' => 'required',
            'code_of_conduct' => 'required',
        );
        $validator = Validator::make($form_data, $rules, $messages);
        if ($validator->fails()) {
            return \Response::json($validator->errors(), 422);
        }
        $business_information = ((new BusinessInformation())->getUserBusinessInformation($id)) ?? 
            (new BusinessInformationComponent)->storeBusinessInformation(Auth::user()->id, config('constants.REGISTERD_COMPANY'));
        (new BusinessInformationComponent)->saveBusinessAddress($form_data, $business_information);
        updateUserSetting(['business_address_pop_up' => config('constants.TRUE')]);
        $stay_safe_confirm = Communication::updateStaySafeStatus($request->communication_id);
        if ($stay_safe_confirm) {
            return ['success' => 1, 'user_id' => $id];
        }
        return ['success' => 0];
    }
}
