<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Redirect;
use Session;
use Validator;
use DB;
use Carbon\Carbon;
use Mail;
use Newsletter;
use App\Model\{
    User,
    UserProfile,
    Language,
    UsersLanguage,
    UsersCommunication,
    ReferralExpert,
    ReferralCouponCode,
    PostJob,
    ServicePackage,
    BuyerProfile,
    InvalidEmailDomain,
    BusinessInformation,
    BusinessAddress,
    BusinessDetails,
    CountryVatDetails,
    ServiceHubAssociatedExpert,
    VendorInvitedExpert
};
use App\Components\
{
    Email,
    PostProjectComponent,
    LoginComponent,
    SegmentComponent,
    BusinessInformationComponent,
    ClearbitComponent
};

class UsersController extends Controller {

    public function signout() {
        Auth::logout();
        return Redirect::To('/')->with('message', 'You are successfully logged out!!!');
    }

    public function thankyou() {
        return view('pages.thankyou');
    }

    public function home() {
        if (Auth::check()) {
            $userType = Auth::user()->user_type_id;
            switch ($userType) {
                Case "1":
                    $link = Session::get('referral_link');
                    Session::forget('referral_link');
                    if (isset($link) && !empty($link) && (trim($link) == trim(Auth::user()->email))) {
                        return Redirect::To('referExpertLink?user=' . urlencode(base64_encode($link)));
                    } else {
                        session()->put('first_login', 1);
                        return Redirect::To('/expert/profile-summary');
                    }
                Case "2":
                    $number_of_projects_posted = PostJob::findByCondition(['user_id' => Auth::user()->id], [], ['type' => 'count']);
                    $projects_pending = PostJob::findByCondition(['user_id' => Auth::user()->id, 'publish'=> config('constants.PROJECT_PENDING')]);
                    if (!$number_of_projects_posted) {
                        return redirect('/myprojects?welcome=true');
                    }
                    if ($number_of_projects_posted == 1 && _count($projects_pending))
                        return Redirect::To('/buyer/messages/project/' . $projects_pending[0]->id);
                    return Redirect::To('/myprojects');
                Case "3":
                    return Redirect::To('/admin/buyerListing');
                Case "4":
                    return Redirect::To(route('service-hubs'));
                default:
                    redirectToWebFlow(config('constants.STATIC_PAGES_DOMAIN'));
                    return view('landingpages.welcome');
            }
        } else {
            LoginComponent::removeCookie('project_from_home');
            LoginComponent::clearAllCookiesAfterlogout();
            redirectToWebFlow(config('constants.STATIC_PAGES_DOMAIN'));
            return view('landingpages.welcome');
        }
    }

    public function create(Request $request) {
        Session::put('provider', 'email');
        $checkemail = $_REQUEST['email'];

        $checkuser = DB::table('users')
            ->where('email', '=', strtolower($checkemail))
            ->get();

        if (!empty($checkuser)) {

            echo 1;
            die;
        } else {

            $user_data = User::create([
                'name' => $request['name'],
                'email' => strtolower($request['email']),
                'password' => bcrypt($request['password']),
                'user_type_id' => config('constants.EXPERT'),
            ]);
            $user_id = $user_data->id;
            $profiledata = new UserProfile;
            $profiledata->user_id = $user_id;
            $profiledata->save();
            echo 2;
            die;
        }
    }

    function sendExpertCouponCodeMail($coupon_code, $referral_expert_id) {
        $ssl = getenv('APP_SSL');
        $referral_information = userInfo($referral_expert_id);
        $expiry_date = '12 noon on ' . date('d M Y', strtotime('+90 days'));
        $email = $referral_information[0]->email;
        $expert_name = $referral_information[0]->name . ' ' . $referral_information[0]->last_name;
        $referral_link = url('/referExpertLink?user=' . urlencode(base64_encode($email)), [], $ssl);
        $data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $email,
            'template_data' => [
                'expertName' => $expert_name,
                'expertEmail' => $email,
                'coupon_code' => $coupon_code,
                'expiry_date' => $expiry_date,
                'url' => getHomeUrl(),
                'headOfficeMapLink' => getOfficeMessageLink(),
                'headerLogoUrl' => getLogoUrl(),
                'homeUrl' => getHomeUrl(),
                'faqUrl' => getFaqUrl(),
                'pplink' => getPrivacyPolicyLink(),
                'tnclink' => getTermConditionsLink(),
                'unsubscribeLink' => getUnsubscribeUrl($email),
                'referral_link' => $referral_link,
                'userEmail' => $email
            ]
        ];
        Email::sendExpertCouponCodeMail($data);
        return true;
    }

    public function emailCheck(Request $request) {
        if ($request->isMethod('post')) {
            $form_data = $request->all();
            $email = strtolower($form_data['email']);
            $count = User::where('email', $email)->count();
            if ($count > 0) {
                return 1;
            }
        }
    }

    public function emailUpdateCheck(Request $request) {
        if ($request->isMethod('post')) {
            $form_data = $request->all();
            $email = strtolower($form_data['email']);
            $check_email = User::getUserInformationWithEmail($email);
            $id = Auth::user()->id;
            $user_data = User::find($id);
            if (!empty($check_email[0]['email']) && (_count($check_email) > 0)) {
                if (trim($check_email[0]['email']) == trim($user_data->email)) {
                    return 0;
                } else {
                    return 1;
                }
            }
        }
    }

    function updateStatus(Request $request) {
        $form_status = $request->all();
        $check_email = User::getUserInformationWithEmail(trim(strtolower($form_status['email'])));
        if ((_count($check_email) > 0) && (!empty($check_email[0]['status'])) && ($check_email[0]['status'] == 1)) {
            return redirect('login')->withErrors([
                'success' => 'Account already verified. Please login!',
            ]);
        } else {
            try {
                if (isset($check_email) && !empty($check_email)) {
                    $user_status = config('constants.APPROVED');
                    $user_profile = UserProfile::getUserProfile($check_email[0]['id']);
                    $expert_type = '';
                    if (!empty($user_profile) && $user_profile[0]['expert_type'] === config('constants.EXPERT_TYPE_SIDE_HUSTLER')){
                        $user_status = config('constants.SIDE_HUSTLER');
                        $expert_type = config('constants.EXPERT_TYPE_SIDE_HUSTLER');
                    }
                    $response = User::updateUser($check_email[0]['id'],
                        [
                            'access_token'=> $form_status['access_token'],
                            'status' =>  $user_status,
                            'verified_status' =>  config('constants.APPROVED')
                        ]);
                    if ($response) {
                        if ($expert_type != config('constants.EXPERT_TYPE_SIDE_HUSTLER')){
                            $response = $this->sendWelcomeEmailToUsers($check_email);
                        }
                        $user = User::getUserByEmail(trim(strtolower($form_status['email'])));
                        (new SegmentComponent)->accountTracking($check_email[0]['id'], $check_email[0]['user_type_id'], config('constants.SEGMENT_EVENT.2'));
                        Auth::login($user);
                        return redirect('/');
                    } else {
                        return redirect('login')->withErrors([
                            'error' => config('constants.CREDENTIAL_DONOT_MATCH'),
                        ]);
                    }
                } else {
                    return redirect('login')->withErrors([
                        'error' => 'User donot exist any more',
                    ]);
                }
            } catch (\Exception $e) {
                return redirect('login')->withErrors([
                    'error' => config('constants.CREDENTIAL_DONOT_MATCH'),
                ]);
            }
        }
    }
    
    private function sendWelcomeEmailToUsers($check_email)
    {
        $email_componenet = new Email();
        if ($check_email[0]['user_type_id'] == config('constants.EXPERT'))
            return $email_componenet->sendExpertWelcomeMail(['expert_id'=>$check_email[0]['id']]);
        
        if ($check_email[0]['user_type_id'] == config('constants.VENDOR')) {
            $email_componenet->buyerAccountForAdminReview($check_email[0]['id']);
            return $email_componenet->sendVendorWelcomeMail($check_email[0]['id']);
        }

        $project_posted_from_home = json_decode($check_email[0]['settings'], 1);
        if(!empty($project_posted_from_home)){
            $email_componenet->waitingProjectApprovalEmail(['buyer_id' =>$check_email[0]['id'] , 'project_id' => $project_posted_from_home['posted_from_home_project_id']]);
            (new SegmentComponent)
                ->accountTracking($check_email[0]['id'], config('constants.BUYER'), config('constants.SEGMENT_EVENT.1'), Carbon::now());
            User::updateUser($check_email[0]['id'], ['settings' =>json_encode([])]);
            return $email_componenet->buyerApprovalEmail($check_email[0]['id']);
        }
        $email_componenet->sendBuyerWelcomeMail($check_email[0]['id']);
        return $email_componenet->buyerAccountForAdminReview($check_email[0]['id']);
    }

    public function userCheckExist(Request $request) {
        $form_data = $request->all();
        $email = strtolower($form_data['email']);
        $check_email = User::where('email', $email)->get()->toArray();
        if (!empty($check_email[0]['email']) && (_count($check_email) > 0)) {
            return 0;
        } else {
            return 1;
        }
    }



    public function unsubscribe() {
        return view('pages.unsubscribe');
    }

    public function unsubscribeEmail(Request $request) {
        $form_data = $request->all();
        $user_email = base64_decode(urldecode($form_data['user']));
        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            return Redirect::To('/unsubscribe')->with('warning', 'Could not find any account associated with this email!');
        }
        $user_detail = User::where('email', $user_email)->get();

        if (!empty($user_detail) && _count($user_detail)) {
            $user_id = $user_detail[0]->id;
            $user_communication = UsersCommunication::where('user_id', $user_id)->get();
            if (isset($user_communication[0]->id) && !empty($user_communication[0]->id)) {
                $user_communication_id = $user_communication[0]->id;
                $user_communication_information = UsersCommunication::find($user_communication_id);
                $user_communication_information->email_subscription = '0';
                if ($user_communication_information->save()) {
                    return Redirect::To('/unsubscribe');
                } else {
                    return Redirect::To('/unsubscribe')->with('warning', 'Account not updated. Please try again.');
                }
            } else {
                return Redirect::To('/unsubscribe')->with('warning', 'Account not updated. Please try again.');
            }
        } else {
            return Redirect::To('/unsubscribe')->with('warning', 'Could not find any account associated with this email!');
        }
    }

    public function contactEmail(Request $request) {
        $form_data = $request->all();
        if(array_key_exists('name', $form_data) && array_key_exists('email', $form_data) && array_key_exists('msg', $form_data)){
            Email::sendContactUsEmailToAdmin(['first_name'=> $form_data['name'], 'email'=> $form_data['email'], 'message'=> $form_data['msg']]);
            return Redirect::to('contact-us#contactuspanel')->with('success', 'Thanks for getting in touch. We will get back to you right away.');
        }
        return back();
    }

    public function getLocationDetails(Request $request) {
        $form_data = $request->all();
        if (isset($form_data) && !empty($form_data) && array_key_exists('location', $form_data)) {
            $place = trim($form_data['location']);
        } else {
            $place = '';
        }
        $google_api_key = getenv('GOOGLE_API_KEY_FOR_LOCATION_AUTOCOMPLETE');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => getenv('GOOGLE_LOCATION_URL')."$place&types=(cities)&key=$google_api_key",
            CURLOPT_RETURNTRANSFER => true,
        ));
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        if ($error) {
            echo $error;
        } else {
            $json_content = json_decode($response, true);

            $final_result = array();
            $counter=0;
            if (!empty($json_content)) {
                foreach ($json_content['predictions'] as $value) {
                    $final_result[$counter]["description"] = $value['description'];
                    $country=end($value['terms'])['value'];
                    $final_result[$counter]["city"]= substr($value['description'],0,-strlen($country)-2);
                    $final_result[$counter]["country"]=$country ;

                    $counter++;
                }
                return $final_result;
            }
        }
    }

    public function referExpertLink(Request $request) {
        $form_data = $request->all();
        if (Auth::check()) {
            if (isset($form_data['user']) && !empty($form_data['user'])) {
                Session::put('referral_link', urldecode(base64_decode($form_data['user'])));
                $email_in_session = Session::get('referral_link');
                $email = Auth::user()->email;
                Session::forget('referral_link');
                if ($email_in_session == $email) {
                    return view('user_profile.refer_expert');
                } else {
                    return redirect('/');
                }
            } else {
                return redirect('/');
            }
        } else {
            Session::put('referral_link', urldecode(base64_decode($form_data['user'])));
            return redirect('login');
        }
    }

    public function referralLink(Request $request) {
        $form_data = $request->all();
        $referral_email = urldecode(base64_decode($form_data['email']));
        $expert_id = urldecode(base64_decode($form_data['expert_id']));
        $response = ReferralExpert::where('expert_id', $expert_id)
            ->where('referral_expert_email', $referral_email)
            ->orderBy('referral_experts.id', 'asc')->first();
        if (isset($response) && !empty($response) && ($response['referral_status'] == 0)) {
            Session::put('referral_expert_id', $response['id']);
            Session::put('referral_expert_email_id', $response['referral_expert_email']);
            Session::put('referral_expert_unique_id', $response['expert_id']);
            return redirect('signup');
        } else {
            Session::forget('referral_expert_id');
            Session::forget('referral_expert_email_id');
            Session::forget('referral_expert_unique_id');
            return redirect('/');
        }
    }

    public function generateNewCouponcode($length = 10) {
        $alphabets = range('A', 'Z');
        $numbers = range('0', '9');
        $additional_characters = array('$', '.');
        $final_array = array_merge($alphabets, $numbers, $additional_characters);
        $password = '';
        while ($length--) {
            $key = array_rand($final_array);
            $password .= $final_array[$key];
        }
        $response = ReferralCouponCode::where('coupon_code', $password)->first();
        if (!empty($response)) {
            $this->generateNewCouponcode();
        }

        return $password;
    }

    public function removeSessionValues(Request $request) {
        Session::forget('referral_expert_id');
        Session::forget('referral_expert_email_id');
        Session::forget('referral_expert_unique_id');
        return 1;
    }

    public function actionSignupView(Request $request) {
        $user_type = array_keys($request->all())[0] ?? '';
        if(!Auth::check()){
            if ($request->isMethod('get')) {
                return view('pages.centralized_sign_up', compact('user_type'));
            }
        }
        return redirect('/');
    }

    public function signUpAfterPostingProject(Request $request) {
        if(!Auth::check()){
            if(isset($_COOKIE) && !empty($_COOKIE) && array_key_exists('project_from_home', $_COOKIE)){
                return view('pages.signupafterprojectpost');
            }else{
                return Redirect::To('/homepage/postproject');
            }
        }else{
            return Redirect::To('/redirectlogin');
        }
    }

    public function saveUser(Request $request) {
        $form_data = $request->all();
        $rules = array(
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile_number' => 'required',
            'email' => 'required|email|unique:users',
            'psswrd' => 'required|min:6|max:50',
            'user_type' => 'required',

        );
        $form_data['first_name'] = ucwords(strtolower($form_data['first_name']));
        $form_data['last_name'] = ucwords(strtolower($form_data['last_name']));
        $user_type = $form_data['user_type'] ?? '';
        if ($user_type == config('constants.BUYER')) {
            $rules['expected_project_post_time'] = 'required';
        }
        $validator = Validator::make($form_data, $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput($request->input());
        } else {
            $user_email = trim(strtolower($form_data['email']));
            $user_validations = $this->userValidations($user_email, $form_data, $request);
            if(!empty($user_validations)) return $user_validations;
            try {
                \DB::beginTransaction();
                $user = $this->saveUserToDatabase($form_data);
                $user->save();
                if ($form_data['user_type'] == config('constants.EXPERT'))
                    $this->saveUserProfileAndLanguage($user->id, $form_data);

                if ($form_data['user_type'] != config('constants.EXPERT'))
                    $this->saveBuyerProfile($user->id, $form_data);
                $this->saveCommunication($user->id);
                $user_type_id = $user['user_type_id'];
                $this->userVerficationEmail($user_type_id,$user['id']);
                if ($form_data['user_type'] == config('constants.EXPERT')) {
                    Email::sendExpertSignupEmailToMeasureMatch($user->id);
                    $this->associateServiceHub($user->id, $user->email);
                } else {
                    if (array_key_exists('source', $form_data) && $form_data['source'] == 'signup_after_project_post' && isset($_COOKIE) &&
                        !empty($_COOKIE) && array_key_exists('project_from_home', $_COOKIE)) {
                        $this->saveProjectFromHome($user->id);
                    } else {
                        Email::sendBuyerSignupEmailToMeasureMatch($user->id);
                    }
                    Newsletter::subscribe($user_email, ['firstName' => $form_data['first_name'], 'lastName' => $form_data['last_name']]);
                }
                Session::put('signup_success', true);
                Session::put('signup_email', $user_email);
                DB::commit();
                (new SegmentComponent)->accountTracking($user['id'], $form_data['user_type'], "Account Created");
                $user_type = getUserRole($user_type_id);
                return Redirect::to('success?type='.$user_type)->with('success', true);
            } catch (\Exception $e) {
                DB::rollback();
                return back()->with('general_error', 'There was an error in creating your account. Please try again later!');
            }
        }
    }
    
    private function associateServiceHub($user_id, $email)
    {
        $domain_already_exist = (new VendorInvitedExpert)->checkIfDomainIsAlreadyRegistered($email);
        if(_count($domain_already_exist))
        {
            foreach($domain_already_exist as $service_hub_id)
            {
                $data_to_insert[] = [
                    'service_hub_id' => $service_hub_id['service_hub_id'],
                    'user_id' => $user_id,
                    'status' => config('constants.PENDING'),
                    'created_at' => date('Y-m-d H:m:s'),
                    'updated_at' => date('Y-m-d H:m:s'),
                    ];
            }            
            ServiceHubAssociatedExpert::insert($data_to_insert);
        }
    }

    private function saveUserToDatabase($form_data){
        $user_email = trim(strtolower($form_data['email']));
        $user_unique_number = checkUniqueMME($form_data['user_type']);
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $user = new User;
        $user->email = $user_email;
        $user->name = $form_data['first_name'];
        $user->last_name = $form_data['last_name'];
        $user->user_type_id = $form_data['user_type'];
        $user->password = bcrypt($form_data['psswrd']);
        $user->mm_unique_num = $user_unique_number;
        $user->phone_num = (!empty($form_data['country_code']))? '+'.$form_data['country_code'].'-'.$form_data['mobile_number'] : $form_data['mobile_number'];
        $user->access_token = $token;
        return $user;
    }

    private function saveUserProfileAndLanguage($user_id, $form_data){
        $user_profile = new UserProfile;
        $user_profile->user_id = $user_id;
        $user_profile->expert_type = getExpertTypeById($form_data['expert_type']);
        if ($user_profile->save()) {
            $lang_name = 'English';
            $languages = Language::where('name', 'iLIKE', trim($lang_name))->get()->toArray();
            if (!empty($languages)) {
                $check_existing_language = UsersLanguage::where('language_id', $languages[0]['id'])->where('user_id', $user_id)->get()->toArray();
                if (empty($check_existing_language)) {
                    $user_language = new UsersLanguage;
                    $user_language->language_id = $languages[0]['id'];
                    $user_language->user_id = $user_id;
                    return $user_language->save();
                }
            }
        }
    }

    private function saveBuyerProfile($user_id, $form_data){
        $buyer_profile = new BuyerProfile;
        $buyer_profile->company_name = trim($form_data['company_name']);
        $buyer_profile->company_url = trim($form_data['company_website']);
        $buyer_profile->first_name = trimFirstName($form_data['first_name']);
        $buyer_profile->last_name = $form_data['last_name'];
        $buyer_profile->user_id = $user_id;
        $buyer_profile->expected_project_post_time = $form_data['expected_project_post_time'] ?? 0;
        
        $business_information = (new BusinessInformationComponent)->storeBusinessInformation($user_id, config('constants.REGISTERD_COMPANY'));
        $business_details = new BusinessDetails;
        $business_details->company_website = trim($form_data['company_website']);
        $business_details->company_name = trim($form_data['company_name']);
        if ($business_details->save()) {
            $business_information = $business_information->updateBusinessInformation($user_id, ['business_detail_id' => $business_details->id]);
        }
        
        return $buyer_profile->save();
    }
        
    private function saveCommunication($user_id){
        $user_communication = new UsersCommunication();
        $user_communication->user_id = $user_id;
        $user_communication->created_at = Carbon::now();
        $user_communication->save();
    }

    private function saveProjectFromHome($user_id){
        $project_from_home = json_decode($_COOKIE['project_from_home'], true);
        $project_from_home['user_id'] = $user_id;
        if ($post_job = PostJob::create($project_from_home)) {
            (new SegmentComponent)->projectTracking($user_id, $post_job->id, $post_job->job_title, "Project Created");
            if(array_key_exists('type_of_organization', $project_from_home) && !empty($project_from_home['type_of_organization'])){
                BuyerProfile::updateBuyerInformation($user_id, ['type_of_organization_id' => $project_from_home['type_of_organization']]);
            }
            LoginComponent::removeCookie('project_from_home');
            PostProjectComponent::saveDeliverables($post_job->id, $project_from_home['deliverables'],'project',false);
            PostProjectComponent::saveSkills($project_from_home, $post_job->id,false);
            Email::projectAdminReview(['buyer_id'=>$user_id , 'project_id' => $post_job->id]);
            User::updateUser($user_id, ['settings' => json_encode(['posted_from_home_project_id'=>$post_job->id]), 'admin_approval_status' => config('constants.ACCEPTED'), 'admin_approval_time' => Carbon::now()]);

        }
    }

    private function userValidations($user_email, $form_data, $request){
        $redirect_to = '';
        $check_duplicate = $this->checkUniquEmail($user_email);
        if($form_data['user_type']==config('constants.BUYER') || $form_data['user_type']==config('constants.VENDOR')){
            $validate_business_email = $this->validateBuyerEmailToBeBusinessEmail($user_email);
            if(empty(trim($form_data['company_name']))){
                $redirect_to = back()->with('general_error', 'Please enter your company name.')->withInput($request->input());
            }
            if(empty(trim($form_data['company_website']))){
                $redirect_to = back()->with('general_error', 'Please enter your company website URL.')->withInput($request->input());
            }
        }else{
            if(empty(trim($form_data['expert_type']))){
                $redirect_to = back()->with('general_error', 'Please choose the service provider type.')->withInput($request->input());
            }
        }
        if ($check_duplicate == 0) {
            $redirect_to = back()->with('general_error', 'This email is already taken. Please try with a new email.')->withInput($request->input());
        }
        if (isset($validate_business_email) && $validate_business_email == 0) {
            $redirect_to = back()->with('general_error', 'Please sign up with your work email address.')->withInput($request->input());
        }
        return $redirect_to;
    }

    public function successPage() {
        $signup_session = Session::get('signup_success');
        $signup_email = Session::get('signup_email');
        if ($signup_session === true) {
            Session::put('signup_success', false);
            Session::put('signup_email', '');
            return view('pages.success', compact('signup_email'));
        }else {
            return redirect('/');
        }
    }

    public function checkUniquEmail($email) {
        if (isset($email)) {
            $email_count = User::where('email', $email)->count();
            if ($email_count > 0) {
                return 0;
            } else {
                return 1;
            }
        }
        return 0;
    }

    public function validateBuyerEmailToBeBusinessEmail($email) {
        if (isset($email)) {
            $check_if_exists = InvalidEmailDomain::fetchDomains(['email_domain' => explode('@', $email)[1]], 'count');
            if ($check_if_exists > 0) {
                return 0;
            } else {
                return 1;
            }
        }
        return 0;
    }

    function userVerficationEmail($user_type_id, $id) {
        try {
            if ($user_type_id == config('constants.EXPERT')){
                Email::expertVerificationEmail(['id' => $id]);
            } 
            if ($user_type_id == config('constants.BUYER') || $user_type_id == config('constants.VENDOR')){
                Email::buyerVerificationEmail(['id' => $id, 'user_type_id' => $user_type_id]);
            }
            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function supportRequestThroughMobile(){
        if (Auth::check()) {
            if(Auth::user()->user_type_id == config('constants.EXPERT')){
                return view('pages.expertsupport');
            }
            return view('pages.buyersupport');
        }
        return redirect('login');
    }
    public function supportNotification(Request $request) {
        $support_message_data = $request->all();
        $rules = array(
            'user_messages' => 'required'
        );
        $validator = Validator::make($support_message_data, $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput($request->input());
        } else {
            $support_message_content = $support_message_data['user_messages'];
            $user_details = getUserDetails(Auth::user()->id);
            if ($user_details['user_type_id'] === config('constants.EXPERT')) {
                Email::supportRequestMessageToAdminFromExpert(['expert_id' => Auth::user()->id , 'support_message_content' => $support_message_content]);
            } else {
                Email::supportRequestMessageToAdminFromBuyer(['buyer_id' => Auth::user()->id , 'support_message_content' => $support_message_content]);
            }
            Email::acknowledgementEmailFromMmSupport(Auth::user()->id);
            return ['success' => True];
        }
    }
    public function deleteUserAccount(Request $request) {
        $user_id = Auth::user()->id;
        $user_type = Auth::user()->user_type_id;
        $input = $request->all();

        if (!empty($user_id) && !empty($input['X-CSRF-TOKEN'])) {
            $archive_allowed = TRUE;
            if(notAllowUserArchive()>0){
                $archive_allowed = FALSE;
            }
            $response = 0;
            if($archive_allowed == TRUE){
                $response = User::updateUser($user_id, ['is_deleted' => '1','deleted_at'=>date('Y-m-d G:i:s')]);
                if ($user_type == config('constants.EXPERT')) {
                    ServicePackage::updateServicePackageWithUserId($user_id, ['deleted_at'=>date('Y-m-d G:i:s')]);
                }else{
                    PostJob::updatePostDataByUser(['deleted_at'=>date('Y-m-d G:i:s')], $user_id);
                }
            }

            if ($response) {
                Auth::logout();
                return 1;
            } else {
                if ($user_type == config('constants.EXPERT')) {
                    return Redirect::To('/expert/settings')->with('warning', 'Your account not deleted. Please try again!!!.');
                } else {
                    return Redirect::To('/buyer/settings')->with('warning', 'Your account not deleted. Please try again!!!.');
                }
            }
        }
    }

    public function resendVerificationEmail(Request $request){
        $input_information = $request->all();
        $result = ['success' => 0];
        if(array_key_exists('email', $input_information)){
            $email = trim($input_information['email']);
            $check_if_exists = User::findByCondition(['email' => $email], [], ['type' => 'count']);
            if($check_if_exists){
                $user_information = User::getUserInformationWithEmail($email);
                $this->userVerficationEmail($user_information[0]['user_type_id'],$user_information[0]['id']);
                $result = ['success' => 1];
            }
        }
        return $result;
    }

    public function requestMyData(){
        if (!Auth::check()) {
            return redirect('login');
        }
        $user_type = Auth::user()->user_type_id;
        if ($user_type == config('constants.EXPERT') || $user_type == config('constants.BUYER')) {
            Email::userRequestForDataEmailToAdmin(Auth::user()->id);
            Email::myDataRequestEmailToUser(Auth::user()->id);
            return 1;
        }
    }
    public function accountDeletionRequest(){
        if (!Auth::check()) {
            return redirect('login');
        }
        $user_type = Auth::user()->user_type_id;
        if ($user_type == config('constants.EXPERT') || $user_type == config('constants.BUYER')) {
            Email::userRequestForAccountDeletionEmailToAdmin(Auth::user()->id);
            Email::accountDeletionRequestEmailToUser(Auth::user()->id);
            $result = ['success' => 1];
        }
        return $result;
    }

    public function getUserProfile(){
        $user = Auth::user();
        if($user->user_type_id == config('constants.BUYER')){
            $user_profile = BuyerProfile::findByCondition(['user_id' => $user->id])->toArray();
        }else{
            $user_profile = UserProfile::getUserProfile($user->id);
        }
        return $user_profile;
    }
    
    public function updateBasicInformation(Request $request) {
        $form_data = $request->all();
        $messages = [
            'first_name.required' => __('custom_validation_messages.personal_information.first_name'),
            'last_name.required' => __('custom_validation_messages.personal_information.last_name'),
            'phone_num.required' => __('custom_validation_messages.personal_information.phone_num_required'),
            'phone_num.regex' => __('custom_validation_messages.personal_information.phone_num'),
            'date_of_birth.required' => __('custom_validation_messages.personal_information.date_of_birth'),
        ];
        $rules = array(
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_num' => 'required|regex:/[+,0-9]/',
            'date_of_birth' => 'required|date'
        );
        $validator = Validator::make($form_data, $rules, $messages);
        if ($validator->fails()) {
            return \Response::json($validator->errors(), 422); 
        }
        if (strtotime($form_data['date_of_birth']) > strtotime(date('d-m-Y')))
        {
            $validator->errors()->add('date_of_birth', __('custom_validation_messages.personal_information.date_of_birth'));
            return \Response::json($validator->errors(), 422);
        }
        
        $data_to_upate = ['name' => $form_data['first_name'],
            'last_name' => $form_data['last_name'],
            'phone_num' => (!empty($form_data['country_code'])) ? $form_data['country_code'] . '-' . $form_data['phone_num'] : $form_data['phone_num'],
            'date_of_birth' => isset($form_data['date_of_birth']) ? date('Y-m-d', strtotime($form_data['date_of_birth'])) : Null
        ];
        return User::updateUser(Auth::user()->id, $data_to_upate);
    }
    public function storeVatDetails(Request $request, $user_id) {
        $form_data = $request->all();
        $messages = [
            'business_type.required' => __('custom_validation_messages.business_information.business_type'),
            'company_country.required' => __('custom_validation_messages.business_information.business_country')
        ];
        $validator = Validator::make($form_data, [
                'business_type' => 'required',
                'company_country' => 'required'
                ], $messages);
        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator->errors())->withInput();
        }
        
        $business_information = (new BusinessInformation())->getUserBusinessInformation($user_id);
        if (empty($business_information)) {
            $business_information = (new BusinessInformationComponent)->storeBusinessInformation($user_id, $form_data['business_type']);
        }
        if ($form_data['business_type'] == config('constants.REGISTERD_COMPANY')) {
            $business_details = $business_information->businessDetails;
            if (empty($business_details)) {
                $business_details = new BusinessDetails;
            }
            $business_details->vat_status = (isset($form_data['vat_registered']) && $form_data['vat_registered'] == 'on' ) ? true : false;
            $business_details->vat_country = $form_data['vat_country'];
            $business_details->company_country = $form_data['company_country'];
            if ($business_details->save()) {
                $business_information = $business_information->updateBusinessInformation($user_id, ['business_detail_id' => $business_details->id]);
            }
        } else {
            $business_address = new BusinessAddress(['country' => $form_data['sole_trader_country']]);
            if ($business_address->save()) {
                $business_information = $business_information->updateBusinessInformation($user_id, ['business_address_id' => $business_address->id]);
            }
        }
        if (isset($form_data['start_conversation_popup']) && $form_data['start_conversation_popup'] == 1) {
            return $form_data['start_conversation_popup'];
        }
        return redirect(route('send-proposal', [$form_data['communication_id'], 1]));
    }
    
    public function getVatDetailsPopup(Request $request) {
        $business_information = (new BusinessInformation)->getUserBusinessInformation($request->id);
        $user_name = $request->expert_name;
        $countries = (new CountryVatDetails)->getAllCountryVatDetails();
        updateUserSetting(['vat_country_confirmation_pop_up' => config('constants.TRUE')]);
        $data = view('message.popups.vat_details_popup', compact('business_information', 'user_name', 'countries'))->render();
        return ['success' => 1, 'content' => $data];
    }
    
    public function getBuyerDetailsFromClearbit(Request $request)
    {
        $email = trim($request->email);
        $buyer_data = (new ClearbitComponent)->getPersonData($email);
        return $buyer_data;
    }

}
