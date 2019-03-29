<?php

namespace App\Http\Controllers;

use App\Model\ServiceHub;
use Illuminate\Http\Request;
use Auth;
use Redirect;
use Validator;
use DB;
use Storage;
use Image;
use Carbon\Carbon;
use App\Model\{BusinessInformation,
    User,
    UserProfile,
    Skill,
    UsersLanguage,
    UsersSkill,
    EmploymentDetail,
    EducationDetail,
    UsersCertification,
    PostJob,
    BuyerProfile,
    Communication,
    UsersCommunication,
    Contract,
    ServicePackage,CountryVatDetails};
use App\Components\{SegmentComponent, Common, Email};

class UserProfileController extends Controller {

    /**
     * Construct Method
     */
    public function __construct() {
        $this->middleware('auth', ['except' => ['getbase64']]);
        $ssl = getenv('APP_SSL');
    }

    /**
     * User Profile
     *
     * @return type
     */

    public function accountFrozen(){

        $user_type = Auth::user()->user_type_id;
        return view('pages.accountfrozen', compact('user_type'));
    }

    public function userProfile(Request $request) {
        $auth_user = Auth::user();
        if ($auth_user->user_type_id == config('constants.EXPERT')) {
            $user_id = $auth_user->id;
            $expert_info_section = $request->segment(2);
            $total_user_skills = UsersSkill::getUserSkillsByUserId($user_id, 'count');
            $total_user_tools = UsersSkill::getUserToolsByUserId($user_id, 'count');
            if ($request->ajax()) {
                $view_name = $this->viewFinderForProfile()[$expert_info_section];
                $user_profile = User::userDetailWithFullProfile($user_id, $expert_info_section, true);
                return view('user_profile.'.$view_name, compact('user_profile', 'total_user_skills', 'total_user_tools'));
            } else {

                if (session()->has('first_login') == true) {
                    session()->forget('first_login');
                    $basic_profile_completeness = UserProfile::profileDetail($auth_user->id);
                    if (($basic_profile_completeness['completed_required_seven_experts_fields'] == config('constants.COMPLETED'))
                        && ($auth_user->admin_approval_status == config('constants.APPROVED'))) {
                        return redirect()->intended('expert/projects-search');
                    }
                }

                $skills = Skill::getRandomSkills();
                $user_profile = User::userDetailWithFullProfile($user_id, $expert_info_section);
                $core_elements_missing = getCoreElementsMissing($user_profile);
                return view('user_profile.seller_profile', compact('user_profile', 'skills', 'total_user_tools', 'total_user_skills', 'core_elements_missing', 'expert_info_section', 'auth_user'));
            }
        } else {
            return Redirect::To('/');
        }
    }

    private function viewFinderForProfile() {
        return [
            'profile-summary' => 'expert_profile_bio',
            'profile-skills' => 'expert_profile_skills',
            'work-history' => 'expert_experience_detail',
            'profile-education' => 'expert_educational_details'
        ];
    }


    /**
     * Seller Profile Detail Method
     *
     * @param Request $request
     *
     * @return type
     *
     *client search profile view of seller
     */
    public function sellerprofiledetail(Request $request) {
        if (!buyerAuth()) {
            return redirect('/');
        }
        $id = app('request')->input('sellerid');
        return redirect("buyer/expert-profile/$id");
    }

    function distinct($items) {
        $result = [];

        for ($i = 0; $i < sizeof($items); $i++)
            if (!array_key_exists($items[$i]['user_id'], $result) || $result[$items[$i]['user_id']]['id'] > $items[$i]['id'])
                $result[$items[$i]['user_id']] = $items[$i];

        return $result;
    }

    /**
     * EditProfile  Method
     *
     * @return type
     */
    public function EditProfile() {
        return view('user_profile.edit_seller_profile');
    }

    /**
     * Edit Summary fun Method
     *
     * @param Request $request
     *
     * @return type
     */
    public function expertBasicInformation(Request $request) {
        $auth_user = Auth::user();
        if ($auth_user) {
            $user_id = $auth_user->id;
            $edited_summary_data = $request->all();
            if (isset($edited_summary_data['field_name']) && $edited_summary_data['field_name'] == 'get_info') {
                $user_profile = User::userDetailWithFullProfile($user_id, 'profile-summary');
                return view('user_profile.expert_profile_left_section_view', compact('user_profile'));
            }
            $rules = array(
                'field_name' => 'required',
                'value' => 'required',
            );
            $validator = Validator::make($edited_summary_data, $rules);
            if ($validator->fails()){
                return trans('custom_validation_messages.expert_profile.profile_validation_error');
            }
            $summary_detail = UserProfile::where('user_id', $user_id)->first();
            if (isset($edited_summary_data['multiple']) && $edited_summary_data['multiple'] === 'true') {
                $split_location_value = explode('_', $edited_summary_data['value']);
                $summary_detail->current_city = $split_location_value[0];
                $summary_detail->country = $split_location_value[1];
            } else {
                $fieldToUpdate = $edited_summary_data['field_name'];
                $summary_detail->$fieldToUpdate = $edited_summary_data['value'];
            }
            $summary_detail->save();
            $basic_profile_completeness = UserProfile::profileDetail($auth_user->id);
            if (empty($basic_profile_completeness['expert_profile_review_email_to_admin_date']) || $basic_profile_completeness['expert_profile_review_email_to_admin_date'] == NULL) {
                sendEmailToAdminIfBasicProfileIsCompleted();
            }
            return 'success';
        } else {
            return Redirect::To('login')->with('warning', 'Session Expired.');
        }
    }

    public function editsellerbio(Request $request) {
        $id = Auth::user()->id;
        $edit_expert_profile = $request->all();
        $rules = array(
            'bio' => 'required'
        );
        $validator = Validator::make($edit_expert_profile, $rules);
        if ($validator->fails()) {

            $error = $validator->errors()->toArray();
            return $error;
        } else {

            $bio_detail = UserProfile::find($edit_expert_profile['bioid']);
            $summary_exists = 0;
            if (!empty($bio_detail->summary)) {
                $summary_exists = 1;
            }
            $bio_detail->summary = stripScriptingTagsInline($edit_expert_profile['bio']);

            if ($bio_detail->save()) {
                $basic_profile_completeness = UserProfile::profileDetail(Auth::user()->id);
                if (empty($basic_profile_completeness['expert_profile_review_email_to_admin_date']) || $basic_profile_completeness['expert_profile_review_email_to_admin_date'] == NULL) {
                    sendEmailToAdminIfBasicProfileIsCompleted();
                }
                if ($basic_profile_completeness['completed_required_seven_experts_fields'] == TRUE && ($summary_exists != 1) && Auth::user()->admin_approval_status == 1) {
                    return Redirect::To('expert/projects-search')->with('success', 'Now that your profile has been completed, search for a Project.');
                }
                return Redirect::To('expert/profile-summary')->with('warning', 'Account updated.');
            }
        }
    }

    /**
     * Edit Seller Rate Method
     *
     * @param Request $request
     *
     * @return type
     *
     * edit daily rate
     */
    public function editsellerrate(Request $request) {
        $id = Auth::user()->id;
        $formData = $request->all();

        $rules = array(
            'daily_rate' => 'required|numeric',
            'rate_variable' => 'required',
            'currency' => 'required'
        );
        $validator = Validator::make($formData, $rules);
        if ($validator->fails()) {

            $error = $validator->errors()->toArray();
            return $error;
        } else {
            $ratedetail = UserProfile::find(trim($formData['rateid']));
            $ratedetail->daily_rate = trim($formData['daily_rate']);
            $ratedetail->rate_variable = trim($formData['rate_variable']);
            $ratedetail->currency = trim($formData['currency']);

            if ($ratedetail->save()) {
                echo 1;
                die;
            }
        }
    }

    /**
     * Seller Logo Method
     *
     * @param Request $request
     *
     * @return type
     *
     * edit seller profile pic
     */
    public function sellerlogo(Request $request) {
        $id = Auth::user()->id;
        $form_data = $request->all();
        if (isset($form_data['base64image']) && !empty($form_data['base64image'])) {
            $profile_picture_exists = UserProfile::getNullPictureCount($id);
            $file = $form_data['base64image'];
            if ($file != '') {
                list($type, $data) = explode(';', $file);
                list(, $data) = explode(',', $file);
                $explode_data = explode('base64', $file);
                $explode_data_img = explode('image/', $explode_data[0]);
                $data = base64_decode($data);
                $new_extension = str_replace(";", "", $explode_data_img[1]);
                $image_file_name = rand() . "." . $new_extension;
                $s3 = Storage::disk('s3');
                $file_path = '/' . $image_file_name;
                $s3_bucket = getenv("S3_BUCKET_NAME");
                $bucket_url = getenv('BUCKET_URL');
                try {
                    $img = Image::make($data);
                    $img->encode($new_extension);
                    $s3->put($file_path, (string) $img, 'public');
                    $full_url = $bucket_url . "/" . $s3_bucket . "" . $file_path;
                } catch (\Exception $ex) {
                    $full_url = "";
                }
            } else {
                $full_url = "";
            }

            if ($full_url != '') {
                $updated_query = UserProfile::updateProfilePicture($id, $full_url);
                $basic_profile_completeness = UserProfile::profileDetail(Auth::user()->id);
                if ($updated_query) {
                    if (empty($basic_profile_completeness['expert_profile_review_email_to_admin_date']) || $basic_profile_completeness['expert_profile_review_email_to_admin_date'] == NULL) {
                        sendEmailToAdminIfBasicProfileIsCompleted();
                    }
                    return redirect('expert/profile-summary');
                } else {
                    return back()->with('image_error', config('constants.IMAGE_COULD_NOT_SAVE'));
                }
            } else {
                return back()->with('image_error', config('constants.IMAGE_COULD_NOT_SAVE'));
            }
        }
        return back()->with('image_error', config('constants.IMAGE_COULD_NOT_SAVE'));
    }

    /**
     * Job View Method
     *
     * @param Request $request
     *
     * @return type
     *
     * seller search profile view of buyer
     */
    public function job_view(Request $request) {
        if (Auth::user()->id && Auth::user()->user_type_id == config('constants.EXPERT') && Auth::user()->admin_approval_status == config('constants.TRUE')) {
            $id = app('request')->input('sellerid');
            if (!empty($id) && ctype_digit($id)) {
                $job_preview = PostJob::getSkillsWithPostId($id);
                if (!empty($job_preview) && $job_preview['rebook_project'] === false) {
                    if (($job_preview['visibility_date'] && strtotime($job_preview['visibility_date']) < time())|| $job_preview['publish']!=config('constants.PUBLISHED'))
                        return view('sellerdashboard.expiredProjectsView');
                    $buyer_profile = BuyerProfile::getBuyerDetail($job_preview['user_id']);
                    $post_company = BuyerProfile::getPostCompany($buyer_profile->id);
                    jobViewUpdate($job_preview['id'], Auth::user()->id);
                    $job_id = $job_preview['id'];
                    $user_id = Auth::user()->id;
                    $buyer_id = $post_company->user_id;
                    $response = $this->showInterestStatus($user_id, $buyer_id, $job_id);
                    $communication_id = '';
                    if (isset($response) && _count($response)) {
                        $status = $response[0]->status;
                        $communication_id = $response[0]->id;
                    } else {
                        $status = 3;
                    }
                    $project_type = 'project';
                    return view('buyer.job_view', compact('job_preview', 'post_company', 'status', 'communication_id', 'id', 'project_type'));
                } else {
                    return view('errors.404');
                }
            } else {
                return view('errors.404');
            }
        } else {
            return redirect('/');
        }
    }

    /**
     * Show Interest Method
     *
     * @param Request $request
     *
     * @return type
     */
    public function showInterest(Request $request) {
        if (Auth::check()) {
            $form_data = $request->all();
            $cover_letter_message = NULL;
            $rules = array(
                'user_id' => 'required',
                'buyer_id' => 'required',
                'job_post_id' => 'required'
            );
            $validator = Validator::make($form_data, $rules);
            if ($validator->fails()) {
                return $validator->errors()->toArray();
            } else {
                $user_id = Auth::user()->id;
                $check_duplicate_interest = Communication::communicationCountByJobPostId($user_id, $form_data['job_post_id']);
                $user_profile = User::userFullProfileByUserId($user_id);
                $expression_of_interest_message = config('constants.MAX_SHOW_INTEREST_PROJECT');
                $user_skill = $user_profile['user_skills'] ?? array();
                $user_bio = $user_profile['user_profile']['summary'] ?? array();
                $user_pic = $user_profile['user_profile']['profile_picture'] ?? "";
                if ((empty($user_bio)) OR ( empty($user_skill)) OR ( empty($user_pic))) {
                    $expression_of_interest_message = config('constants.ADD_PROFILE_PICTURE_BIO_SKILL');
                } elseif (Auth::user()->admin_approval_status != config('constants.APPROVED')) {
                    $expression_of_interest_message = config('constants.PROFILE_UNDER_REVIEW');
                } elseif (!empty($user_bio) && !empty($user_skill) && !empty($user_pic)) {
                    if ($check_duplicate_interest > 0) {
                        $expression_of_interest_message = "You have already shown interest on this job.";
                        return json_encode(array('success' => '2', 'msg' => $expression_of_interest_message));
                    }
                    $communication = new Communication;
                    $communication->user_id = $form_data['user_id'];
                    $communication->buyer_id = $form_data['buyer_id'];
                    $communication->job_post_id = $form_data['job_post_id'];
                    $communication->status = 0;
                    $response = $communication->save();

                    if ($response == true) {
                        $post_job = PostJob::getPostInformation($form_data['job_post_id']);
                        $job_title = $post_job[0]['job_title'] ?? '';
                        if (hasSubscribed($form_data['user_id'])) {
                            Email::expressionOfInterestEmailToExpert(['buyer_id' => $form_data['buyer_id'],
                                'expert_id' => $form_data['user_id'], 'project_id' => $form_data['job_post_id']]);
                        }
                        if (hasSubscribed($form_data['buyer_id'])) {
                            Email::expressionOfInterestEmailToBuyer(['buyer_id' => $form_data['buyer_id'], 'expert_id' => $form_data['user_id'],
                                'project_id' => $form_data['job_post_id'] ,'communication_id'=>$communication->id ]);
                        }

                        if (isset($form_data['cover_letter_message']) && !empty($form_data['cover_letter_message'])) {
                            $cover_letter_message = $form_data['cover_letter_message'];
                            $message_detail = Common::saveCoverLetterMessage($form_data['user_id'],
                                $form_data['buyer_id'], $form_data['cover_letter_message'], $communication['id']);

                        } else{
                            Common::saveExpertMessageShowInterestInProjectToBuyer($form_data['user_id'],
                                $form_data['buyer_id'], $job_title, $communication);
                        }
                        (new SegmentComponent)->negotiationsTracking(
                            $user_id, $form_data['job_post_id'], $communication->id, $job_title, $cover_letter_message, config('constants.EOI_SUBMITTED')
                        );
                        if(!empty($cover_letter_message)){
                            (new SegmentComponent)->messagesTracking(
                                $user_id,
                                $message_detail->id,
                                $form_data['buyer_id'],
                                $cover_letter_message,
                                NULL,
                                config('constants.EOI_MESSAGE'),
                                config('constants.MESSAGE_SENT')
                            );
                        }
                        $message = Common::saveExpertMessageShowInterestInProjectToExpert($form_data['user_id'],
                            $form_data['buyer_id'], $job_title, $communication);
                        if ($message) {
                            Email::sendEOIMailToAdmin(['buyer_id' => $form_data['buyer_id'], 'expert_id' => $form_data['user_id'],
                                'project_id' => $form_data['job_post_id'], 'communication_id' => $communication->id]);
                        }

                        $result = array('success' => config('constants.APPROVED'), 'data' => $message);
                        return json_encode($result);
                    } else {
                        $result = array('success' => config('constants.PENDING'), 'msg' => config('constants.TRY_AGAIN'));
                        return json_encode($result);
                    }
                }
                return json_encode(array('success' => config('constants.REJECTED'), 'msg' => $expression_of_interest_message));
            }
        } else {
            return json_encode(array('success' => config('constants.REJECTED'), 'msg' => config('constants.PLEASE_LOGIN_TO_CONTINUE')));
        }
    }

    public function removeInterest(Request $request) {
        if (Auth::check()) {
            $remove_interest_data = $request->all();
            $rules = array(
                'user_id' => 'required',
                'buyer_id' => 'required',
                'job_post_id' => 'required'
            );
            $validator = Validator::make($remove_interest_data, $rules);
            if ($validator->fails()) {

                $error = $validator->errors()->toArray();
                return $error;
            } else {
                $id = Auth::user()->id;
                $interest_data = Communication::getFirstCommunicationWithJobId($id, $remove_interest_data['job_post_id'], '1');
                if (_count($interest_data)) {
                    $result = array('success' => '2', 'msg' => 'This interest cannot be removed, as client has already accepted the interest.');
                    $json_response = json_encode($result);
                    return $json_response;
                }

                $remove_interest = Communication::deleteCommunication($id, $remove_interest_data['job_post_id']);

                if ($remove_interest == true) {
                    $result = array('success' => '1', 'msg' => '');
                    $json_response = json_encode($result);
                    return $json_response;
                } else {
                    $result = array('success' => '2', 'msg' => config('constants.ERROR_OCCURED'));
                    $jsonStr = json_encode($result);
                    return $json_response;
                }
            }
        } else {
            $result = array('success' => '2', 'msg' => config('constants.PLEASE_LOGIN_TO_CONTINUE'));
            $json_response = json_encode($result);
            return $json_response;
        }
    }

    /**
     * Show Interest Status Method
     *
     * @param type $user_id
     * @param type $buyer_id
     * @param type $job_id
     *
     * @return string
     */
    public function showInterestStatus($user_id, $buyer_id, $job_id) {

        $checkStatus = DB::table('communications')
            ->where('buyer_id', '=', $buyer_id)
            ->where('user_id', '=', $user_id)
            ->where('job_post_id', '=', $job_id)
            ->get();
        if ($checkStatus) {
            $response = $checkStatus;
        } else {
            $response = '';
        }

        return $response;
    }

    /**
     * Edit Seller Account Method
     *
     * @return type
     */
    public
        function editSellerAccount()
    {
        if (!expertAuth())
            return redirect('/');
        try
        {
            $id = Auth::user()->id;
            $user_data = (new User)->userDetailsWithCommunicationStatus($id);
            $user_communication = [];
            $business_information = new BusinessInformation();
            $business_information = $business_information->getUserBusinessInformation($id);
            if (_count($business_information))
            {
                $business_type = $business_information->type;
                $business_details = $business_information->businessDetails;
                $business_address = $business_information->businessAddress;
            }
            $company_name = $company_website = $birth_day = $birth_month = $birth_year = '';
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
            if (!empty($user_data->UsersCommunication->toArray()))
            {
                $user_communication_arr = $user_data->UsersCommunication->toArray();
                $user_communication[] = $user_communication_arr[0]['email_subscription'];
            }
            if (!empty($user_data->date_of_birth))
            {
                $birth_day = date('d', strtotime($user_data->date_of_birth));
                $birth_month = date('m', strtotime($user_data->date_of_birth));
                $birth_year = date('Y', strtotime($user_data->date_of_birth));
            }
            $countries = (new CountryVatDetails)->getAllCountryVatDetails();
            $contracts = Contract::findByCondition(['user_id' => $id, 'status' => 1, 'parent_contract_id' => null],
                    ['communication.extensionContracts']);
            return view('user_profile.edit_seller_account',
                compact('user_data',
                    'user_communication',
                    'contracts',
                    'countries',
                    'business_type',
                    'business_details',
                    'company_name',
                    'company_website',
                    'business_address',
                    'birth_day',
                    'birth_month',
                    'birth_year'));
        } catch (\Exception $ex)
        {
            throw new \Exception($ex);
        }

    }

    /**
     * Upd Seller Account Method
     *
     * @param Request $request
     *
     * @return type
     */
    public function updSellerAccount(Request $request) {
        $form_data = $request->all();

        $rules = array(
            'name' => 'required',
            'email' => 'required|unique:users,email,'.Auth::user()->id,
            'vat_country_code' => 'required_if:have_vat,1',
            'vat_number' => 'required_if:have_vat,1',
            'date_of_birth' => 'nullable|date'
        );
        $validator = Validator::make($form_data, $rules);
        if ($validator->fails()) {
            return Redirect::To('/expert/settings')->with('warning', 'Error: Validation fail. Please try again.');
        } else {
            $vat_country_code = $request->get('vat_country_code') ? explode('_', $request->get('vat_country_code')) : [];
            $is_eu =  _count($vat_country_code) &&  $vat_country_code[0] ==1 ? true : false;
            $vat_country_code = _count($vat_country_code) ? $vat_country_code[0] ==1 ? $vat_country_code[1] : $vat_country_code[1] : '';
            $vat_number = $request->get('vat_number') ?? '';
            $have_vat_number = $request->get('have_vat') ?? '';
            $validate_vat_number =  $have_vat_number ? $is_eu ? getVatApiResponse('validate?vat_number='.$vat_country_code.$vat_number) : ['valid' =>true] : ['valid' =>false];
              if ($have_vat_number) {
                if ($validate_vat_number['valid']==false) {
                    $validator->errors()->add('vat_number', 'Please input a valid VAT number.');
                    return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
                }
            }
            $id = Auth::user()->id;
            $users = User::find($id);
            $users->name = $form_data['name'];
            $users->last_name = $form_data['last_name'];
            $users->date_of_birth = ($form_data['date_of_birth'] != '') ? 
                    (date('Y-m-d', strtotime($form_data['date_of_birth']))) : 
                    ((!empty($users->date_of_birth)) ? ($users->date_of_birth) : 
                    null);
            if ($validate_vat_number['valid']!=false  || !$have_vat_number) {
                $users->vat_country_code = $have_vat_number ? $vat_country_code : '';
                $users->vat_number = $have_vat_number ? $form_data['vat_number'] : '';
            }

            $users->email = $form_data['email'];
            $users->phone_num = (!empty($form_data['country_code']))? $form_data['country_code'].'-'.$form_data['phone_num'] : $form_data['phone_num'];

            $response = $users->save();
            if ($response == true) {
                $result = array('success' => '1', 'msg' => 'Data Saved');
                $jsonStr = json_encode($result);
                return Redirect::To('/expert/settings')->with('warning', 'Basic information updated.');
            } else {
                $result = array('success' => '0', 'msg' => 'Please try again,due to some problem unable to update.');
                $jsonStr = json_encode($result);
                echo $jsonStr;
                die();
            }
        }
    }

    function updateLoggedInStatus(Request $request) {
        $formData = $request->all();
        $id = $formData['id'];
        $user = User::find($id);
        $user->first_time_logged_in = 1;
        $user->save();
        return 1;
    }

    public function deleteWorkHistory(Request $request) {
        $form_data = $request->all();
        EmploymentDetail::deleteEmployementDetail($form_data['id']);
        return 1;
    }

    public function deleteCollegeUniversity(Request $request) {
        $education_id = $request->all();
        EducationDetail::deleteEducationDetail($education_id['id']);
        return 1;
    }

    public function deleteCertificateAndCourses(Request $request) {
        $course_id = $request->all();
        UsersCertification::deleteCertificateAndCourses($course_id['id']);
        return 1;
    }

    public function getbase64(Request $request) {
        try {
            $formData = $request->all();
            if (isset($formData['url'])) {
                if ($formData['url'] != "" && $formData['url'] != "no image") {
                    $type = pathinfo($formData['url'], PATHINFO_EXTENSION);
                    $data = file_get_contents($formData['url']);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                } else {
                    $ssl = getenv('APP_SSL');
                    $base64 = url(config('constants.DEFAULT_PROFILE_IMAGE'), [], $ssl);
                }
                return $base64;
            } else {
                return 0;
            }
        } catch (\Exception $ex) {
            return 0;
        }
    }
    
    public function buyerExpertProfileView(Request $request)
    {
        $auth_user = Auth::user();
        $buyer_id = $auth_user->id;
        $is_vendor = isVendor();
        $vendor_service_hubs = [];
        if ($is_vendor) {
            $vendor_service_hubs = (new ServiceHub())->getVendorServiceHubAndAssociatedExperts($buyer_id, $request->id);
        }
        $expert = User::userFirstDetailWithFullProfile($request->id, $is_vendor);
        $name = ucfirst($expert->name) . " " . ucfirst(substr($expert->last_name, 0, 1));
        $tools = UsersSkill::getUserToolsByUserId($expert->id);
        $skills = UsersSkill::getUserSkillsByUserId($expert->id);
        $service_packages = ServicePackage::fetchServicePackages(
                ['user_id' => $expert->id, 'is_approved' => TRUE, 'is_hidden' => FALSE],
                '',
                ['servicePackageTags.Tags']
            );
        $other_experts = UserProfile::getRandomActiveExpertProfiles([$expert->id], 4);
        $active_projects = Communication::getCommunicationInformationWithBuyerId($expert->id, $buyer_id);
        $projects_list = PostJob::getPostInformationWithUserIdAndDate($buyer_id);
        $business_detail_id = (new BusinessInformation())->getUserBusinessDetailId($buyer_id);
        return view('user_profile.buyer_expert_profile_view',
            compact('expert',
                'name',
                'tools',
                'skills',
                'service_packages',
                'other_experts',
                'projects_list',
                'active_projects',
                'is_vendor',
                'vendor_service_hubs',
                'business_detail_id')
            );
    }

    public function updateExpertCountryScript(){
        $ssl = getenv('APP_SSL');
        $handle = fopen(url('/Expert_profiles(location).csv', [], $ssl), "r");
        $data_to_update = [];
        if ($handle) {
            $count=0;
            while (($raw_data = fgets($handle)) !== false) {
                if($count!=0){
                    $user_data = explode(',', $raw_data);
                    $data_to_update[] = [
                        'id' => $user_data[0],
                        'city' => str_replace('"', '', (_count($user_data) == 7)? $user_data[4].', '.$user_data[5]:$user_data[4]),
                        'country' => (_count($user_data) == 7)? $user_data[6]:$user_data[5]
                    ];
                }
                $count++;
            }
            $updated_rows = 0;
            if(_count($data_to_update)){
                foreach($data_to_update as $data){
                    if(!empty($data['country'])){
                        if(UserProfile::updateData(['user_id' => $data['id']], ['current_city' => $data['city'], 'country' => $data['country']])){
                            $updated_rows++;
                        }
                    }
                }
            }
            return $updated_rows.' rows updated!';
        } else {
            return 'Error in opening the file';
        }

    }
}
