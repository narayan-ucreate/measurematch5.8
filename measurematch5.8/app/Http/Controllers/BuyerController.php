<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Redirect;
use Session;
use Validator;
use DB;
use Newsletter;
use App\Model\User;
use App\Model\BuyerProfile;
use App\Model\UsersCommunication;
use App\Model\TypeOfOrganization;
use Carbon\Carbon;
use Storage;
use Image;
use App\Model\Contract;
use App\Model\PostJob;
use App\Model\InvalidEmailDomain;

class BuyerController extends Controller {

    public function __construct() {

        $this->middleware('auth',
            ['except' => ['BuyerSignUp', 'buyerTypeOfOrganization', 'buyertypeOrg', 'postProjectBuyerSignUp']]);
    }

    public function buyerProfile() {
        if (!empty(Auth::user() && Auth::user()->id) && Auth::user()->user_type_id == config('constants.BUYER')) {
            if(Auth::user()->admin_approval_status != config('constants.APPROVED')) return Redirect::To('/');
            $user_id = Auth::user()->id;
            $buyer_information = BuyerProfile::findByCondition(['user_id' => $user_id], ['type_of_organisation'], ['id', 'desc']);
            $buyer_data = $buyer_information['0'];
            $posted_projects = PostJob::findByCondition(['user_id' => $user_id, 'publish' => config('constants.PUBLISHED')]);
            $type_of_org_list = TypeOfOrganization::listAll();
            if (!empty($buyer_data)) {
                return view('buyer.profile', compact('buyer_data', 'posted_projects', 'type_of_org_list'));
            } else {
                return redirect('buyer/signup-step1')->with('status', 'We could not find any linked profile. Please signup to create your profile!');
            }
        } else {
            return Redirect::To('/');
        }
    }

//view buyer profile to freelancer
    public function viewBuyerProfile(Request $request)
    {
        $user_id = app('request')->input('id');
        if (!isValidUuid($user_id)) {
            return view('errors.404');
        }
        try {
            $buyer_information = BuyerProfile::findByCondition(['user_id' => $user_id], [], ['id', 'desc']);
            $buyer_data = $buyer_information[0];
        } catch (\Exception $ex) {
            return view('errors.404');
        }
        if (!empty($buyer_data)) {
            return view('buyer.viewbuyerprofile', compact('buyer_data'));
        } else {
            return redirect('buyer/signup-step1')->with('status', 'We could not find any linked profile. Please signup to create your profile!');
        }
    }

    public function getBuyerProfile(Request $request) {
        $user_id = Auth::user()->id;
        $buyer_data = BuyerProfile::findByCondition(['user_id' => $user_id])->toArray();
        return $buyer_data;
    }

    //end of view buyer profile to freelancer
    public function editcompany(Request $request) {
        $id = Auth::user()->id;
        $form_data = $request->all();
       
        if (($form_data['hidden_parent_company_url'] != '') && ($form_data['parent_company_option'] == '')) {
            $form_data['parent_company_option'] = 'Yes';
        }
        $rules = array(
            'company_url' => 'required',
        );
        $validator = Validator::make($form_data, $rules);
        if ($validator->fails()) {
            $error = $validator->errors()->toArray();
        } else {
            $type_of_org = '';
            if (isset($form_data['type_of_organization']) && !empty($form_data['type_of_organization'])) {
                $type_of_organization = TypeOfOrganization::getTypeOfOrganizationByName($form_data['type_of_organization']);
                if (isset($type_of_organization) && !empty($type_of_organization) && isset($type_of_organization[0]->id)) {
                    $type_of_org = $type_of_organization[0]->id;
                }
            }
            if ($form_data['parent_company_option'] == 'Yes') {
                $company_details_parent_company = stripScriptingTagsInline($form_data['parent_company_url']);
            } else if ($form_data['parent_company_option'] == 'No') {
                $company_details_parent_company = '-1';
            } else {
                $company_details_parent_company = '';
            }
            $data_to_update = ['company_url' => strtolower($form_data['company_url']), 'office_location' => $form_data['office_location'], 'parent_company' => $company_details_parent_company];
            if(!empty(trim($type_of_org))){
                $data_to_update['type_of_organization_id'] = $type_of_org;
            }
            $update_query = BuyerProfile::updateBuyerInformation($id, $data_to_update);
            if ($update_query) {
                return ["success"=>1];
            } else {
                return ["success"=>0];
            }
        }
    }

    public function savebio(Request $request) {
        $id = Auth::user()->id;
        $formData = $request->all();
        $rules = array(
            'bio' => 'required',
        );
        $validator = Validator::make($formData, $rules);
        if ($validator->fails()) {
            $error = $validator->errors()->toArray();
            if ($error['bio']['0']) {
                return '3';
            }
        } else {
            $update_query = BuyerProfile::updateBuyerInformation($id, ['bio' => stripScriptingTagsInline($formData['bio'])]);
            if (!$update_query) {
                return '2';
            } else {
                return '1';
            }
        }
    }

    public function addlogo(Request $request) {
        if (Auth::check()) {
            $id = Auth::user()->id;
            $form_data = $request->all();
            if (isset($form_data['base64image']) && !empty($form_data['base64image'])) {
                $file = $form_data['base64image'];
                $full_url = "";
                if ($file != '') {
                    list($type, $data) = explode(';', $file);
                    list(, $data) = explode(',', $file);
                    $explode_data = explode('base64', $file);
                    $exploded_data_image = explode('image/', $explode_data[0]);
                    $data = base64_decode($data);
                    $new_extnsion = str_replace(";", "", $exploded_data_image[1]);
                    $image_file_name = rand() . "." . $new_extnsion;
                    $s3 = Storage::disk('s3');
                    $file_path = '/' . $image_file_name;
                    $s3_bucket = getenv("S3_BUCKET_NAME");
                    $bucket_url = getenv('BUCKET_URL');
                    try {
                        $img = Image::make($data);
                        $img->encode($new_extnsion);
                        $s3->put($file_path, (string) $img, 'public');
                        $full_url = $bucket_url . "/" . $s3_bucket . "" . $file_path;
                    } catch (\Exception $ex) {
                        $full_url = "";
                    }
                }

                if ($full_url != '') {
                    $update_query = BuyerProfile::updateBuyerInformation($id, ['profile_picture' => $full_url]);
                    if ($update_query) {
                        return redirect('buyer/profile-summary');
                    } else {
                        return back()->with('image_error', config('constants.IMAGE_COULD_NOT_SAVE'));
                    }
                } else {
                    return back()->with('image_error', config('constants.IMAGE_COULD_NOT_SAVE'));
                }
            }
            return back()->with('image_error', config('constants.IMAGE_COULD_NOT_SAVE'));
        } else {
            return redirect('/login');
        }
    }

    public function buyerTypeOfOrganization(Request $request) {
        $type_of_org = TypeOfOrganization::listAll()->toArray();
        return $type_of_org;
    }

    public function buyertypeOrg(Request $request) {
        $form_data = $request->all();
        $type_of_org = TypeOfOrganization::getLikeTypeOfOrganization(['name' => $form_data['textType']]);
        if (isset($type_of_org) && !empty($type_of_org)) {
            $response = 1;
        } else {
            $response = 0;
        }
        return $response;
    }

    /* Post project from home buyer signup */

    public function postProjectBuyerSignUp(Request $request) {
        $form_data = $request->all();
       
        $pass_space = preg_match('/\s/', $form_data['password']);
        if ($pass_space == 1) {
            $msg = "Spaces are not allowed";
            return Redirect::back()->with('pass_error', $msg);
        }
        $messages = [
            'company_name.required' => 'Please enter your company name',
            'type_of_organization.required' => 'Please select type of organization',
            'email.required' => 'Please enter your work email.',
            'email.unique' => 'Company email alreay exists. Please choose different one',
            'password.required' => 'Enter your password',
            'password.max' => 'Maximum 50 characters are allowed',
            'password.min' => 'Minimum 6 characters are allowed',
            'first_name.required' => 'Please enter your first name',
            'first_name.max' => 'Maximum 100 characters are allowed',
            'last_name.required' => 'Please enter your last name',
            'last_name.max' => 'Maximum 100 characters are allowed',
        ];

        $validator = Validator::make($form_data, [
                'company_name' => 'required|max:255',
                'first_name' => 'required|max:100',
                'type_of_organization' => 'required',
                'company_url' => 'required',
                'phone_number' => 'required',
                'last_name' => 'required|max:100',
                'email' => 'required|max:255|unique:users',
                'password' => 'required|max:20|min:6',
                ], $messages);

        if ($validator->fails()) {

            $error = $validator->errors()->toArray();
            if (!empty($error['company_name'][0])) {
                $response['company_name'] = $error['company_name'][0];
            }
            if (!empty($error['first_name'][0])) {
                $response['first_name'] = $error['first_name'][0];
            }
            if (!empty($error['last_name'][0])) {
                $response['last_name'] = $error['last_name'][0];
            }
            if (!empty($error['password'][0])) {
                $response['password'] = $error['password'][0];
            }
            if (!empty($error['email'][0])) {
                $response['errors'] = $error['email'][0];
            }

            Session::put('response', 'error in mail');
            Redirect::to('/post-a-project-step1')->withErrors($validator->messages());
        } else {

            try {
                DB::beginTransaction();
                $buyer_unique_number = checkUniqueMME(config('constants.BUYER'));
                $token = bin2hex(openssl_random_pseudo_bytes(16));
                $user = new User;
                $user->name = trimFirstName($form_data['first_name']);
                $user->last_name = $form_data['last_name'];
                $user->email = trim(strtolower($form_data['email']));
                $user->user_type_id = config('constants.BUYER');
                $user->admin_approval_status = config('constants.APPROVED');
                $user->password = bcrypt($form_data['password']);
                $user->mm_unique_num = $buyer_unique_number;
                $user->phone_num = (!empty($form_data['country_code']))? $form_data['country_code'].'-'.$form_data['phone_number'] : $form_data['phone_number'];
                $user->access_token = $token;
                $user->posted_a_project_from_homepage = $form_data['posted_a_project_from_homepage'];
                

                if ($user->save()) {
                    /* post project from Home code starts */
                    $project_from_home = '';

                    if (isset($_COOKIE) && !empty($_COOKIE) && array_key_exists('project_from_home', $_COOKIE)) {
                        $project_from_home = (array) json_decode($_COOKIE['project_from_home']);
                    }

                    $first_name = $form_data['first_name'];
                    $user_email = strtolower($form_data['email']);
                    $company_name = $form_data['company_name'];
                    \App\Components\Email::userVerficationEmail(['first_name'=>$first_name,'user_email'=>$user_email,'access_token'=>$token,'user_type_id'=>config('constants.BUYER')]);
                    $response = Newsletter::subscribe($form_data['email'], ['firstName' => $form_data['first_name'], 'lastName' => $form_data['last_name']]);
                    $buyer_profile = new BuyerProfile;
                    $buyer_profile->company_name = trim($form_data['company_name']);
                    $buyer_profile->first_name = $form_data['first_name'];
                    $buyer_profile->last_name = $form_data['last_name'];
                    $buyer_profile->company_url = $form_data['company_url'];
                    
                    $type_of_org = 108; /* Default organization (Type of organization) */
                    if (isset($form_data['type_of_organization']) && !empty($form_data['type_of_organization'])) {
                        $type_of_organization = TypeOfOrganization::getTypeOfOrganizationByName($form_data['type_of_organization']);
                        if (isset($type_of_organization) && !empty($type_of_organization) && isset($type_of_organization[0]->id)) {
                            $type_of_org = $type_of_organization[0]->id;
                        }
                    }
                    $buyer_profile->type_of_organization_id = $type_of_org;
                    $buyer_profile->office_location = $form_data['office_location'];
                    $buyer_profile->user_id = $user->id;

                    $buyer_profile->save();
                    //-------Code added by rahul to add record in user communication ------
                    $created = Carbon::now();
                    $user_communication = new UsersCommunication();
                    $user_communication->user_id = $user->id;
                    $user_communication->created_at = $created;

                    $user_communication->save();

                    //-------End Code added by rahul to add record in user communication------
                    $params = array(
                        'email' => strtolower($user->email),
                        'password' => $form_data['password']
                    );
                    /* post project from Home code starts */

                    if (($user->user_type_id == config('constants.BUYER')) && $project_from_home != '') {
                        $email_componenet = new Email();
                        $id = $user->id;
                        $buyer_detail = BuyerProfile::getBuyerProfileList($id);
                        $post_job = addPostOffline($project_from_home, '3', $buyer_detail[0], $id);
                        if ($post_job) {
                            $job_id = addSkillOffline($post_job, $project_from_home);
                            $company_name = $buyer_profile->company_name;
                            $email_componenet->projectAdminReview(['buyer_id'=>$id , 'project_id' => $post_job->id ,'manual_skills'=> $project_from_home['manual_skills']]);
                            if(hasSubscribed($user->id)){
                            $email_componenet->waitingProjectApprovalEmail(['buyer_id' =>$user->id , 'project_id' => $post_job->id]);
                            }
                            setcookie("project_from_home", "", time() - 3600, '/');
                        }
                        /* Post a project from home ends */
                        DB::commit();
                        Session::put('signup_success', true);
                        Session::put('signup_email', $user_email);
                        return '1';
                    }
                }
            } catch (\Exception $e) {
                DB::rollback();
                return back()->with('general_error', 'There was an error in creating your account. Please try again later!');
            }
        }
    }
    
    public function insertInvalidEmailsToDatabase(){
        $ssl = getenv('APP_SSL');
        $handle = fopen(url('/invalid_email_domains.csv', [], $ssl), "r");
        if ($handle) {
            $count_all_domains = InvalidEmailDomain::fetchDomains([], 'count');
            $data_to_insert = [];
            while (($email = fgets($handle)) !== false) {
                if($count_all_domains == 0){
                    $data_to_insert[] = ['email_domain' => trim($email),
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now()
                                        ];
                }
            }
            if(_count($data_to_insert)){
                if(InvalidEmailDomain::insert($data_to_insert)){
                    fclose($handle);
                    return 'Data successfully inserted';
                }
            }
            fclose($handle);
            return 'Data could not be inserted';
            
        } else {
            return 'Error in opening the file';
        } 
    }
    
    public function updateTypeOfOrganizationInBuyerProfileTableScript(){
        $buyer_profiles = BuyerProfile::get();
        $count = 0;
        foreach($buyer_profiles as $buyer_profile){
            if(!empty(trim($buyer_profile->type_of_organization))){
                if(BuyerProfile::updateBuyerProfile(['id' => $buyer_profile->id], ['type_of_organization_id' => trim($buyer_profile->type_of_organization)])){
                    $count++;
                }
            }
        }
        return $count.' rows updated';
    }
    public function buyerMobileView()	
    {	
        return view('buyer.mobile_view');	
    }
}
