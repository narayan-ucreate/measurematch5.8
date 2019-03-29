<?php

use App\Model\User;
use App\Model\Communication;
use App\Model\UserProfile;
use App\Model\PostJob;
use App\Model\SavedExpert;
use App\Model\JobsSkill;
use App\Model\Skill;
use App\Model\Contract;
use App\Model\CountryVatDetails;
use Carbon\Carbon;
use App\Model\CouponAppliedByExpert;
use App\Model\PromotionalCouponUsageDetail;
use App\Model\ServicePackage;
use App\Model\SavedServicePackage;
use App\Model\UsersSkill;
use App\Model\BuyerProfile;
use App\Model\UsersCommunication;
use App\Components\Common;
use App\Components\SegmentComponent;

function expertInformation($id) {
    $expert = User::getExpertInformation($id);
    return $expert;
}

function buyerInfo($id) {
    $buyer = User::getBuyerInformation($id);
    return $buyer;
}

function userInfo($id) {
    $user = User::getUserInformationWithId($id);
    return $user;
}
function getContract($contract_id) {
    $communication_id = Contract::getContractInformationInObject($contract_id);
    return $communication_id;
}

function getJob($job_id, $truncated = 1,$limit=25) {
    $get_job = PostJob::getPostInformation($job_id);

    if (isset($get_job) && !empty($get_job)) {
        if($truncated == 1){
            $job_title = getTruncatedContent(ucfirst($get_job[0]['job_title']), $limit);
        }else{
            $job_title = ucfirst($get_job[0]['job_title']);
        }
    } else {
        $job_title = '';
    }

    return $job_title;
}

function getServicePackageName($service_package_id,$truncated=1,$limit=25) {
    $get_package = ServicePackage::getServicePackageById($service_package_id);
    if (isset($get_package) && !empty($get_package)) {
        if($truncated == 1) {
           return $package_title = getTruncatedContent(ucfirst($get_package[0]['name']), $limit);
        }else{
           return $package_title = ucfirst($get_package[0]['name']);
        }
    } else {
      return  $package_title = '';
    }
}

function getPostJobs($id = null) {
    $post_jobs = PostJob::where('user_id', $id)->select('job_title', 'id')->get();
    if (isset($post_jobs) && !empty($post_jobs)) {
        $response = $post_jobs;
    } else {
        $response = '';
    }
    return $response;
}

function allUnreadMessages() {
    $all_messages = \App\Model\Message::getUnreadMessageCount(\App\Components\Common::getAuthorizeUser()->id);
    if ($all_messages == 0) return '';
    return $all_messages;
}

function userUnreadMessages($communications_id) {
    return \App\Model\Message::getCountUnreadMsgCount($communications_id, Auth::user()->id);
}

function getAdminInfo() {
    $response = DB::table('users')->select('*')->where('user_type_id', 3)->get();
    return $response;
}

function getTypeOfOrganization() {
    $response = DB::table('type_of_organizations')->select('*')->where('depricated', False)->orderBy('name', 'asc')->get();
    return $response;
}

function timeElapsedString($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $time_diffence = $now->diff($ago);
    $time_diffence->w = floor($time_diffence->d / 7);
    $time_diffence->d -= $time_diffence->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($time_diffence->$k) {
            $v = $time_diffence->$k . ' ' . $v . ($time_diffence->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full)
        $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function jobViewUpdate($job_id, $expert_id) {

    $get_user = DB::table('job_viewers')->where('job_posted_id', $job_id)->where('expert_id', $expert_id)->get();
    if (!_count($get_user)) {
        $job_viewer = new App\Model\JobViewer;
        $job_viewer->job_posted_id = $job_id;
        $job_viewer->expert_id = $expert_id;
        $job_viewer->save();
    }
    return true;
}

function countJobViewers($job_id) {
    $get_user = DB::table('job_viewers')->where('job_posted_id', $job_id)->get();
    if (isset($get_user) && !empty($get_user)) {
        $user_information = _count($get_user);
    } else {
        $user_information = 0;
    }
    return $user_information;
}

function checkReferralStatus($referral_email, $expert_id) {

    $referral = App\Model\ReferralExpert::where('referral_expert_email', $referral_email)->where('expert_id', $expert_id)->get();
    $response = 0;
    foreach ($referral as $referral_information) {
        if (isset($referral_information) && !empty($referral_information)) {
            $response = 1;
        }
    }
    return $response;
}

function getCouponApplied($total_price, $contract_id, $expert_id) {
    $contract_information = App\Model\CouponAppliedByExpert::where('expert_id', $expert_id)->where('contract_id', $contract_id)->get();
    $result['response'] = '1'; //Coupon Not Applied
    foreach ($contract_information as $contractResponse) {
        if (isset($contractResponse) && !empty($contractResponse)) {
            $result['response'] = '0'; //Coupon Applied
        }
    }
    $total_price = (double) $total_price;
    if ($result['response'] == 0) {
        $app_paid = $total_price - ((config('constants.EXPERT_SHARE') / config('constants.HUNDRED')) * $total_price);
        $result['application_to_be_paid'] = round($app_paid, 2);
        $total_you_will_receive = $total_price - (((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $total_price)) + config('constants.TWENTY');
        $result['amount_to_be_paid'] = round($total_you_will_receive, 2);
        $result['expert_amount'] = round($total_you_will_receive, 2);
        $result['discount_applied'] = config('constants.TWENTY');
    } else {
        $result['application_to_be_paid'] = round($total_price - (((config('constants.EXPERT_SHARE') / config('constants.HUNDRED')) * $total_price)), 2);
        $result['expert_amount'] = round($total_price - ((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $total_price), 2);
        $result['amount_to_be_paid'] = round($total_price - ((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $total_price), 2);
    }
    return $result;
}

/* Contract having any coupon or not
 * @param: $contract_id
 *
 * @returns:
 * */

function contractHasPromotionalCoupon($total_price, $contract_id) {
    $coupon_information = PromotionalCouponUsageDetail::isPromotionalCouponApplied($contract_id);
    $total_price = (double) $total_price;
    if (isset($coupon_information) && _count($coupon_information) > 0 && isset($coupon_information->promotionalCouponDetail->amount)) {
        $coupon_amount = $coupon_information->promotionalCouponDetail->amount;
        $amount_to_be_paid_by_buyer = $total_price - $coupon_amount;
        $app_paid = $total_price - ((config('constants.EXPERT_SHARE') / config('constants.HUNDRED')) * $total_price); //Amount to be paid to MeasureMatch
        $result['application_to_be_paid'] = number_format($app_paid, 2);
        $total_you_will_receive = $total_price - (((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $total_price)); //Amount to be paid to Expert
        $result['amount_to_be_paid'] = number_format($amount_to_be_paid_by_buyer, 2);
        $result['expert_amount'] = number_format($total_you_will_receive, 2);
        $result['discount_applied'] = number_format($coupon_amount, 2);
    } else {
        $result['application_to_be_paid'] = number_format($total_price - (((config('constants.EXPERT_SHARE') / config('constants.HUNDRED')) * $total_price)), 2);
        $result['amount_to_be_paid'] = number_format(($total_price), 2);
        $result['expert_amount'] = number_format($total_price - ((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $total_price), 2);
    }
    return $result;
}

function getContractfeedback($contId) {
    $feedback_count = DB::table('contracts')->where('user_id', '=', $contId)->where('buyer_feedback_status', 1)->count();
    return $feedback_count;
}

/* Diffrence between two dates
 * @param: $from and $to
 * @returns: Difference in days/weeks
 * */

function differenceInWeeksAndDays($from, $to) {
    $day = 24 * 3600;
    $from = strtotime($from);
    $to = strtotime($to) + $day;
    $time_diffence = abs($to - $from);
    $time_frame = floor($time_diffence / $day / 7);
    $days = $time_diffence / $day - $time_frame * 7;
    $out = array();
    if ($time_frame)
        $out[] = "$time_frame Week" . ($time_frame > 1 ? 's' : '');
    if ($days)
        $out[] = "$days Day" . ($days > 1 ? 's' : '');
    return implode(', ', $out);
}

/*
 * Total EOI count on project
 */

function getCountEOI($id, $project_type = 'project') {
    return Communication::getCommunicationCountById($id, $project_type);
}

/**
 * project expiry date status
 * @param: Expiry date  ('in 2017-06-17 Format ')
 * returns expiry status
 *   */
function projectExpiryDateStatus($expiry_date) {
    $now = time();
    $your_date = strtotime($expiry_date);
    $datediff = $your_date - $now;
    $difference_in_days = (floor($datediff / (60 * 60 * 24)) + 1);
    if ($difference_in_days >= 0) {
        $result = 'Project Start: ' . $difference_in_days . ' days';
    } else {
        $result =  "Project Start: To be confirmed";
    }
    return $result;
}

/**
 * User Check Exist Method
 *
 * @param Request $request
 *
 * @return int
 */
function checkEmailStatus($email) {
    $email = strtolower($email);
    $email_chk = User::getUserInformation($email);
    if (!empty($email_chk[0]['email']) && (_count($email_chk) > 0)) {
        return 1;
    } else {
        return 0;
    }
}

/* For datalayers getting user type
 * */

function userTypeArray() {
    $user_type_array = ['1' => 'Expert', '2' => 'Client', '3' => 'Admin'];
    return $user_type_array;
}

/* To check how many experts buyer is enganged for project
 * @param : $buyer_id and $job_id
 * @return: Engagement
 */

function engagementCount($buyer_id, $job_id) {
    $engagement = Communication::where('buyer_id', $buyer_id)->where('job_post_id', $job_id)->where('status', '1')->count();
    return $engagement;
}

function isValidUuid($uuid) {
    return preg_match('/^[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}$/i', $uuid);
}

function adminAuth() {
    if (isset(\App\Components\Common::getAuthorizeUser()->id) && \App\Components\Common::getAuthorizeUser()->user_type_id == config('constants.ADMIN')) {
        return true;
    }
}

function expertAuth() {
    if (isset(\App\Components\Common::getAuthorizeUser()->id) && \App\Components\Common::getAuthorizeUser()->user_type_id == config('constants.EXPERT')) {
        return true;
    }
}

function buyerAuth() {
    if (isset(\App\Components\Common::getAuthorizeUser()->id) && \App\Components\Common::getAuthorizeUser()->user_type_id == config('constants.BUYER')) {
        return true;
    }
}

/* Check if this is buyer's first project */

function isFirstProject() {
    if (isset(Common::getAuthorizeUser()->id) && Common::getAuthorizeUser()->user_type_id == config('constants.BUYER')) {
        $all_project = PostJob::findByCondition(['user_id' => Common::getAuthorizeUser()->id], [], ['type' => 'count']);
        $pending_posts_count = PostJob::findByCondition(['user_id' => Common::getAuthorizeUser()->id, 'publish' => config('constants.PROJECT_PENDING')], [], ['type' => 'count']);
        $rejected = PostJob::getPublishedJobs(Common::getAuthorizeUser()->id, config('constants.PROJECT_REJECTED'))->count();
        $accepted = PostJob::getPublishedJobs(Common::getAuthorizeUser()->id, config('constants.ACCEPTED'))->count();

        if ($all_project == 0) {
            return 0;
        } else if ($pending_posts_count && $accepted == 0) {
            return 1;
        } else if ($rejected > 0 && $accepted == 0) {
            return 3;
        } else {
            return 2;
        }
    }
}

function checkIfExpertSavedByBuyer($expert_id, $buyer_id, $source = '') {
    if($source == 'project_progress'){
        return SavedExpert::findByCondition(['expert_id' => $expert_id, 'buyer_id' => $buyer_id], '', ['type' => 'count'], ['post_job_id' => 'null']);
    }
    return SavedExpert::findByCondition(['expert_id' => $expert_id, 'buyer_id' => $buyer_id], '', ['type' => 'count'], ['post_job_id' => 'notnull']);
}

function addPostOffline($project_from_home, $publish_status, $company_id, $id) {
    $postjob = new PostJob;

    $postjob->user_id = $id;
    $postjob->post_is_negotiable = 0;
    //----------Code to add unique no. to jobs------
    $JobNum = checkUniqueJobNums();
    $postjob->job_num = $JobNum;
    $postjob->company_id = $company_id;
    $postjob->job_title = $project_from_home['job_title'];
    if (array_key_exists('post_is_negotiable', $project_from_home) && !empty($project_from_home['post_is_negotiable'])) {
        $postjob->post_is_negotiable = $project_from_home['post_is_negotiable'];
    }
    $postjob->description = $project_from_home['description'];
    $postjob->remote_id = $project_from_home['remote_work'];
    $postjob->job_start_date = date('Y-m-d');
    $postjob->project_duration = $project_from_home['number_of_days'];

    if (!empty($project_from_home['end_time'])) {
        $postjob->job_end_date = date('Y-m-d', strtotime($project_from_home['end_time']));
    }
    $postjob->rate_variable = $project_from_home['rate_variable'];

    if ($project_from_home['budget'] == 'no') {
        $postjob->rate = 0;
    } else {
        $postjob->rate = $project_from_home['rate'];
    }
    $postjob->payment_method = $project_from_home['radio'];
    $postjob->currency = '$';
    $postjob->publish = $publish_status;
    if ($publish_status == config('constants.APPROVED')) {
        $postjob->publish_date = date('Y-m-d');
    }
    $postjob->save();

    return $postjob;
}

function checkUniqueMME($user_type) {
    $count_chk_mmeId = User::orderBy('created_at', 'desc')->first();
    if (!empty($count_chk_mmeId->mm_unique_num)) {
        $newstring = substr($count_chk_mmeId->mm_unique_num, -6);
        $user_unique_number = $newstring + 1;
        if ($user_type == 1) {
            $user_unique_number = 'MME00' . $user_unique_number;
        } else {
            $user_unique_number = 'MMB00' . $user_unique_number;
        }
    } else {
        if ($user_type == 1) {
            $user_unique_number = 'MME' . '001000';
        } else {
            $user_unique_number = 'MMB' . '001000';
        }
    }
    return $user_unique_number;
}

function checkUniqueJobNums() {
    $job_number = '';
    $checkJobNum = PostJob::orderBy('created_at', 'desc')->first();
    if (!empty($checkJobNum->job_num)) {
        $newstring = substr($checkJobNum->job_num, -6);
        $job_number = $newstring + 1;
        $job_number = 'MMP00' . $job_number;
    } else {
        $job_number = 'MMP' . '001000';
    }
    return $job_number;
}

function addSkillOffline($post_job, $project_from_home) {
    $job_id = $post_job->id;
    $skills = isset($project_from_home['addskill']) ? $project_from_home['addskill'] : array();
    $manual_skills = explode(',', $project_from_home['manual_skills']);
    $all_skills = array_merge($skills, $manual_skills);

    if (!empty($all_skills)) {
        for ($i = 0; $i < sizeof($all_skills); $i++) {
            $check_skill = Skill::where('name', 'iLIKE', trim($all_skills[$i]))->get()->toArray();
            $created = Carbon::now();
            if (!empty($check_skill)) {
                $skill_id = $check_skill[0]['id'];
                if (JobsSkill::where('skill_id', $skill_id)->where('job_post_id', $job_id)->exists()) {

                } else {
                    $skill_insert = addSkillsOffline($skill_id, $job_id, $created);
                }
            } else {
                if (trim($all_skills[$i]) == '') {
                    unset($all_skills[$i]);
                } else {
                    $skill_id = Skill::insertGetId(array('name' => trim($all_skills[$i]), 'created_at' => $created, 'skill_type' => 'job'));
                    $skill_insert = addSkillsOffline($skill_id, $job_id, $created);
                }
            }
        }
        return 1;
    }
}

function mandateFieldsCompleteness($user_profile) {
    $profile_complete_percentage = config('constants.HUNDRED');
    $basic_profile_completness = TRUE;
    if (empty($user_profile['user_profile']['profile_picture'])) {
        $profile_complete_percentage = ($profile_complete_percentage - 5);
        $basic_profile_completness = FALSE;
    }

    if (empty($user_profile['user_profile']['describe'])) {
        $profile_complete_percentage = ($profile_complete_percentage - 10);
        $basic_profile_completness = FALSE;
    }

    if (empty($user_profile['user_profile']['daily_rate'])) {
        $profile_complete_percentage = ($profile_complete_percentage - 10);
        $basic_profile_completness = FALSE;
    }

    if (empty($user_profile['user_profile']['current_city'])) {
        $profile_complete_percentage = ($profile_complete_percentage - 10);
        $basic_profile_completness = FALSE;
    }

    if (empty($user_profile['user_profile']['summary'])) {
        $profile_complete_percentage = ($profile_complete_percentage - 10);
        $basic_profile_completness = FALSE;
    }

    if (areSkillsAndToolsAdded(Auth::user()->id)==FALSE && $user_profile['admin_approval_status'] == config('constants.PENDING')){
        $profile_complete_percentage = ($profile_complete_percentage - 25);
        $basic_profile_completness = FALSE;
    }

    if (empty($user_profile['user_profile']['remote_id'])) {
        $profile_complete_percentage = ($profile_complete_percentage - 10);
        $basic_profile_completness = FALSE;
    }

    return $result = ['profile_complete_percentage' => $profile_complete_percentage, 'basic_profile_completness' => $basic_profile_completness];
}
function areSkillsAndToolsAdded($expert_id){
    $user_profile = User::userFirstDetailWithFullProfile($expert_id)->toArray();
    $tool_count=$skill_count=0;
    if(_count($user_profile['user_skills']) == 0){return false;}
    if (_count($user_profile['user_skills'])){
         foreach ($user_profile['user_skills'] as $value) {
             if($value['skill']['is_tool']==1){
                 $tool_count++;
             }else{
                $skill_count++;
             }
         }
    }
    if(($tool_count + $skill_count) >= config('constants.MINIMUM_SKILLS_AND_TOOLS_COUNT_FOR_PROFILE_COMPLETEION')){
      return True;
    }else{
      return false;
    }

}
function getExpertTools($expert_id){
    return UsersSkill::getUserToolsByUserId($expert_id);
}
function getExpertSkills($expert_id){
    return UsersSkill::getUserSkillsByUserId($expert_id);
}
function getProjectTools($project_id){
    return JobsSkill::getProjectToolsByProjectId($project_id);
}
function getProjectSkills($project_id){
    return JobsSkill::getProjectSkillsByProjectId($project_id);
}
function calculateProfileCompletePercentageStatus() {
    $id = \App\Components\Common::getAuthorizeUser()->id;
    $user_profile = User::userFirstDetailWithFullProfile($id)->toArray();
    if (empty($user_profile['user_profile']['expert_profile_review_email_to_admin_date']) || $user_profile['user_profile']['expert_profile_review_email_to_admin_date'] == NULL) {
        $profile_review_email_sent_to_admin = FALSE;
    } else {
        $profile_review_email_sent_to_admin = TRUE;
    }

    $mandate_fields_completeness = mandateFieldsCompleteness($user_profile);
    $profile_complete_percentage = $mandate_fields_completeness['profile_complete_percentage'];
    $basic_profile_completness = $mandate_fields_completeness['basic_profile_completness'];

    //Below are optional fields

    if (_count($user_profile['user_languages']) == 0) {
        $profile_complete_percentage = ($profile_complete_percentage - 5);
    }

    if (_count($user_profile['user_employment_detail']) == 0) {
        $profile_complete_percentage = ($profile_complete_percentage - 5);
    }

    if (_count($user_profile['user_education_detail']) == 0) {
        $profile_complete_percentage = ($profile_complete_percentage - 5);
    }

    if (_count($user_profile['user_certification']) == 0) {
        $profile_complete_percentage = ($profile_complete_percentage - 5);
    }

    $response = ['profileCompletePercentage' => $profile_complete_percentage, 'basic_profile_completness' => $basic_profile_completness, 'profile_review_email_sent_to_admin' => $profile_review_email_sent_to_admin];

    return $response;
}

/*
 * Payment calculation on accepting contract by expert
 */

function contractPaymentCalculation($rate, $contract_id, $expert_id) {

    $is_promo_coupon_applied = PromotionalCouponUsageDetail::isPromotionalCouponApplied($contract_id);
    $contract_information = CouponAppliedByExpert::isRefferalCouponAppliedByExpert($expert_id, $contract_id);

    /* calculation vairables */
    $fifteen_percent_of_rate = ((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $rate);
    $eighty_five_percent_of_rate = ((config('constants.EXPERT_SHARE') / config('constants.HUNDRED')) * $rate);
    $stripe_fee = ((config('constants.STRIPE_TRANSACTION_FEE') / config('constants.HUNDRED')) * $rate) + 0.30; //Stripe fee on a charge
    $payment = [];

    if (_count($is_promo_coupon_applied) > 0) { //if buyer has applied promo coupon
        $payment['amount_to_be_paid'] = $rate - $fifteen_percent_of_rate + config('constants.HUNDRED');
        $payment['application_to_be_paid'] = $rate - $eighty_five_percent_of_rate - config('constants.HUNDRED');
        $payment['promo_code'] = "$100";
        $payment['rate'] = $rate - config('constants.HUNDRED');
    } elseif (!empty($contract_information)) {  //if expert applied refferal coupon
        $payment['amount_to_be_paid'] = $rate - $fifteen_percent_of_rate + config('constants.TWENTY');
        $payment['application_to_be_paid'] = $rate - ($eighty_five_percent_of_rate + $stripe_fee ) - config('constants.TWENTY');
        $payment['promo_code'] = '$20';
        $payment['rate'] = $rate;
    } else { // if no coupon is applied
        $payment['amount_to_be_paid'] = $rate - $fifteen_percent_of_rate;
        $payment['application_to_be_paid'] = $rate - ($eighty_five_percent_of_rate + $stripe_fee);
        $payment['promo_code'] = 'No code';
        $payment['rate'] = $rate;
    }
    return $payment;
}

function contractPaymentCalculationWithoutCoupon($rate){
    $fifteen_percent_of_rate = ((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $rate);
    $eighty_five_percent_of_rate = ((config('constants.EXPERT_SHARE') / config('constants.HUNDRED')) * $rate);

    $payment['amount_to_be_paid_to_expert'] = $rate - $fifteen_percent_of_rate;
    $payment['mm_fee'] = $rate - ($eighty_five_percent_of_rate);
    return $payment;
}

function contractPaymentCalculationWithoutCouponIncludingVat($rate){
    $vat  = config('constants.VAT');
    $fifteen_percent_of_rate = ((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $rate);
    $eighty_five_percent_of_rate = ((config('constants.EXPERT_SHARE') / config('constants.HUNDRED')) * $rate);
    $vat_amount = (($vat / config('constants.HUNDRED')) * $rate);
    $payment['amount_to_be_paid_to_expert'] = number_format(($rate - $fifteen_percent_of_rate)/config('constants.HUNDRED'), 2);
    $payment['mm_fee'] = number_format(($rate - $eighty_five_percent_of_rate)/config('constants.HUNDRED'), 2);
    $payment['vat_amount'] = number_format($vat_amount/config('constants.HUNDRED'), 2);
    $payment['total'] = number_format(($vat_amount+$rate)/config('constants.HUNDRED'), 2);
    return $payment;
}

function currencyConverterUsdtoGbp($amount) {
    // set API Endpoint and API key
    $endpoint = 'latest';
    $access_key = getenv('FIXER_CURRENCY_CONVERTOR_KEY');
    //source https://fixer.io/dashboard
    // Initialize CURL:
    $ch = curl_init(getenv('FIXER_CURRENCY_CONVERTOR_URL').$endpoint.'?access_key='.$access_key.'&symbols=USD,GBP&format=1');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Store the data:
    $json = curl_exec($ch);
    curl_close($ch);

    // Decode JSON response:
    $exchange_rates = json_decode($json, true);
    $result = 0;
    if($exchange_rates['success'] == 1){
        $conversion_rate = $exchange_rates['rates']['GBP']/$exchange_rates['rates']['USD'];
        $result = round(($amount*$conversion_rate)*100);
    }
    return $result;
}

function expertBuyerBelongToUk($contract_id){
    $result = FALSE;
    $valid_places_list = ['united kingdom', 'uk', 'london'];
    $contract = Contract::findByCondition(['id' => $contract_id], ['seller', 'buyer'], [], 'first')->toArray();
    if((checkIfInUk($contract['seller']['country'], $valid_places_list) ||
        checkIfInUk($contract['seller']['current_city'], $valid_places_list)) &&
        checkIfInUk($contract['buyer']['office_location'], $valid_places_list)){
        $result = TRUE;
    }
    return $result;
}

function checkIfInUk($user_location, $valid_places_list){
    $result = FALSE;
    foreach($valid_places_list as $place_in_uk){
        if(strpos(trim(strtolower($user_location)), $place_in_uk) !== false){
            $result = TRUE;
        }
    }
    return $result;
}

function acceptedContractsCount($parent_contract_id){
    return (Contract::findByCondition(['status'=>1,'parent_contract_id'=>$parent_contract_id], [], [], 'count'))+1;
}

function getImage($image = Null, $ssl) {

    if (isset($image) && $image != Null) {
        $image_name = $image;
    } else {
        $image_name = url(config('constants.DEFAULT_PROFILE_IMAGE'), [], $ssl);
    }

    return $image_name;
}

function getUserDetail($id) {
    return User::where('id', $id)->first()->toArray();
}

function sendEmailToAdminIfBasicProfileIsCompleted() {
    $calculated_percentage = calculateProfileCompletePercentageStatus();
    if ($calculated_percentage['basic_profile_completness'] == TRUE) {
        $admin_review_email_status = UserProfile::getUserProfile( Common::getAuthorizeUser()->id);
        if ($admin_review_email_status[0]['expert_profile_review_email_to_admin_date'] == '') {
            App\Components\Email::expertProfileCompletionEmailToAdmin(['id' => Common::getAuthorizeUser()->id]);
        }
        if (hasSubscribed(Common::getAuthorizeUser()->id)) {
        App\Components\Email::expertEmailForAdminReview(['email' => Common::getAuthorizeUser()->email]);
        }
        UserProfile::updateEmailSentToAdminField( Common::getAuthorizeUser()->id);
    }
}

function getProjectStatusInformation($project_id) {
    $contract_status = 0;
    $contract_information = Contract::getContractStatus($project_id);
    if (isset($contract_information) && !empty($contract_information)) {
        foreach ($contract_information as $value) {
            $status[] = $value['status'];
        }
        if (in_array(1, $status)) {
            $contract_status = 1;
        }
    }
    return $contract_status;
}

function uploadFile($file) {
    if ($file) {
        $original_file = $file->getClientOriginalName();
        $name = time() . "_" . $original_file;
        $s3 = \Storage::disk('s3');
        $s3_bucket = getenv("S3_BUCKET_NAME");
        $file_path = '/' . $name;
        $full_url = getenv('BUCKET_URL'). $s3_bucket . "" . $file_path;
        $s3->put($file_path, file_get_contents($file), 'public');
        return $full_url;
    }
}
function redirectToWebFlow($url_to_redirect){
        if(getenv('ENVIRONMENT')=='Production'){
        header("Location: $url_to_redirect", true, 301);
        exit;
        }
 }
function getHomeUrl() {
    return (config('constants.STATIC_PAGES_DOMAIN').'/');
}

function getFaqUrl() {
    return(config('constants.STATIC_PAGES_DOMAIN').'/faq');
}

function getPrivacyPolicyLink() {
    return(config('constants.STATIC_PAGES_DOMAIN').'/privacy-policy');

}

function getTermConditionsLink() {
    return(config('constants.STATIC_PAGES_DOMAIN').'/terms-of-service');
}
function getCodeOfConductLink() {
    return(config('constants.STATIC_PAGES_DOMAIN').'/code-of-conduct');
}

function getUnsubscribeUrl($email) {
    $base_url = getenv('APP_URL');
    return $base_url . 'unsubscribeEmail?user=' . urlencode(base64_encode($email));
}

function getExpertMessageLink() {
    $base_url = getenv('APP_URL');
    return $base_url . "expert/messages";
}

function getBuyerMessageLink() {
    $base_url = getenv('APP_URL');
    return $base_url . "buyer/messages";
}
function getApproveServicePackageLink($id) {
    $base_url = getenv('APP_URL');
    return $base_url . "admin/pendingservicepackage/$id";
}
function getApproveDraftServicePackageLink($id) {
    $base_url = getenv('APP_URL');
    return $base_url . "admin/draftedservicepackage/$id?all=true";
}

function getOfficeMessageLink() {
    return getenv('HEAD_OFFICE_MAP_LINK');
}

function getLogoUrl() {
    $base_url = getenv('APP_URL');
    return $base_url . "images/Header_logo.png";
}

function getLoginUrl() {
    $base_url = getenv('APP_URL');
    return $base_url . "login";
}
function getExpertServicePackageLink($id) {
    $base_url = getenv('APP_URL');
    return $base_url . "servicepackage/detail/".$id;
}
function getBuyerServicePackageLink($id) {
    $base_url = getenv('APP_URL');
    return $base_url . "servicepackage/".$id;
}


function userName($id, $with_last_initial = 0, $first_name_only=0) {
    $user = User::getUserById($id);
    if ($with_last_initial == 1) {
        return ucfirst($user['name']) . " " . ucfirst(substr($user['last_name'], 0, 1));
    }else if($first_name_only==1){
          return ucfirst($user['name']);
    } else {
        return ucfirst($user['name']) . " " . ucfirst($user['last_name']);
    }
}

function getUserDetails($userid) {
    $user = User::find($userid);
    return ['first_name'=>ucfirst($user->name),'name' => ucfirst($user->name) . " " . ucfirst($user->last_name), 'email' => $user->email, 'user_type_id' => $user->user_type_id,'phone' => $user->phone_num];
}

function orderBy($request) {
    $order_by = 'asc';
    if (isset($request['orderBy']) && !empty($request['orderBy'])) {
        if ($request['orderBy'] == 'asc') {
            $order_by = 'desc';
        }
    }
    return $order_by;
}

function excelProjectInformation($projects_detail, $project_status = '') {
    $user_information=[];
    foreach ($projects_detail as $keys => $project) {
        if (!empty($project['user'])) {
            $publish = $project['publish'];
            $status = config("constants.$publish");
            if(!empty($project_status))
                $status = $project_status;
            $skill_names = [];
            $tools_names = [];
            if (!empty($project['jobsskill'])) {
                foreach ($project['jobsskill'] as $job_skills) {
                    if ($job_skills['skill']['is_tool']) {
                        $tools_names[] = rtrim($job_skills['skill']['name']);
                    } else {
                        $skill_names[] = rtrim($job_skills['skill']['name']);
                    }
                }
            }
            $office_location='';
            if(isset($project['user']['buyer_profile']['office_location']) || !empty(trim($project['office_location']))){
                $office_location=(!empty(trim($project['office_location'])))?trim($project['office_location']):trim($project['user']['buyer_profile']['office_location']);
            }
            $user_information[$keys]['Client Name'] = $project['user']['name'] . ' ' . $project['user']['last_name'];
            $user_information[$keys]['Client Company'] = $project['user']['buyer_profile']['company_name'];
            $user_information[$keys]['Project Name'] = $project['job_title'];
            $user_information[$keys]['No. of EOIs'] = (string) (isset($project['communication_count']))? $project['communication_count']: '';
            $user_information[$keys]['Expert Name'] = $project['contract']['expert']['name'] ?? '';

            if($project_status == config('constants.IN_CONTRACT_PROJECT'))
                $user_information[$keys]['Expert Name'] = $project['latest_active_contract']['expert_name']['name'].' '
                .$project['latest_active_contract']['expert_name']['last_name'];
            if($project_status == config('constants.CONTRACT_COMPLETED'))
                $user_information[$keys]['Expert Name'] = $project['contract']['expert_name']['name'].' '
                .$project['contract']['expert_name']['last_name'];
            $user_information[$keys]['Tools/Tech'] = implode(', ', $tools_names);
            $user_information[$keys]['Skills'] = implode(', ', $skill_names);
            $user_information[$keys]['Brief'] = $project['description'];
            $user_information[$keys]['Place of work'] = expertWorkLocation($project['remote_id']);
            $user_information[$keys]['Office Location'] = $office_location;
            $user_information[$keys]['Project Id'] = $project['job_num'];
            $user_information[$keys]['Project duration'] = ($project['project_duration'])?convertDaysToWeeks($project['project_duration'])['time_frame']:"I don't know";
            $user_information[$keys]['Posted Date'] = isset($project['publish_date']) ? date('d-m-Y', strtotime($project['publish_date'])) : date('d-m-Y', strtotime($project['job_start_date']));
            $user_information[$keys]['Project Start date'] = date('d-m-Y', strtotime($project['job_end_date']));
            $user_information[$keys]['Status'] = $status;
            if($project_status == config('constants.IN_CONTRACT_PROJECT'))
            {
                $user_information[$keys]['Contract Start Date'] = date('d-m-Y', strtotime($project['latest_active_contract']['job_start_date']));
                $user_information[$keys]['Contract End Date'] = date('d-m-Y', strtotime($project['latest_active_contract']['job_end_date']));
            }
            if($project_status == config('constants.CONTRACT_COMPLETED'))
            {
                $user_information[$keys]['Contract Start Date'] = date('d-m-Y', strtotime($project['contract']['job_start_date']));
                $user_information[$keys]['Contract End Date'] = date('d-m-Y', strtotime($project['contract']['job_end_date']));
            }
            if(!empty($project['visibility_date']))
                $user_information[$keys]['Visibility Expiry date'] = date('d-m-Y', strtotime($project['visibility_date']));
            $user_information[$keys]['Budget Approval Status'] = getBudgetApprovalStatus($project['budget_approval_status']);
            $user_information[$keys]['Project Currency'] = convertToCurrencySymbol($project['currency']);
            $user_information[$keys]['Project Guide Price'] = projectPriceByRateVariable($project['rate_variable'], $project['rate'], $project['currency']);
        }
    }
    return $user_information;
}
function projectPriceByRateVariable($rate_variable, $rate, $currency_name){
    $price = 'Negotiable';
    if ($rate > 0 && $rate_variable == 'fixed') {
        $price = convertToCurrencySymbol($currency_name).number_format($rate, 2) . " Fixed Price ";
    } elseif ($rate > 0 && $rate_variable == 'daily_rate') {
        $price = convertToCurrencySymbol($currency_name).number_format($rate, 2) . '/Day';
    }
    return $price;
}
function expertWorkLocation($expert_remote_id){
    if ($expert_remote_id == config('constants.ONLY_WORK_REMOTELY')) {
                $remote = 'Only work remotely';
            } elseif ($expert_remote_id == config('constants.ONLY_WORK_ON_SITE')) {
                $remote = 'Only work on site';
            } else {
                $remote = 'Can work remotely and on site';
            }
           return $remote;
}

function getUserInformation($result) {
    foreach ($result as $keys => $val) {
        $user_information[$keys]['First Name'] = $val['name'];
        $user_information[$keys]['Last Name'] = $val['last_name'];
        $user_information[$keys]['Type'] = $val['user_profile']['expert_type'];
        $user_information[$keys]['Email'] = $val['email'];
        $user_information[$keys]['Phone number'] = $val['phone_num'];
        $user_information[$keys]['Vat Number'] = $val['vat_country_code'].$val['vat_number'];
        $user_information[$keys]['MM ID'] = $val['mm_unique_num'];
        $user_information[$keys]['Application Date'] = date('d-m-Y', strtotime($val['created_at']));
        if (url()->previous() != url('admin/notverifiedexperts')) {
            $user_information[$keys]['Approval Date'] = adminApprovalDate($val);
        }
        $user_information[$keys]['Description'] = $val['user_profile']['describe'];
        if (is_numeric($val['user_profile']['daily_rate'])) {
            $val['user_profile']['daily_rate'] = number_format($val['user_profile']['daily_rate']);
        }
        $user_information[$keys]['Daily Rate'] = trim($val['user_profile']['currency']) . $val['user_profile']['daily_rate'];
        $invite_vendors = isset($val['serviceHubAssociatedExpert']) ? $val['serviceHubAssociatedExpert']->toArray() : isset($val['service_hub_associated_expert']) ? $val['service_hub_associated_expert'] : [];
        $user_information[$keys]['Vendor Invite'] = _count($invite_vendors) ? fetchServiceHubNamesCommaSeparated($invite_vendors) : 'NA';
        $user_information[$keys]['City'] = ($val['user_profile']['current_city']) ? $val['user_profile']['current_city'] : '';
        $user_information[$keys]['Country'] = ($val['user_profile']['country']) ? $val['user_profile']['country'] : '';
        $user_information[$keys]['Work Preferences'] = $val['user_profile']['remote_work']['name'];
        $user_information[$keys]['Bio'] = (isset($val['user_profile']['summary']) && !empty($val['user_profile']['summary'])) ? strip_tags(html_entity_decode($val['user_profile']['summary'], ENT_QUOTES)) : '';
        $skill_names = '';
        $skill_name = array();
        if (isset($val['user_skills']) && !empty($val['user_skills'])) {
            foreach ($val['user_skills'] as $s => $skill) {
                $skill_name[$s] = $skill['skill']['name'];
                if (isset($skill_name) && !empty($skill_name)) {
                    $skills = implode(', ', $skill_name);
                    $skill_names = rtrim($skills, ", \t\n");
                }
            }
        }
        $user_information[$keys]['Skills'] = ($skill_names) ? $skill_names : '';
    }
    return $user_information;
}

function getSideHustlerInformation($result) {
    foreach ($result as $keys => $val) {
        $user_information[$keys]['First Name'] = $val['name'];
        $user_information[$keys]['Last Name'] = $val['last_name'];
        $user_information[$keys]['Email'] = $val['email'];
        $user_information[$keys]['MM ID'] = $val['mm_unique_num'];
        $user_information[$keys]['Application Date'] = date('d-m-Y', strtotime($val['created_at']));
    }
    return $user_information;
}

function adminApprovalDate($buyer_data){
    $approval_date = date('d-m-Y', strtotime($buyer_data['admin_approval_time']));
    if ($approval_date == config('constants.ADMIN_APPROVAL_DATE')) {
        $approval_date = date('d-m-Y', strtotime($buyer_data['created_at']));
    }
    return $approval_date;
}
function addSkillsOffline($skill_id, $job_id, $created) {
    $skill_array = array(
        'skill_id' => $skill_id,
        'job_post_id' => $job_id,
        'created_at' => $created,
        'updated_at' => $created
    );
    return $skill_insert = JobsSkill::create($skill_array);
}

function calculateTotalDays($start_date, $end_date) {
    $difference = strtotime($end_date . ' +1 day') - strtotime($start_date);
    $total_days = floor($difference / (60 * 60 * 24));
    return $total_days;
}

function getPostJobInformation($postid) {
    return PostJob::where(['id' => $postid])
            ->first();
}

function getUserType() {
    if (Auth::Check()) {
        $user = Auth::user();
        if ($user->user_type_id == config('constants.EXPERT')) {
            return '<span class="top-user-type"> Expert<span class="top-user-account-text"> Account</span></span>';
        }
        if ($user->user_type_id == config('constants.BUYER')) {
            return '<span class="top-user-type"> Client <span class="top-user-account-text"> Account</span></span>';
        }
        if ($user->user_type_id == config('constants.VENDOR')) {
            return '<span class="top-user-type"> Vendor <span class="top-user-account-text"> Account</span></span>';
        }
    }
}

function getUserTypeString($user_type){
    switch ($user_type){
        case config('constants.BUYER'):
            return 'Client';
        case config('constants.EXPERT'):
            return 'Expert';
        case config('constants.VENDOR'):
            return 'Vendor';
        default:
            return '';
    }
}

function getExpertTypeById($expert_type_id){
    switch ($expert_type_id){
        case 1:
            return config('constants.EXPERT_TYPE_INDEPENDENT');
        case 2:
            return config('constants.EXPERT_TYPE_CONSULTANCY');
        case 3:
            return config('constants.EXPERT_TYPE_SIDE_HUSTLER');
        default:
            return '';
    }
}

function updateUserSetting($input_data) {
    $user_setting_json = Auth::user()->settings;
    $data_to_update = $input_data;
    if (!empty($user_setting_json)) {
        $setting_array = json_decode($user_setting_json, 1);
        if (is_array($setting_array)) {
            foreach ($input_data as $input_key => $input_value) {
                $setting_array[$input_key] = $input_value;
            }
            $data_to_update = $setting_array;
        }
    }
    $result = ['success' => 0];
    if (_count($data_to_update)) {
        if (User::updateUser(Auth::user()->id, ['settings' => json_encode($data_to_update)])) {
            $result = ['success' => 1];
        }
    }
    return $result;
}

function updateUserSettingsById($input_data, $user_id) {
    $user = User::find($user_id);
    $user_setting_json = $user->settings;
    $data_to_update = $input_data;
    if (!empty($user_setting_json)) {
        $setting_array = json_decode($user_setting_json, 1);
        if (is_array($setting_array)) {
            foreach ($input_data as $input_key => $input_value) {
                $setting_array[$input_key] = $input_value;
            }
            $data_to_update = $setting_array;
        }
    }
    $result = ['success' => 0];
    if (_count($data_to_update)) {
        if (User::updateUser($user->id, ['settings' => json_encode($data_to_update)])) {
            $result = ['success' => 1];
        }
    }
    return $result;
}

function calculateYear($start_date, $end_date) {
    $year = '';
    $number_of_days = abs(strtotime($end_date . ' +1 month') - strtotime($start_date));
    $years = floor($number_of_days / (365 * 60 * 60 * 24));
    if ($years > 0 && $years == 1) {
        $year = printf("%d year\n", $years);
    } else {
        if ($years > 0) {
            $year = printf("%d years\n", $years);
        } else {
            $year = '';
        }
    }
    return $year;
}

function calculateMonth($start_date, $end_date) {

    $diff = abs(strtotime($end_date . ' +1 month') - strtotime($start_date));
    $years = floor($diff / (365 * 60 * 60 * 24));
    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    if ($months > 0 && $months == 1) {
        $month = printf(" %d month\n", $months);
    } else {
        if ($months > 0) {
            $month = printf(" %d months\n", $months);
        } else {
            $month = '';
        }
    }
    return $month;
}

function getFullName($expert) {
    if (strlen($expert['name']) > 17) {
        $full_name = ucfirst(substr($expert['name'], 0, 17)) . '...';
    } else {
        $full_name = ucfirst($expert['name']) . ' ' . ucfirst(substr($expert['last_name'], 0, 1));
    }
    return $full_name;
}

function countServicePackageViewers($service_package_id) {
    return App\Components\ServicePackageComponent::viewersCount($service_package_id);
}

function getServicePackage($service_package_id) {
    return ServicePackage::getServicePackageById($service_package_id);
}
function getServicePackageDetails($service_package_id) {
    return ServicePackage::getServicePackages(['id' => $service_package_id], ['servicePackageCategory','deliverables', 'servicePackageType','servicePackageTags.Tags'],['first']);
}
function getServicePackageContractDetails($contract_id) {
    return Contract::findByCondition(['id' => $contract_id], ['servicePackage','contractDeliverables'],[],'first')->toArray();
}
function getServicePackageById($service_package_id) {
    return ServicePackage::getServicePackage($service_package_id);
}
function getServicePackageInformation($service_package_data, $user_id) {
    return $data = ['user_id' => $user_id,
        'name' => $service_package_data['name'],
        'description' => $service_package_data['description'],
        'subscription_type' => $service_package_data['subscription_type'],
        'service_packages_category_id' => $service_package_data['service_package_category'],
        'buyer_remarks' => $service_package_data['buyer_remarks'],
        'price' => strtr($service_package_data['price'], array('.' => '', ',' => '')),
        'duration' => $service_package_data['duration'],
        'publish' => $service_package_data['publish'],
        'service_package_type_id' => getServicePackageTypeId($service_package_data['service_package_type'])
    ];
}

function getServicePackageTypeId($service_package_type) {
    if (!empty($service_package_type)) {
        $existing_type = \App\Model\ServicePackageType::getSimilarTypes($service_package_type);
        if (_count($existing_type)) {
            return $existing_type->id;
        } else {
            $type = new \App\Model\ServicePackageType(['name' => trim($service_package_type), 'added_by' => 'expert']);
            if ($type->save()) {
                return $type->id;
            }
        }
    }
}
function getSavedServicePackageStatus($buyer_id,$service_package_id){
    $condition = ['buyer_id' => $buyer_id, 'service_package_id'=> $service_package_id];
    return SavedServicePackage::getSavedServicePackage($condition);
}

function getTruncatedContent($content, $limit){
    if(strlen($content)>$limit){
        $result = ucfirst(mb_substr($content,0,$limit)).'...';
    }else{
        $result = ucfirst($content);
    }
    return $result;
}

function contractStatus($communication_id){
    $communication = Communication::find($communication_id);
    $total_contracts = Contract::findByCondition(['communications_id' => $communication_id], [], [], 'count');
    if($total_contracts>1){
        $contract_detail = Contract::findByCondition(['communications_id' => $communication_id], [], ['order_by' => ['created_at', 'desc']], 'first');
    }else{
        $contract_detail = $communication->contractDetails;
    }
    $status = [
        'communication_initiated_by_buyer' => $communication->status,
        'contract_has_been_offered' => _count($contract_detail) ? 1: '',
        'contract_has_been_accepted_by_expert' => $contract_detail->status ?? '',
        'contract_has_been_marked_complete_by_expert' => $contract_detail->expert_complete_status ?? '',
        'contract_has_been_marked_complete_by_buyer' => $contract_detail->complete_status ?? '',
        'feedback_given_by_buyer' => $contract_detail->buyer_feedback_status ?? ''
    ];
    return $status;
}

function contractCurrentStage($status_detail)
{
    $contract = Contract::getFirstContractWithCommunication($status_detail);
    $status = array_filter(array_unique(array_reverse(contractStatus($status_detail))));
    if (!_count($status) || array_key_exists('communication_initiated_by_buyer', $status))
        return __('general_status_messages.contracts.interest_expressed');
    
    if (array_key_exists('contract_has_been_offered', $status))
    {
        if (strtotime($contract->created_at) != strtotime($contract->updated_at))
            return $current_status = __('general_status_messages.contracts.updated');
        return __('general_status_messages.contracts.offer');
    }
    if (array_key_exists('contract_has_been_accepted_by_expert', $status)
        || array_key_exists('contract_has_been_marked_complete_by_expert', $status))
    {
        if (strtotime($contract->job_start_date) > strtotime(Carbon::now()))
            return $current_status = __('general_status_messages.contracts.agreed');
        if (strtotime($contract->job_start_date) < strtotime(Carbon::now()))
            return __('general_status_messages.contracts.in_progress');
        if ($contract->subscription_type == config('constants.ONE_TIME_PACKAGE')
            && strtotime($contract->job_end_date) < strtotime(Carbon::now()))
                return __('general_status_messages.contracts.finished_awaiting_completion');
        
        if ($contract->finished_by)
            return __('general_status_messages.contracts.sp_ended_awaiting_review');
    }
    if (array_key_exists('contract_has_been_marked_complete_by_buyer', $status))
        return $current_status = __('general_status_messages.contracts.sp_finished_awaiting_review');
    if (array_key_exists('feedback_given_by_buyer', $status))
    {
        if ($contract->subscription_type == config('constants.ONE_TIME_PACKAGE'))
            return __('general_status_messages.contracts.sp_finished');
        
        if (!$contract->complete_status)
            return __('general_status_messages.contracts.sp_reviewed_awaiting_completion');

        return __('general_status_messages.contracts.sp_finished');
    }
}

function nextBillingDateForMonthlyRetainer($contract_id) {
    $contract = Contract::getFirstContract($contract_id);
    if (_count($contract)) {
        $contrat_start_date = $contract->job_start_date;
        if(!empty($contract->monthly_billing_date)){
            $next_due_date = date('d M Y', strtotime($contract->monthly_billing_date));
        }elseif (!$contract->finished_by) {
            $start_date = strtotime($contrat_start_date);
            $today = strtotime(Carbon::now());
            if($start_date>$today){
                $number_of_months = 1;
            }else{
                $number_of_months = getNumberOfMonthsBetweenDates($start_date, $today);
            }
            $days_to_add = (30 * $number_of_months);
            $next_due_date = date('d M Y', strtotime('+' . $days_to_add . ' days', $start_date)) . PHP_EOL;
        } else {
            $next_due_date = date('d M Y', strtotime($contract->job_end_date));
        }
        return $next_due_date;
    } else {
        return false;
    }
}
function getNumberOfMonthsBetweenDates($start_date, $today) {
    $year1 = date('Y', $start_date);
    $year2 = date('Y', $today);
    $month1 = date('m', $start_date);
    $month2 = date('m', $today);
    $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
    return $diff+1;
}
function trimFirstName($name) {
    return trim(ucfirst($name));
}
function averageRating($total_rating, $count){
    return round(($total_rating/$count)*2)/2;
}
function notAllowUserArchive(){
    $user_id = Auth::user()->id;
    $result = 1;
    if (Auth::user()->user_type_id == config('constants.EXPERT')) {
        $communication_count = Communication::fetchCommunications(['user_id' => $user_id], 'count');
        $service_package_count = ServicePackage::fetchServicePackages(['user_id' => $user_id, 'is_approved' => 'TRUE'], 'count');
        $result=$communication_count+$service_package_count;
    } elseif (Auth::user()->user_type_id == config('constants.BUYER')) {
        $result = User::findByCondition(['id' => $user_id], [], ['type' => 'count'], ['buyerCommunication']);
        $is_project_posted = PostJob::findByCondition(['user_id' => $user_id, 'publish' => 1], [], ['type' => 'count']);
        $result+= $is_project_posted;
    }
    return $result;
}
function isContractCompletionAllowed($communication_id){
    $latest_contract = Contract::findByCondition(['communications_id' => $communication_id], [], ['order_by' => ['created_at', 'desc']], 'first');
    $total_contracts = Contract::findByCondition(['communications_id' => $communication_id], [], [], 'count');
    if(_count($latest_contract)){
        if($total_contracts > 1){
            if(($latest_contract->status != config('constants.ACCEPTED'))){
                return $result = ['mark_complete_allowed' => 'yes',
                        'total_number_of_contracts' => $total_contracts,
                        'last_contract_status' => 'not accepted',
                      ];
            }
            if(($latest_contract->status == config('constants.ACCEPTED'))){
                if(strtotime($latest_contract->job_start_date) > strtotime(date('Y-m-d'))){
                    return $result = ['mark_complete_allowed' => 'no',
                        'total_number_of_contracts' => $total_contracts,
                        'last_contract_status' => 'accepted',
                        'start_date_arrived' => 'no'
                      ];
                }elseif(strtotime($latest_contract->job_start_date) <= strtotime(date('Y-m-d'))){
                    return $result = ['mark_complete_allowed' => 'yes',
                        'total_number_of_contracts' => $total_contracts,
                      ];
                }
            }
        }else{
            if(strtotime($latest_contract->job_start_date) <= strtotime(date('Y-m-d'))){
                return $result = ['mark_complete_allowed' => 'yes',
                    'total_number_of_contracts' => $total_contracts,
                  ];
            }else{
                return $result = ['mark_complete_allowed' => 'no',
                    'total_number_of_contracts' => $total_contracts,
                  ];
            }
        }
    }
}
function totalContractExtentionCount($communication_id){
  return Contract::findByCondition(['communications_id' => $communication_id], [], [], 'count');
}
function makeOrdinalNumber($number){
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}
function saveRefererUrl(){
       $refferer_url = Request::server('HTTP_REFERER');
       $ip = Request::ip();
}
function numberOfExpertsRange($lower_range){
    if($lower_range==2){
        return '2-10 Experts';
    }
    if($lower_range==11){
        return '11-30 Experts';
    }
    if($lower_range==31){
        return '31-99 Experts';
    }
    if($lower_range==101){
        return '100+ Experts';
    }
}
function homeUrlWebflow(){
    if(getenv('ENVIRONMENT')=='Production'){
       return (config('constants.STATIC_PAGES_DOMAIN').'/');
    }else{
        return url('/',[],getenv('APP_SSL'));
    }
}
function addTimeZone($date_format, $raw_date){
    $time_zone = 'UTC';
    if(!empty(trim(session('timezone'))) && strtolower(trim(session('timezone'))) != 'undefined'){
        $time_zone = session('timezone');
    }
    return Carbon::createFromFormat('Y-m-d H:i:s', $raw_date, 'UTC')->setTimezone($time_zone)->format($date_format);
}
function convertDaysToWeeks($days){
    if ($days==0){
        $time_frame = "I don't know";
        $number_of_days = 0;
    }elseif($days<5){
        $time_frame = 'Less than 1 week';
        $number_of_days = 4;
    }elseif($days<6){
        $time_frame = '1 week (5 working day)';
        $number_of_days = 5;
    }elseif($days<11){
        $time_frame = '2 weeks (10 working day)';
        $number_of_days = 10;
    }elseif($days<16){
        $time_frame = '3 weeks (15 working day)';
        $number_of_days = 15;
    }elseif($days<21){
        $time_frame = '4 weeks (20 working day)';
        $number_of_days = 20;
    }elseif($days<26){
        $time_frame = '5 weeks (25 working day)';
        $number_of_days = 25;
    }elseif($days<31){
        $time_frame = '6 weeks (30 working day)';
        $number_of_days = 30;
    }elseif($days<36){
        $time_frame = '7 weeks (35 working day)';
        $number_of_days = 35;
    }elseif($days<41){
        $time_frame = '8 weeks (40 working day)';
        $number_of_days = 40;
    }elseif($days<46){
        $time_frame = '9 weeks (45 working day)';
        $number_of_days = 45;
    }elseif($days<51){
        $time_frame = '10 weeks (50 working day)';
        $number_of_days = 50;
    }elseif($days<56){
        $time_frame = '11 weeks (55 working day)';
        $number_of_days = 50;
    }elseif($days<61){
        $time_frame = '12 weeks (60 working day)';
        $number_of_days = 60;
    }elseif($days>60){
        $time_frame = 'More than 12 weeks';
        $number_of_days = 65;
    }
    return $result=['time_frame'=> $time_frame, 'number_of_days' => $number_of_days];
}
function getFileName($document){
    $explode_document = explode('/', $document);
    $final_image = explode('_', end($explode_document));
    if (_count($final_image) == 2) {
        $file_name = $final_image[1];
    } else {
        array_shift($final_image);
        $file_name = implode('_', $final_image);
    }
    return $file_name;
}
function monthName($month_numeric){
    $date = DateTime::createFromFormat('!m', $month_numeric);
    return $month_name = $date->format('F');
}
function getCountryFlag($country_to_convert){
    $user_agent = Request::server('HTTP_USER_AGENT');
    $size = 24;
    if(strpos($user_agent, "Mac")) {
        $size = 64;
    }
    //reference http://countryflags.io/
    include public_path().'/country-codes.php';
    $iso_code = array_search(strtolower(trim($country_to_convert)), array_map('strtolower', $countries));
    if(!empty($iso_code)){
        return "https://countryflags.io/$iso_code/flat/$size.png";
    }
}
function differenceInYearsMonths($start_date, $end_date){
    $diff = abs(strtotime($end_date . ' +1 month') - strtotime($start_date));
    $years = floor($diff / (365 * 60 * 60 * 24));
    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $result = array();
    if ($years)
        $result[] = "$years Year" . ($years > 1 ? 's' : '');
    if ($months)
        $result[] = "$months Month" . ($months > 1 ? 's' : '');
    return implode(', ', $result);
}
function stripScriptingTags($input_fields_data, $excluded_fields = []){
    foreach($input_fields_data as $key => $input_field_data){
        if(is_string($input_field_data)){
            if(_count($excluded_fields) && in_array($key, $excluded_fields))
            {
                $input_fields_data[$key]= $input_field_data;
            }
            else
            {
                $input_fields_data[$key]= strip_tags($input_field_data,'<br><b>');   
            }
        }        
    }
    return $input_fields_data;    
}
function stripScriptingTagsInline($input_field){
    return $input_field != '' ? trim(strip_tags($input_field,'<br><b>')) : '';
}

function pr($data, $debug = 0) {
    echo '<pre>';
    print_r($data);
    if ($debug === 1) {
        exit;
    }
}
function closeTags($html) {
   preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
   $opened_tags = $result[1];
   preg_match_all('#</([a-z]+)>#iU', $html, $result);
   $closed_tags = $result[1];
   $len_opened = _count($opened_tags);
   if (_count($closed_tags) == $len_opened) {
       return $html;
   }
   $opened_tags = array_reverse($opened_tags);
   for ($i=0; $i < $len_opened; $i++) {
       if (!in_array($opened_tags[$i], $closed_tags)) {
           $html .= '</'.$opened_tags[$i].'>';
       } else {
           unset($closed_tags[array_search($opened_tags[$i], $closed_tags)]);
       }
   }
   return $html;
}
function getProjectCurrentStatus($posted_project) {
    $project = PostJob::getPostJobInformation($posted_project->id);
    if ($project->exists()) {
        $project_detail = PostJob::getAcceptanceContract($posted_project->id);
        if ($project_detail->publish == config('constants.PROJECT_PENDING'))
            return __('general_status_messages.projects.awaiting_approval');
        if(!$project_detail->communication_count)
            return __('general_status_messages.projects.live_status');
        if ($project_detail->publish == config('constants.APPROVED')) {
            if (!empty($project_detail->accepted_contract_id) && $project_detail->accepted_contract_complete_status == false) {
                return __('general_status_messages.projects.in_progress');
            } 
            if (!empty($project_detail->accepted_contract_id) && $project_detail->accepted_contract_complete_status == true) {
                if (!empty($project_detail->acceptedContract->expert_rating))
                    return __('general_status_messages.projects.finished');
                    
                return __('general_status_messages.projects.awaiting_review');
            }
        }
    }
    return 0;
}

function buyersCount()
{
    $user = new User;
    return [
        'approved' => $user->approvedBuyersCount(),
        'pending' => $user->pendingBuyersCount(),
        'unverified' => $user->unverifiedBuyersCount(),
        'archived' => $user->archivedBuyersCount(),
    ];
}

function servicePackagesCount()
{
    return [
        'approved' => ServicePackage::fetchServicePackages(['publish' => 'TRUE', 'is_approved' => 'TRUE'], 'count'),
        'pending' => ServicePackage::fetchServicePackages(['publish' => 'TRUE', 'is_approved' => 'FALSE', 'is_rejected' => 'FALSE'], 'count'),
        'drafted' => ServicePackage::fetchServicePackages(['publish' => 'FALSE', 'is_approved' => 'FALSE', 'is_rejected' => 'FALSE'], 'count'),
        'rejected' => ServicePackage::fetchServicePackages(['is_approved' => 'FALSE', 'is_rejected' => 'TRUE'], 'count'),
    ];
}
function calculateAverageRating($ratings)
{
    $total = 0;
    $count = 0;
    foreach ($ratings as $rating)
    {
        $count += 1;
        $total += $rating->expert_rating;
    }
    return $count ? averageRating($total, $count) : 0;
}

function getFormatedDate($date, $is_admin_panel = False)
{
    $format = config('constants.DATE_FORMAT');
    if ($is_admin_panel)
        $format = config('constants.ADMIN_DATE_FORMAT');

    return date($format, strtotime($date));
}
function getBudgetApprovalStatus($budget_approval_status)
{
    if ($budget_approval_status == config('constants.BUDGET_APPROVAL_STATUS.OWN_BUDGET'))
        return 'I own/control the budget for investments like these';

    if ($budget_approval_status == config('constants.BUDGET_APPROVAL_STATUS.NOT_APPROVED'))
        return "I don't have budget approval; I need proposals first";

    return 'I have access to budget or it has been pre-approved';
}
function buyerProjectCreatedCount($buyer_id){
    return (new PostJob)->getBuyerProjectCreatedCount($buyer_id);
} 
function buyerProjectApprovedCount($buyer_id){
    return (new PostJob)->getBuyerProjectApprovedCount($buyer_id);
} 
function submitTechnographicTracking($skills_result, $company_name, $domain){
    if($domain){
       $tech_list=(is_array($skills_result) && _count($skills_result))? implode(', ', array_unique($skills_result)) : 'No Match found!!';
       (new SegmentComponent)->technographicTracking(
              $company_name,
              $domain,
              $tech_list,
              config('constants.TECHNOGRAPHIC_SUBMITTED')
       );
       }
}


function getVatApiResponse($end_point) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.vatsense.com/1.0/'.$end_point);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_USERPWD, 'user:'.env('VATSENSE_KEY'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response, true);
    return $response['data'] ?? ['valid' =>false];
}

function getCountryVAT($country_code, $rates){
    foreach ($rates as $country){
        if ($country['country_code'] == $country_code){
            return $country['standard']['rate'];
        }
    }
}

function hasSubscribed($user_id){
    return UsersCommunication::getEmailSubscriptionStatus($user_id);
}

function minimumMatchingSkillChunk($skill)
{
    $skill_length = strlen($skill);
    $number_of_letters_to_search = round((config('constants.MINIMUM_PERCENTAGE_OF_SKILL_TO_MATCH')*$skill_length)/100);
    if($skill_length <= 10)
        $number_of_letters_to_search = $skill_length;
    return substr($skill, 0, $number_of_letters_to_search);
}

function getClearbitData($term) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://autocomplete.clearbit.com/v1/companies/suggest?query='.$term,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_TIMEOUT => 1000,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
function emailTemplateFooter($email, $show_unsubscribe_link = 1) {
    $footer_html = '<table width="100%" style="max-width:800px; background:#fff" align="center">
      <tr>
         <td align="center" style="border-top:#252161 solid 3px; margin-bottom:15px; padding-bottom:20px;">
            <p style="padding:0px 5px; margin-top: 15px; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
            Need more help? Visit our <a href="' . getFaqUrl() . '" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;
                text-decoration:none; color:#252161" title="click here">FAQ</a>.</p>
            <p style="padding:0px 5px; margin-top: 15px; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
            Copyright © ' . date('Y') . ' MeasureMatch Ltd. All Rights Reserved.</p>
            <p style="padding:0px 5px; margin: 10px 0; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
            Company Number: 10199524 | VAT Number: 253943881 | Address: 280 Mare Street, London, England, E8 1HE</p>';

        if ($show_unsubscribe_link) {
            $footer_html .= '<p style="padding:0px 5px; margin-top: 15px; margin-bottom: 2px; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                To unsubscribe <a href="' . getUnsubscribeUrl($email) . '" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; '
                . 'text-decoration:none; color:#252161" title="click here">click here</a>.</p>';
        }
        $footer_html .= '<a style="padding:0px 10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; text-decoration:none; color:#252161; '
            . 'padding-right:5px;" href="' . getPrivacyPolicyLink() . '" title="Privacy Policy">Privacy Policy</a>|'
            . '<a style="padding:0px 10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; text-decoration:none; color:#252161;" href="' . getTermConditionsLink() . '" title="Terms of Service">Terms of Service</a>
         </td>
      </tr>
    </table>';
        return $footer_html;
    }

function technographicBuyerUrl()
{
    $user_id = Auth::user()->id;
    $buyer_data = BuyerProfile::getBuyerDetail($user_id);
    $response = getClearbitData($buyer_data->company_url);
    $response = json_decode($response, 1);
    $response = $response[0] ?? '';
    $query_string = $response ? '?name='.$response['name'].'&logo='.$response['logo'].'&domain='.$response['domain'] : 
                            '?name='.$buyer_data->company_name.'&domain='.$buyer_data->company_url;
    return $query_string;
}    

function getCoreElementsMissing($user_profile){

    $core_elements_missing['profile_photo'] = $user_profile['user_profile']['profile_picture'] ? 'profile-completion-completed-element' : "";
    $core_elements_missing['profile_title'] = $user_profile['user_profile']['describe'] ? 'profile-completion-completed-element' : "";
    $core_elements_missing['city_country'] = $user_profile['user_profile']['current_city'] && $user_profile['user_profile']['country'] ? 'profile-completion-completed-element' : '';
    $core_elements_missing['daily_rate'] = $user_profile['user_profile']['daily_rate'] ? 'profile-completion-completed-element' : '';
    $core_elements_missing['remote_id'] =$user_profile['user_profile']['remote_id'] ? 'profile-completion-completed-element' : '';
    $core_elements_missing['summary'] = $user_profile['user_profile']['summary'] ? 'profile-completion-completed-element' : '';
    $core_elements_missing['skills'] = "";
    $core_elements_missing['skills_details'] = "";
    $core_elements_missing['total_skills'] = sizeof($user_profile['user_skills']);
    if ($core_elements_missing['total_skills'] >= config('constants.MINIMUM_SKILLS_AND_TOOLS_COUNT_FOR_PROFILE_COMPLETEION')){
        $core_elements_missing['skills'] = 'profile-completion-completed-element';
        $core_elements_missing['skills_details'] = "hidden";
    }
    return $core_elements_missing;
}

function calculateContractVATValues($proposal_value, $expert_vat_status, $expert_country, $buyer_vat_status, $buyer_country){

    $expert_location = strtoupper($expert_country);
    $buyer_location = strtoupper($buyer_country);
    $mm_fee_percentage = config('constants.MEASUREMATCH_FEE');
    $UK_VAT = config('constants.VAT') / 100;
    $proposal_value_vat = 0;
    $expert_country_details = getCountryVatDetails($expert_location);
    $buyer_country_details = getCountryVatDetails($buyer_location);
    $expert_vat = $expert_country_details['vat_value'] / 100;
    $buyer_vat = $buyer_country_details['vat_value'] / 100;
    $expert_in_eu = $expert_country_details['is_eu_country'];
    $buyer_in_eu = $buyer_country_details['is_eu_country'];
    $reverse_charge_invoice = false;
    $reverse_charge_mm_fee = false;

    if (!$expert_vat_status || (!$buyer_vat_status && $buyer_in_eu && !$expert_in_eu)) {
        $proposal_value_vat = 0;
    } else if ($buyer_location == $expert_location || (!$buyer_vat_status && $buyer_in_eu)) {
        $proposal_value_vat = $proposal_value * $expert_vat;
    }
    if ($expert_in_eu && $buyer_in_eu && $expert_vat_status && $buyer_vat_status && $proposal_value_vat == 0) {
        $reverse_charge_invoice = true;
    }

    $mm_fee = $proposal_value * $mm_fee_percentage;
    if (!$expert_in_eu) {
        $mm_fee_vat = 0;
    } else {
        if ($expert_vat_status) {
            $mm_fee_vat = 0;
            if ($expert_location == 'GB') {
                $mm_fee_vat = $UK_VAT * $mm_fee;
            } else {
                $reverse_charge_mm_fee = true;
            }
        } else {
            $mm_fee_vat = $UK_VAT * $mm_fee;
        }
    }
    $total_invoice = $proposal_value + $proposal_value_vat;
    $total_expert_will_receive = $total_invoice - $mm_fee - $mm_fee_vat;
    $total_mm_fee = $mm_fee + $mm_fee_vat;

    $values = [
        'subtotal' => $proposal_value,
        'vat' => $expert_country_details['vat_value'],
        'vat_value' => $total_invoice - $proposal_value,
        'total_buyer_will_pay' => $total_invoice,
        'mm_fee' => $mm_fee,
        'mm_fee_vat' => $mm_fee_vat,
        'total_mm_fee' => $total_mm_fee,
        'total_expert_will_receive' => $total_expert_will_receive,
        'reverse_charge_invoice' => $reverse_charge_invoice,
        'reverse_charge_mm_fee' => $reverse_charge_mm_fee,
    ];
    return $values;
}

function getCountryVatDetails($country_code){
    $country = CountryVatDetails::where('country_code', $country_code)->get()->first();
    if ($country) {
        $details['vat_value'] = $country->value;
        $details['vat'] = $country->value;
        $details['is_eu_country'] = false;
        if ($country->eu && $country->eu == '1') {
            $details['is_eu_country'] = true;
        }
        return $details;
    }
    return null;
}

function convertToCurrencySymbol($name) {
    if ($name === 'GBP') {
        return '£';
    } else if ($name === 'EUR') {
        return '€';
    } else {
        return '$';
    }
}

function isBuyer()
{
    if (Auth::Check()) {
        return Auth::user()->user_type_id == config('constants.BUYER') ? true : false;
    }
    return false;
}

function isExpert()
{
    if (Auth::Check()) {
        return Auth::user()->user_type_id == config('constants.EXPERT') ? true : false;
    }
    return false;
}

function isVendor()
{
    if (Auth::Check()) {
        return Auth::user()->user_type_id == config('constants.VENDOR') ? true : false;
    }
    return false;
}



function isAdmin()
{
    if (Auth::Check()) {
        return Auth::user()->user_type_id == config('constants.ADMIN') ? true : false;
    }
    return false;
}

function getCompanyFirstName($company_name) {
    return explode(' ', $company_name)[0];
}

function getExpertInformation($user_id) {
    return (new \App\Model\BusinessInformation())->getUserBusinessInformation($user_id);
}

function getUserRole($user_type_id) {
    switch ($user_type_id) {
        case config('constants.EXPERT');
            return 'expert';
        case config('constants.BUYER');
            return 'buyer';
        default;
            return 'vendor';
    }
}

function isBuyerAndVendor($user_type_id) {
    return in_array($user_type_id, [config('constants.BUYER'), config('constants.VENDOR')]);
}

function fetchServiceHubNamesCommaSeparated($service_hub)
{
    $service_hub_name = [];
    $all_service_hubs = 'NA';
    foreach($service_hub as $service_hub)
    {
        $service_hub_name[] = $service_hub['service_hub']['name'];
    }
    if(_count($service_hub_name))
        $all_service_hubs = implode (', ', $service_hub_name);
    return $all_service_hubs;
}
function createExternalUrl($url) {
    return ((strpos($url, 'http') !== false) || strpos($url, 'https') !== false) ? $url : 'http://'.$url;
    if ((strpos($url, 'http') !== false) || strpos($url, 'https') !== false) {
        $final_url = $url;
    } else {
        $final_url = 'http://'.$url;
    }
    return $final_url;
}

if (!function_exists('is_countable')) {
   function is_countable($var) {
       return (is_array($var) || $var instanceof Countable);
   }
}

function _count($input){
    if (is_countable($input))
        return count($input);
    $input = json_decode(json_encode($input, 1), 1);
    return is_countable($input) ? count($input) : 0;
}