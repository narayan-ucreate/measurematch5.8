<?php

namespace App\Http\Controllers;

use App\Model\CountryVatDetails;
use Illuminate\Http\Request;
use Authenticatable,
    CanResetPassword;
use App\Http\Requests;
use Auth;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Redirect;
use App\Model\User;
use Session;
use Validator;
use DB;
use App\Model\Language;
use App\Model\UserType;
use App\Model\UserProfile;
use App\Model\Skill;
use App\Model\UsersLanguage;
use Storage;
use App\Model\UsersSkill;
use App\Model\EmploymentDetail;
use App\Model\EducationDetail;
use App\Model\UsersCertification;
use App\Model\PostJob;
use App\Model\BuyerProfile;
use App\Model\Communication;
use App\Model\UsersCategory;
use App\Model\Stripe;
use Stripe\Error\Card;
use Carbon\Carbon;
use App\Model\UsersCommunication;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Model\Payment;
use App\Model\ServicePackage;
use App\Model\BusinessInformation;
use App\Model\BusinessDetails;
use App\Model\BusinessAddress;
use App\Components\BusinessInformationComponent;

class SellerAccountController extends Controller {

    /**
     * Construct Method
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Update Seller Account Info Method
     * 
     * @param Request $postdata
     * 
     * @return type
     */
    public function updateSellerAccountInfo(Request $postdata) {
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

        if (isset($formData['category']) && !empty($formData['category'])) {
            UsersCategory::where('user_id', $id)->delete();
            $exlode_category = $formData['category'];
            for ($i = 0; $i < sizeof($exlode_category); $i++) {
                $checkcategory = DB::table('categories')->where('id', trim($exlode_category[$i]))->get();
                $created = Carbon::now();
                if (!empty($checkcategory)) {

                    $categoryId = $checkcategory[0]->id;
                    $arr = array(
                        'category_id' => $categoryId,
                        'user_id' => $id,
                        'created_at' => $created
                    );
                    $categoryinsert = UsersCategory::create($arr);
                }
            }
        }

        return 1;
    }

    /**
     * Update Expert Password Method
     * 
     * @param Request $request
     * 
     * @return type
     */
    public function updateExpertPassword(Request $request) {
        $id = Auth::user()->id;
        $form_data = $request->all();
        $new_password = stripScriptingTagsInline($form_data['new_password']);
        $confirm_password = stripScriptingTagsInline($form_data['confirm_password']);

        if (Auth::attempt(array('email' => Auth::user()->email, 'password' => stripScriptingTagsInline($request->old_password)))) {
            if ($new_password == $confirm_password) {
                $user = User::find($id);
                $user->password = stripScriptingTagsInline(bcrypt($form_data['confirm_password']));
                $result = $user->save();
                if ($result) {
                    $response['status'] = config('constants.APPROVED');
                    $response['msg'] = 'Your password has been updated.';
                } else {
                    $response['status'] = config('constants.NOT');
                    $response['msg'] = 'Problem in update password. Please try again.';
                }
            } else {
                $response['status'] = config('constants.NOT');
                $response['msg'] = 'New password and Confirm password did not match.';
            }
        } else {
            $response['status'] = config('constants.NOT');
            $response['msg'] = 'Wrong password.';
        }
        return $response;
    }

   public function signoutAccount() {
        Auth::logout();
        return Redirect::To('/')->with('message', 'Password changed. Please login again!!!');
    }

    public function checkUserPassword(Request $request) {
        if (Auth::attempt(array('email' => Auth::user()->email, 'password' => $request->old_password))) {
            return Auth::user();
        } else {
            echo "1";
        }
        die;
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
     * Update BusIness info Method
     * 
     * @param Request $request
     * 
     * @return type
     */
    public function updateBuisnessInfo(Request $request) {
        $form_data = $request->all();
        if (!empty($form_data)) {
            $validator = $this->validateExpertSettings($form_data);
            if ($validator->fails()) {
                return \Response::json($validator->errors(), 422);
            }
            $id = Auth::user()->id;
            $business_information = (new BusinessInformation())->getUserBusinessInformation($id);
            if (empty($business_information)) {
                $business_information = new BusinessInformation();
                $this->saveBusinessType($form_data, $business_information, $id);
            };
            if ($form_data['business_type'] == config('constants.SOLE_TRADER')) {
                if (!empty($business_information->business_detail_id)) {
                    $business_information->business_detail_id = null;
                    $this->saveBusinessType($form_data, $business_information, $id);
                    $business_information->businessDetails->delete();
                }
                return (new BusinessInformationComponent)->saveBusinessAddress($form_data, $business_information, true);
            }
            return $this->saveBusinessDetails($form_data, $business_information);
        }
    }

    private function saveBusinessType($user_data, $business_information, $id) {
        $business_information->type = $user_data['business_type'];
        $business_information->user_id = $id;
        $business_information->save();
        return $this->resultJson(true, config('constants.DETAILS_SAVED'));
    }

    private function saveBusinessDetails($user_data, $business_information) {
        $business_details = $business_information->businessDetails;
        if (empty($business_details)) {
            $business_details = new BusinessDetails;
        }
        $id = Auth::user()->id;
        $have_vat_number = $user_data['vat_status'] ?? false;
        $vat_country_code = (new CountryVatDetails)->getCodeByCountry($user_data['company_country'])->country_code ?? '';
        $is_eu = (!empty($user_data['is_eu'])) ? $user_data['is_eu'] : ((!empty($vat_country_code)) ? getCountryVatDetails($vat_country_code)['is_eu_country'] : '');
        $company_country = $user_data['company_country'];
        if ($have_vat_number) {
            $vat_number = $user_data['vat_number'] ?? '';
            if(!empty($vat_number))
            {
                $validate_vat_number = $have_vat_number ? (( $is_eu ) ? getVatApiResponse('validate?vat_number=' . $vat_country_code . $vat_number) 
                    : ['valid' => true] ) : ['valid' => false];
                if ($validate_vat_number['valid'] == false) {
                    $validator = Validator::make($user_data, []);
                    $validator->errors()->add('vat_number', 'Please input a valid VAT number.');
                    return \Response::json($validator->errors(), 422);
                }
            }
        }
        $business_details->company_name = $user_data['company_name'];
        $business_details->company_website = $user_data['company_website'];
        $business_details->role = $user_data['company_role'];
        $business_details->vat_status = $have_vat_number;
        if (!(new CountryVatDetails)->getCountryByCode($vat_country_code)->vat){
            $business_details->vat_status = false;
        }
        $business_details->vat_country = $vat_country_code;
        $business_details->company_country = $company_country;
        $business_details->vat_number = $vat_number;
        $business_details->save();
        $business_information_updated = $business_information->updateBusinessInformation(Auth::user()->id, 
            ['business_detail_id' => $business_details->id, 'type' => $user_data['business_type']]);
        (new BusinessInformationComponent)->saveBusinessAddress($user_data, $business_information);
        return response()->json(['successful' => true ,'msg' => config('constants.DETAILS_SAVED'), 'business_type' => config('constants.REGISTERD_COMPANY')]);
    }

    private function resultJson($success, $msg) {
       return response()->json(['successful' => $success,
            'msg' => $msg
        ]);
    }
    private function validateExpertSettings($form_data) {
        $messages = [
            'business_type.required' => __('custom_validation_messages.business_information.business_type'),
            'company_name.required_if' => __('custom_validation_messages.business_information.company_name'),
            'company_website.required_if' => __('custom_validation_messages.business_information.company_website'),
            'company_website.regex' => __('custom_validation_messages.business_information.company_website_url'),
            'company_role.required_if' => __('custom_validation_messages.business_information.company_role'),
            'company_country.required' => __('custom_validation_messages.business_information.company_country'),
            'company_country.in_array' => __('custom_validation_messages.business_information.company_country'),
            'first_address.required' => __('custom_validation_messages.business_information.first_address'),
            'business_city.required' => __('custom_validation_messages.business_information.business_city'),
            'business_state.required' => __('custom_validation_messages.business_information.business_state'),
            'business_country.required' => __('custom_validation_messages.business_information.business_country'),
            'business_postal_code.required' => __('custom_validation_messages.business_information.business_postal_code'),
            'vat_country.required_if' => __('custom_validation_messages.business_information.vat_country'),
            'business_registered_country.required_if' => __('custom_validation_messages.business_information.vat_country'),
            'vat_number.required_if' => __('custom_validation_messages.business_information.vat_number'),
        ];
        $country_list = (new CountryVatDetails)->getCountryNameArray();
        $form_data['country_list'] = array($country_list);
        $rules = array(
            'business_type' => 'required',
            'company_name' => 'required_if:business_type,1',
            'company_website' => [
                'required_if:business_type,1',
                'regex:/^http:\/\/|(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/'
                ],
            'first_address' => 'required',
            'business_city' => 'required',
            'business_state' => 'required',
            'business_postal_code' => 'required',
            'business_country' => 'required',
            'vat_country' => 'required_if:business_type,1',
            'business_registered_country' => 'required_if:business_type,2',
            'company_country' => 'required_if:business_type,1|in_array:country_list.*'
        );
        if(Auth::user()->user_type_id == config('constants.EXPERT'))
        {
            $rules['vat_number'] = 'required_if:vat_status,on';
            $rules['company_role'] = 'required_if:business_type,1';
        }
        return Validator::make($form_data, $rules, $messages);
     }

    /**
     * Update Seller Communication Method
     * 
     * @param Request $request
     * 
     * Function to save Records for seller communication
     * 
     * @return type
     */
    public function updatesellerCommunication(Request $request) {
        $id = Auth::user()->id;
        $form_data = $request->all();

        if (!empty($form_data)) {

            if (!empty($form_data['user_communication'])) {
                $email_subscription = '1';
            } else {
                $email_subscription = '0';
            }
            $created = Carbon::now();
            $user_communication = UsersCommunication::where('user_id', $id)->get();

            if (!empty($user_communication[0]->id)) {
                $user_communication_id = $user_communication[0]->id;
                $usr_communication_detail = UsersCommunication::find($user_communication_id);
                $usr_communication_detail->email_subscription = $email_subscription;
                if ($usr_communication_detail->save()) {
                    $response['status'] = config('constants.APPROVED');
                    ;
                    $response['msg'] = 'Communications details updated.';
                } else {
                    $response['status'] = config('constants.NOT');
                    $response['msg'] = 'Account not updated. Please try again.';
                }
            } else {
                $response['status'] = config('constants.NOT');
                $response['msg'] = 'Account not updated. User not found.';
            }
        } else {
            $response['status'] = config('constants.NOT');
            $response['msg'] = 'Account not updated. Please try again.';
        }
        return $response;
    }
}
