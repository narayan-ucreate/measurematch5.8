<?php

namespace App\Http\Controllers\admin;

use App\Components\ServiceHubComponent;
use App\Components\WebflowComponent;
use App\Http\Controllers\Controller;
use App\Model\BusinessInformation;
use App\Model\ServiceHubApplicant;
use App\Model\UsersCommunication;
use App\Model\WebflowPage;
use App\Model\ServiceHub;
use Illuminate\Http\Request;
use Auth;
use Redirect;
use Validator;
use DB;
use Excel;
use App\Model\User;
use App\Model\UserProfile;
use App\Model\UsersSkill;
use App\Model\BuyerProfile;
use Carbon\Carbon;
use App\Model\PostJob;
use App\Model\Category;
use App\Model\JobsSkill;
use App\Model\Skill;
use App\Model\ServicePackage;
use App\Model\ServicePackageType;
use App\Model\Deliverable;
use App\Model\TypeOfOrganization;
use App\Components\ServicePackageComponent;
use App\Components\Email;
use App\Components\SegmentComponent;
use App\Components\CommonFunctionsComponent;
use App\Model\Communication;
use App\Model\Contract;
use App\Model\ServiceHubAssociatedExpert;
class AdminController extends Controller {

    public function __construct() {
        $this->middleware('adminAuth', ['except' => ['adminLogin', 'index']]);
    }

    
    public function index(){
        if(adminAuth()) return redirect('admin/buyerListing');        
        if(!Auth::check()) return view('admin.admin.login');
        return redirect('/');
    }

    public function adminLogin(Request $request) {
        if ($request->isMethod('post')) {
            $admin_login_form_data = $request->all();
            $rules = array(
                'mm_password' => 'required',
                'mm_email' => 'required'
            );
            $validator = Validator::make($admin_login_form_data, $rules);
            if ($validator->fails()) {
                return redirect('paneladmin1')
                                ->withErrors($validator)
                                ->withInput();
            } else {
                if (Auth::attempt(['email' => $admin_login_form_data['mm_email'], 'password' => $admin_login_form_data['mm_password'], 'user_type_id' => config('constants.ADMIN')])) {
                    $status = Auth::user()->status;
                    if ($status == 1) {
                        return redirect()->intended('admin/buyerListing');
                    }
                    Auth::logout();
                    return redirect('paneladmin1')->withErrors([
                                'error' => config('constants.VERIFY_ACTIVATION_LINK')
                    ]);
                }
                return redirect('paneladmin1')->withErrors([
                            'error' => config('constants.CREDENTIAL_DONOT_MATCH'),
                ]);
            }
        }
        return view('errors.404');
    }

    public function buyerListing(Request $request) {
        return $this->commonListing($request, User::getBuyer(), 'buyers_common_listing');
    }

    public function unverifiedBuyers(Request $request) {
        return $this->commonListing($request, User::getUnverifiedBuyers(), 'buyers_common_listing');
    }

    public function unverifiedVendors(Request $request) {
        return $this->commonListing($request, (new User)->getUnverifiedVendors(), config('constants.ADMIN_VENDORS_VIEWS.VENDORS_COMMON_LISTING'));
    }

    public function expertListing(Request $request) {
        return $this->commonListing($request, User::getUserProfileWithSkills(), config('constants.ADMIN_EXPERT_VIEWS.EXPERT_COMMON_LISTING'));
    }

    public function expertWithIncompleteProfile(Request $request) {
        return $this->commonListing($request, User::getUserProfileWithIncompleteData(), config('constants.ADMIN_EXPERT_VIEWS.EXPERT_COMMON_LISTING'));
    }

    public function sideHustlersExperts(Request $request) {
        return $this->commonListing($request, User::getSideHustlersExperts(), config('constants.ADMIN_EXPERT_VIEWS.EXPERT_COMMON_LISTING'));
    }
    
    public function pendingBuyers(Request $request) {
        return $this->commonListing($request, User::getPendingBuyers(), 'buyers_common_listing');
    }
    
    public function notverifiedexperts(Request $request) {
        return $this->commonListing($request, User::getNonVerifiedExperts(), config('constants.ADMIN_EXPERT_VIEWS.EXPERT_COMMON_LISTING'));
    }
        
    public function archivedExpertsListing(Request $request) {
        return $this->commonListing($request, User::getBlockedExperts(), config('constants.ADMIN_EXPERT_VIEWS.EXPERT_COMMON_LISTING'));
    }
    public function archivedBuyersListing(Request $request) {
        return $this->commonListing($request, User::getBlockedBuyers(), 'buyers_common_listing');
    }

    public function archivedVendorsListing(Request $request) {
        return $this->commonListing($request, (new User)->getArchivedVendors(), config('constants.ADMIN_VENDORS_VIEWS.VENDORS_COMMON_LISTING'));
    }
    
    public function liveProjects(Request $request) {
        return $this->commonListing($request, PostJob::getLiveProjects(date('Y-m-d H:i:s')), 'projects_common_listing', 'post_jobs');
    }

    public function completedProjects(Request $request) {
        return $this->commonListing($request, (new PostJob)->getCompletedProjects(), 'projects_common_listing', 'contracts');
    }
    
    public function archivedProjectsListing(Request $request) {
        return $this->commonListing($request, PostJob::getArchivedProjects(), 'projects_common_listing', 'post_jobs');
    }
    
    public function expiredProjectsListing(Request $request) {
        return $this->commonListing($request, PostJob::getExpiredProjects(date('Y-m-d H:i:s')), 'projects_common_listing', 'post_jobs');
    }

    public function inContractProjectsListing(Request $request) {
        return $this->commonListing($request, (new PostJob)->getInContractProjects(), 'projects_common_listing', 'contracts');
    }

    public function rebookingProjectsListing(Request $request) {
        return $this->commonListing($request, (new PostJob)->getRebookingProjects(), 'projects_common_listing', 'post_jobs');
    }

    public function vendorListing(Request $request) {
        return $this->commonListing($request, (new User)->getVendors(), config('constants.ADMIN_VENDORS_VIEWS.VENDORS_COMMON_LISTING'));
    }

    public function pendingHubs(Request $request) {
        return $this->commonListing($request, (new ServiceHub)->getPendingHubs(), 'hubs_common_listing', 'service_hubs');
    }
    
    public function archivedHubs(Request $request) {
        return $this->commonListing($request, (new ServiceHub)->getArchivedHubs(), 'hubs_common_listing', 'service_hubs');
    }

    public function liveHubs(Request $request) {
        return $this->commonListing($request, (new ServiceHub)->getLiveHubs(), 'hubs_common_listing', 'service_hubs');
    }

    private function commonListing($request, $query, $view, $table_name = null)
    {
        $common_functions_component = new CommonFunctionsComponent;
        $projects_count = $common_functions_component->projectsCount();
        $table = $table_name ?? 'users';
        $input_data = $request->all();
        $order_by = ($input_data['orderBy']) ?? 'desc';
        $data_sort = ($input_data['data-sort']) ?? '';
        $sorted_query = $this->sortedQuery($data_sort, $order_by, $query, $table);
        $result = $sorted_query->paginate(config('constants.ADMIN_PER_PAGE_LISTING_COUNT'));
        if(_count($input_data))
        {
            $current_url = $this->currentUrlWithParameters($input_data);
            $result->withPath($current_url);
        }
        $view_data = ['result' => $result, 'projects_count' => $projects_count];
        if($view == 'hubs_common_listing')
            $view_data['hubs_count'] = $common_functions_component->hubsCount();
        return view("admin.admin.$view", $view_data);
    }
    
    private function sortedQuery($data_sort, $order_by, $query, $table)
    {
        $default_sort_field = "$table.created_at";
        if($table == 'contracts')
            $default_sort_field = "$table.job_start_date";
        if(!empty($data_sort))
        {
            return $sorted_query = $query->orderBy($data_sort, $order_by);
        }
        return $query->orderBy($default_sort_field, $order_by);
    }
    
    private function currentUrlWithParameters($input_data)
    {
        $current_url = url()->current();
        if(_count($input_data))
        {
            $count = 0;
            foreach($input_data as $input_key => $input_value)
            {
                if($input_key == 'page')
                    continue;
                if($count == 0)
                {
                    $current_url.="?$input_key=$input_value";
                }
                else
                {
                    $current_url.="&$input_key=$input_value";
                }
                $count++;
            }
        }
        return $current_url;
    }

    public function exportExpertWithIncompleteProfile() {
        $query = User::getExpertWithIncompleteData();
        $result = $query->orderBy('users.created_at', 'desc')->get()->toArray();
        $user_information = getUserInformation($result);
        return $this->makeCsv($user_information);
    }

    public function exportSideHustlersExperts() {
        $query = User::getSideHustlersExperts();
        $result = $query->orderBy('users.created_at', 'desc')->get()->toArray();
        $user_information = getSideHustlerInformation($result);
        return $this->makeCsv($user_information);
    }
    public function exportNotVerifiedExperts(Request $request) {
        $has_array = ['user_profile'];
        $result = User::findByCondition([
                'user_type_id' => config('constants.EXPERT'),
                'users.status' => config('constants.PENDING'),
                'users.admin_approval_status' => config('constants.PENDING'),
                'users.is_deleted' => config('constants.PENDING'),
                ], [
                'user_profile',
                'user_skills'], ['order_by' =>
                ['name', 'asc']
                ], $has_array);

        $user_information = getUserInformation($result);
        return $this->makeCsv($user_information);
    }

    public function expertExportListing(Request $request) {
        $admin_approval_status = $request->admin_approved_status;
        if($admin_approval_status == config('constants.APPROVED')){
            return $this->makeCsv(getUserInformation(User::getUserProfileWithSkills()->get()));
        }else{
            return $this->makeCsv(getUserInformation(User::getExpertProfileInformation()));
        }
    }

    public function buyerArchievedView($id) {
        if (isValidUuid($id)) {
            $result = User::findByCondition([
                    'users.user_type_id' => config('constants.BUYER'),
                    'users.status' => '2',
                    'users.id' => $id
                    ], ['buyer_profile']);
            if (_count($result) == 0) {
                return view('errors.404');
            }
            $back_url = 'admin/archivedBuyersListing';
            $page_label = config('constants.ARCHIVED_LABEL');
            return view('admin.admin.buyerView', compact('result', 'back_url', 'page_label'));
        }
        return view('errors.404');
    }

    public function vendorArchivedView($id) {
        if (isValidUuid($id)) {
            $result = User::findByCondition([
                'users.user_type_id' => config('constants.VENDOR'),
                'users.status' => '2',
                'users.id' => $id
            ], ['buyer_profile']);
            if (_count($result) == 0) {
                return view('errors.404');
            }
            $back_url = 'admin/archivedVendorsListing';
            $page_label = config('constants.ARCHIVED_LABEL');
            return view('admin.admin.vendorView', compact('result', 'back_url', 'page_label'));
        }
        return view('errors.404');
    }

    public function expertArchievedView($id) {
        if (isValidUuid($id)) {
            $result = User::findByCondition([
                    'users.user_type_id' => config('constants.EXPERT'),
                    'users.status' => '2',
                    'users.id' => $id
                    ], ['user_profile', 'user_profile.remote_work', 'user_skills']);
            if (_count($result) == 0) {
                return view('errors.404');
            }
            $back_url = 'admin/archivedExpertsListing';
            $page_label = 'Archived';
            return view('admin.admin.expertView', compact('result', 'back_url', 'page_label'));
        }
        return view('errors.404');
    }

    public function logout(Request $request) {
        try {
            Auth::logout();
            return redirect('paneladmin1');
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function buyerView($id) {
        if (isValidUuid($id)) {
            $user = User::findByCondition([
                    'users.id' => $id
            ]);
            if (_count($user) == 0) {
                return view('errors.404');
            }
            $user_status = $user[0]->status;
            $result = User::findByCondition(['users.user_type_id' => config('constants.BUYER'),
                    'users.admin_approval_status' => '1',
                    'users.status' => $user_status,
                    'users.id' => $id
                    ], ['buyer_profile'], []);
            $back_url = 'admin/buyerListing';
            $page_label = config('constants.APPROVED_LABEL');
            return view('admin.admin.buyerView', compact('result', 'back_url', 'page_label'));
        }
        return view('errors.404');
    }

    public function vendorView($id) {
        if (isValidUuid($id)) {
            $user = User::findByCondition([
                'users.id' => $id
            ]);
            if (_count($user) == 0) {
                return view('errors.404');
            }
            $user_status = $user[0]->status;
            $result = User::findByCondition(['users.user_type_id' => config('constants.VENDOR'),
                'users.admin_approval_status' => '1',
                'users.status' => $user_status,
                'users.id' => $id
            ], ['buyer_profile'], []);
            $back_url = 'admin/vendorListing';
            $page_label = config('constants.APPROVED_LABEL');
            return view('admin.admin.vendorView', compact('result', 'back_url', 'page_label'));
        }
        return view('errors.404');
    }

    public function viewUnverifiedBuyer($id) {
        if (isValidUuid($id)) {
            $user = User::findByCondition(['users.id' => $id ]);
            if (_count($user) == 0) {
                return view('errors.404');
            }
            $user_status = $user[0]->status;
            $result = User::findByCondition(['users.user_type_id' => config('constants.BUYER'),'users.status' => $user_status,'users.id' => $id], ['buyer_profile'], []);
            $back_url = 'admin/unverifiedBuyers';
            $page_label = config('constants.UNVERIFIED_LABEL');
            return view('admin.admin.buyerView', compact('result', 'back_url', 'page_label'));
        }
        return view('errors.404');
    }

    public function viewUnverifiedVendor($id) {
        if (isValidUuid($id)) {
            $user = User::findByCondition(['users.id' => $id ]);
            if (_count($user) == 0) {
                return view('errors.404');
            }
            $user_status = $user[0]->status;
            $result = User::findByCondition(['users.user_type_id' => config('constants.VENDOR'),'users.status' => $user_status,'users.id' => $id], ['buyer_profile'], []);
            $back_url = 'admin/unverifiedVendors';
            $page_label = config('constants.UNVERIFIED_LABEL');
            return view('admin.admin.vendorView', compact('result', 'back_url', 'page_label'));
        }
        return view('errors.404');
    }

    public function buyerExportListing(Request $request) {
        $admin_approval_status = $request->admin_approved_status;
        $result = User::findByCondition(['users.user_type_id' => config('constants.BUYER'),
            'users.admin_approval_status' => $admin_approval_status,
            'users.status' => '1',
        ], ['buyer_profile'], ['order_by' => ['name', 'asc']]);
        $user_information = $this->buyersDetailListing($result);
        return $this->makeCsv($user_information);
    }

    public function vendorExportListing(Request $request) {
        $admin_approval_status = $request->admin_approved_status;
        $result = User::findByCondition(['users.user_type_id' => config('constants.VENDOR'),
            'users.admin_approval_status' => $admin_approval_status,
            'users.status' => '1',
        ], ['buyer_profile'], ['order_by' => ['name', 'asc']]);
        $user_information = $this->buyersDetailListing($result, true);
        return $this->makeCsv($user_information);
    }
    
    private function buyersDetailListing($result, $vendor = false){
        $user_information = [];
        foreach ($result as $keys => $val) {
            $parent_comapny = '';
            if (isset($val['buyer_profile']['parent_company']) && ($val['buyer_profile']['parent_company'] != '-1')) {
                $parent_comapny = $val['buyer_profile']['parent_company'];
            }
            $type_of_organization = '';
            $type_of_org = getTypeOfOrganization();
            foreach ($type_of_org as $type) {
                if ($val['buyer_profile']['type_of_organization_id'] == $type->id) {
                    $type_of_organization = $type->name;
                }
            }
            $user_information[$keys]['Name'] = $val['name'] . ' ' . $val['last_name'];
            $user_information[$keys]['Email'] = $val['email'];
            $user_information[$keys]['MM Number'] = $val['mm_unique_num'];
            $user_information[$keys]['Registration Date'] = date('d-m-Y', strtotime($val['created_at']));
            $user_information[$keys]['Company Name'] = $val['buyer_profile']['company_name'];
            $user_information[$keys]['Company Url'] = $val['buyer_profile']['company_url'];
            $user_information[$keys]['phone'] = $val['phone_num'];
            $user_information[$keys]['Vat Number'] = $val['vat_country_code'].$val['vat_number'];
            $user_information[$keys]['Parent Company'] = $parent_comapny;
            $user_information[$keys]['Type of Organization'] = $type_of_organization;
            if (!$vendor){
                $user_information[$keys]['How soon do you need to get a project done?'] = $val['buyer_profile']['expected_project_post_time'] ? config('constants.EXPECTED_PROJECT_POST_TIME.'.$val['buyer_profile']['expected_project_post_time']) : '';
                $user_information[$keys]['Office Location'] = ($val['buyer_profile']['office_location']) ? str_replace('<br/>', ', ', $val['buyer_profile']['office_location']) : '';
                $user_information[$keys]['Bio'] = ($val['buyer_profile']['bio']) ? strip_tags($val['buyer_profile']['bio']) : '-';
            }
            $posts = [];
            $all_post = getPostJobs($val['id']);
            if(_count($all_post)){
                $posts = $all_post;
            }
            $post_information = [];
            if (_count($posts)) {
                foreach ($posts as $post) {
                    $post_information[] = trim($post->job_title);
                }
            }
            $final_post = implode(',', $post_information);
            $user_information[$keys]['Projects (titles)'] = ($final_post) ? $final_post : '';
        }
        return $user_information;
    }

    public function expertView($id) {
        if (isValidUuid($id)) {
            $result = User::findByCondition(['user_type_id' => config('constants.EXPERT'),
                    'users.admin_approval_status' => '1',
                    'users.id' => $id
                    ], ['user_profile', 'user_profile.remote_work', 'user_skills']);
            if (_count($result) == 0) {
                return view('errors.404');
            }
            $back_url = 'admin/expertListing';
            $page_label = 'Approved';
            $webflow_page = WebflowPage::getPageByUserId($id);
            $webflow_url = '-';
            if ($webflow_page){
                $webflow_url = $webflow_page->webflow_url;
            }
            return view('admin.admin.expertView', compact('result', 'back_url', 'page_label', 'webflow_url'));
        }
        return view('errors.404');
    }

    public function expertWithIncompleteProfileView($id) {
        if (isValidUuid($id)) {
            $result = User::getExpertWithIncompleteProfileView($id);
            if (_count($result) == 0) {
                return view('errors.404');
            }
            $back_url = 'admin/incompleteProfileExperts';
            $page_label = 'Profile Incomplete';
            return view('admin.admin.expertView', compact('result', 'back_url', 'page_label'));
        }
        return view('errors.404');
    }

    public function notverifiedexpertView($id) {
        if (isValidUuid($id)) {
            $result = User::getNotVerifiedExpertView($id);
            if (_count($result) == 0) {
                return view('errors.404');
            }
            $back_url = 'admin/notverifiedexperts';
            $page_label = 'Unverified';
            return view('admin.admin.expertView', compact('result', 'back_url', 'page_label'));
        }
        return view('errors.404');
    }
    public function sideHustlerView($id) {
        if (isValidUuid($id)) {
            $result = User::getSideHustler($id)->get();
            if (_count($result) == 0) {
                return view('errors.404');
            }
            $back_url = 'admin/sideHustlersExperts';
            $page_label = 'Side Hustlers';
            return view('admin.admin.expertView', compact('result', 'back_url', 'page_label'));
        }
        return view('errors.404');
    }

    public function blockUser(Request $request) {
        $response = 0;
        $block_user_form_data = $request->all();
        $id = $block_user_form_data['id'];
        if (isValidUuid($id)) {
            $response = User::updateUserStatusToBlocked($id);
        }
        return $response;
    }

    public function resendemail(Request $request) {
        $resend_mail_form_data = $request->all();
        $id = $resend_mail_form_data['id'];
        $response = 0;
        if (isValidUuid($id)) {
            $user_information = User::find($id)->toArray();
            if (_count($user_information) == 0) {
                return $response;
            }
            $response = $this->userVerficationEmail($user_information['user_type_id'], $user_information['id']);
        }
        return $response;
    }

    public function unblockUser(Request $request) {
        $user_id = $request->id;
        $result = ['result' => 'failure'];
        if (isValidUuid($user_id)) {
            $user_information = User::find($user_id)->toArray();
            if (_count($user_information) == 0) {
                return $result;
            }
            $verified_status = $user_information['verified_status'];
            $status = ($user_information['admin_approval_status'] == config('constants.REJECTED'))?config('constants.PENDING'):$user_information['admin_approval_status'];
            $response = User::updateUserApprovalStatus($user_id, $verified_status, $status);
            if ($response == 1) {
                $success = ($verified_status == 1) ? 'success' : 'failure';
                $result = ['result' => $success];
            }
        }
        return $result;
    }

    public function reinstateProject(Request $request) {
        $result = 'failure';
        $reinstate_project_form_data = $request->all();
        $project_id = $reinstate_project_form_data['project_id'];
        if (ctype_digit($project_id)) {
            $update = PostJob::updatePostData(['publish' => config('constants.PROJECT_PENDING'),'publish_date' => Carbon::now()], $project_id);
            if ($update) {
                $result = 'success';
            }
        }
        return ['result' => $result];
    }

    public function buyerEdit($id) {
        return $this->accountEdit($id, config('constants.BUYER'));
    }

    public function vendorEdit($id) {
        return $this->accountEdit($id, config('constants.VENDOR'));
    }

    public function accountEdit($id, $user_type){
        if (isValidUuid($id)) {
            $result = User::findByCondition([
                'users.user_type_id' => $user_type,
                'users.status' => '1',
                'users.id' => $id
            ], ['buyer_profile']);
            if (_count($result) == 0) {
                return view('errors.404');
            }
            switch (true){
                case ($user_type == config('constants.BUYER')):
                    return view('admin.admin.buyerEdit', compact('result'));
                case ($user_type == config('constants.VENDOR')):
                    return view('admin.admin.vendorEdit', compact('result'));
            }
        }
        return view('errors.404');
    }

    public function buyerUpdate(Request $request) {
        $form_data = $request->all();
        $id = $form_data['user_id'];
        if (isValidUuid($id)) {
                $update_user = User::updateUser($id, ['name' => $form_data['first_name'], 
                                                    'last_name' => $form_data['last_name'],
                                                    'phone_num' => $form_data['phone_number']]);
            if ($update_user) {
                $update = [
                    'first_name' => $form_data['first_name'],
                    'last_name' => $form_data['last_name'],
                    'company_name' => $form_data['company_name'],
                    'company_url' => $form_data['company_url'],
                ];
                if(!empty(trim($form_data['type_of_organization']))){
                    $update['type_of_organization_id'] = trim($form_data['type_of_organization']);
                }
                if ($form_data['user_type'] == config('constants.BUYER')){
                    $update['expected_project_post_time'] = $form_data['expected_project_post_time'];
                    $update['bio'] = $form_data['bio'];
                    $update['office_location'] = $form_data['office_location'];
                }
                $response = BuyerProfile::updateBuyerInformation($id, $update);
                if ($response) {
                    $redirect_route = (($form_data['user_type'] == config('constants.BUYER')) ? 'buyerListing' : 'vendorListing');
                    return Redirect::To('admin/'.$redirect_route)->with('success', __('global.account_updated'));
                }
                ($form_data['user_type'] == config('constants.BUYER')) ? $redirect_route = 'buyerEdit' : $redirect_route = 'vendorEdit';
                return Redirect::To('admin/'.$redirect_route.'/'. $id)->with('success', __('global.unable_to_update'));
            }
        }
        return back();
    }

    public function expertEdit($id) {
        if (isValidUuid($id)) {
            $result = User::findByCondition([
                    'users.user_type_id' => config('constants.EXPERT'),
                    'users.status' => '1',
                    'users.id' => $id
                    ], ['user_profile', 'user_profile.remote_work', 'user_skills']);
            if (_count($result) == 0) {
                return view('errors.404');
            }
            return view('admin.admin.expertEdit', compact('result'));
        }
        return view('errors.404');
    }

    public function expertUpdate(Request $request) {
        $form_data = $request->all();
        $id = $form_data['user_id'];
        if (isValidUuid($id)) {
            $users = new User;
            $users->name = $form_data['first_name'];
            $users->last_name = $form_data['last_name'];
            $update_user = User::updateUser($id, ['name' => $form_data['first_name'], 
                                        'last_name' => $form_data['last_name'],
                                        'phone_num' => $form_data['phone_number']]);

            if ($update_user) {
                $summary = nl2br(htmlentities($form_data['summary'], ENT_QUOTES, 'UTF-8'));
                $response = UserProfile::where('user_id', $id)->update(
                    [
                        'describe' => $form_data['describe'],
                        'currency' => '$',
                        'daily_rate' => (int) str_replace(['.00', ','], '', $form_data['daily_rate']),
                        'current_city' => $form_data['current_city'],
                        'remote_id' => $form_data['remote_work'],
                        'expert_type' => $form_data['expert_type'],
                        'experts_count_lower_range' => $form_data['experts_count_lower_range'],
                        'summary' => $summary
                ]);
                if ($response) {
                    $user_id = $id;
                    $skills = $form_data['addskill'];
                    if (!empty($skills)) {
                        $this->addExpertSkills($skills, $user_id);
                    }
                    return Redirect::To('admin/expertListing')->with('success', 'Account has been updated.');
                }
                return Redirect::To('admin/expertEdit/' . $id)->with('success', 'An error occured. Please try again!');
            }
        }
        return back();
    }
    
    private function addExpertSkills($skills, $user_id){
        $exploded_skills = explode(',', $skills);
        UsersSkill::deleteRecord(['user_id' => $user_id]);
        foreach($exploded_skills as $i => $skill){
            $checkskill = Skill::getSimilarSkills($skill);
            $created = Carbon::now();
            if (!empty($checkskill)) {
                $skill_id = $checkskill[0]['id'];
                if (UsersSkill::getUserSkills($skill_id, $user_id)->exists()) {
                    $skills_id = "";
                } else {
                    $add_skills = array(
                        'skill_id' => $skill_id,
                        'user_id' => $user_id,
                        'created_at' => $created
                    );
                    $skill_inserted = UsersSkill::create($add_skills);

                    $skills_id[$i] = $skill_id;
                }
            } else {
                if (trim($skill) == '') {
                    unset($exploded_skills[$i]);
                } else {
                    $skill_id = Skill::insertSkillId($skill,$created,'user');
                    $add_skills = array(
                        'skill_id' => $skill_id,
                        'user_id' => $user_id,
                        'created_at' => $created
                    );
                    $skill_inserted = UsersSkill::create($add_skills);
                    $skills_id[$i] = $skill_id;
                }
            }
        }
    }
    
    private function updateJobSkill($skills, $job_id){
        $exploded_skills = explode(',', $skills);
        JobsSkill::deleteSkillJob($job_id);
        foreach($exploded_skills as $i => $skill){
            $checkskill = Skill::getSimilarSkills($skill);
            $created = Carbon::now();
            if (!empty($checkskill)) {
                $skill_id = $checkskill[0]['id'];
                if (!JobsSkill::skillsExist($skill_id, $job_id)) {
                    $add_skills = array(
                        'skill_id' => $skill_id,
                        'job_post_id' => $job_id,
                        'created_at' => $created
                    );
                    $skill_inserted = JobsSkill::create($add_skills);
                }
            } else {
                if (trim($exploded_skills[$i]) == '') {
                    unset($exploded_skills[$i]);
                } else {
                    $skill_id = Skill::insertSkillId(trim($skill), $created, 'job');
                    $add_skills = array(
                        'skill_id' => $skill_id,
                        'job_post_id' => $job_id,
                        'created_at' => $created
                    );
                    $skill_inserted = JobsSkill::create($add_skills);
                }
            }
        }
    }

    public function pendingVendors(Request $request) {
        return $this->commonListing($request, (new User)->getPendingVendors(), config('constants.ADMIN_VENDORS_VIEWS.VENDORS_COMMON_LISTING'));
    }

    public function pendingExperts(Request $request) {
        $input_data = $request->all();
        $order_by = 'desc';
        if (isset($input_data['orderBy']) && !empty($input_data['orderBy'])) {
            $order_by = $input_data['orderBy'];
        }
        $result = User::getExpertProfileInformation($input_data, $order_by, ['paginate' => 'paginate']);
        if(_count($input_data))
        {
            $current_url = $this->currentUrlWithParameters($input_data);
            $result->withPath($current_url);
        }
        return view('admin.admin.'.config('constants.ADMIN_EXPERT_VIEWS.EXPERT_COMMON_LISTING'), compact('result'));
    }

    public function pendingProjects(Request $request) {
        $input_data = $request->all();
        $query = PostJob::getPostListing(config('constants.PROJECT_PENDING'), null, $input_data);
        $result = $query->paginate(config('constants.ADMIN_PER_PAGE_LISTING_COUNT'));
        $projects_count = (new CommonFunctionsComponent)->projectsCount();
        if(_count($input_data))
        {
            $current_url = $this->currentUrlWithParameters($input_data);
            $result->withPath($current_url);
        }
        return view('admin.admin.projects_common_listing', compact('result', 'projects_count'));
    }
    
    public function pendingServicePackages(Request $request) {
        $service_package_data = $request->all();
        $pending_service_package_count = ServicePackage::fetchServicePackages(['publish' => 'TRUE', 'is_approved' => 'FALSE', 'is_rejected' => 'FALSE'], 'count');
        $condition = ['publish' => 'TRUE', 'is_approved' => 'FALSE', 'is_rejected' => 'FALSE'];
        $result = ServicePackage::getPackageListingWithUserSorting($condition, config('constants.ADMIN_PER_PAGE_LISTING_COUNT'), $service_package_data);
        if(_count($service_package_data))
        {
            $current_url = $this->currentUrlWithParameters($service_package_data);
            $result->withPath($current_url);
        }
        return view('admin.admin.service_packages_common_listing', compact('result', 'pending_service_package_count'));
    }
    
    public function allDraftedServicePackages(Request $request) {
        $service_package_data = $request->all();
        $drafted_service_package_count = ServicePackage::fetchServicePackages(['publish' => 'FALSE', 'is_approved' => 'FALSE', 'is_rejected' => 'FALSE'], 'count');
        $condition = ['publish' => 'FALSE', 'is_approved' => 'FALSE', 'is_rejected' => 'FALSE'];
        $result = ServicePackage::getPackageListingWithUserSorting($condition, config('constants.ADMIN_PER_PAGE_LISTING_COUNT'), $service_package_data);
        if(_count($service_package_data))
        {
            $current_url = $this->currentUrlWithParameters($service_package_data);
            $result->withPath($current_url);
        }
        return view('admin.admin.service_packages_common_listing', compact('result', 'drafted_service_package_count'));
    }

    public function buyerPendingView($id) {
        if (isValidUuid($id)) {
            $result = User::findByCondition([
                'users.user_type_id' => config('constants.BUYER'),
                'users.admin_approval_status' => '0',
                'users.id' => $id
            ], ['buyer_profile']);

            if (_count($result) == 0) {
                return view('errors.404');
            }
            $back_url = 'admin/pendingBuyers';
            $page_label = config('constants.PENDING_LABEL');
            return view('admin.admin.buyerView', compact('result', 'back_url', 'page_label'));
        }
        return view('errors.404');
    }

    public function vendorPendingView($id) {
        if (isValidUuid($id)) {
            $result = User::findByCondition([
                'users.user_type_id' => config('constants.VENDOR'),
                'users.admin_approval_status' => '0',
                'users.id' => $id
            ], ['buyer_profile']);

            if (_count($result) == 0) {
                return view('errors.404');
            }
            $back_url = 'admin/pendingVendors';
            $page_label = config('constants.PENDING_LABEL');
            return view('admin.admin.vendorView', compact('result', 'back_url', 'page_label'));
        }
        return view('errors.404');
    }

    public function expertPendingView($id) {
        if (isValidUuid($id)) {
            $result = User::findByCondition([
                    'users.user_type_id' => config('constants.EXPERT'),
                    'users.admin_approval_status' => '0',
                    'users.id' => $id
                    ], ['user_profile', 'user_profile.remote_work', 'user_skills'], [], ['completeMandatoryFields']);
            if (_count($result) == 0) {
                return view('errors.404');
            }
            $back_url = 'admin/pendingExperts';
            $page_label = 'To Interview';
            return view('admin.admin.expertView', compact('result', 'back_url', 'page_label'));
        }
        return view('errors.404');
    }

    public function approveUser(Request $request) {
        $form_data = $request->all();
        $id = $form_data['id'];
        $success = 0;
        $webflow_component = new WebflowComponent;
        if (isValidUuid($id)) {
            if (User::find($id)->user_type_id == config('constants.EXPERT')){
                $expertDetails = User::getSellerDetails($id, 0)[0];
                $profile = $webflow_component->createProfileObject($expertDetails);
                $new_item = $webflow_component->createSingleItem(getenv('WEBFLOW_PROFILE_COLLECTION_ID'), $profile);
                if (isset($new_item['_id'])){
                    $webflow_component->saveProfile($new_item, $id);
                }
            }
            if (User::find($id)->user_type_id == config('constants.VENDOR')){
                $invite_mandatory = $form_data['invite_mandatory'];
                updateUserSettingsById(['invite_mandatory' => $invite_mandatory], $id);
            }
            $admin_approval_time = Carbon::now();
            $success = User::updateUser($id,['admin_approval_status' => config('constants.ACCEPTED'), 'admin_approval_time' => $admin_approval_time]);
            (new ServiceHubAssociatedExpert)->updateData(['user_id' => $id], ['status' => config('constants.ACCEPTED')]);
            $user_data = User::find($id)->toArray();
            $user_type_id = $user_data['user_type_id'];
            (new SegmentComponent)->accountTracking($id, $user_type_id, "Account Approved", $user_data['created_at']);
            $this->approveUserEmail($user_type_id, $id);
        }
        return $success;
    }

    public function expertApproveWebflow(Request $request) {
        $form_data = $request->all();
        $id = $form_data['id'];
        $webflow_component = new WebflowComponent;
        if (isValidUuid($id) && User::find($id)->user_type_id == config('constants.EXPERT')){
            $expertDetails = User::getSellerDetails($id)[0];
            $profile = $webflow_component->createProfileObject($expertDetails);
            $new_item = $webflow_component->createSingleItem(getenv('WEBFLOW_PROFILE_COLLECTION_ID'), $profile);
            if (isset($new_item['_id'])){
                $webflow_component->saveProfile($new_item, $id);
                return['success'=>true];
            }
        }
        return['success'=>false];
    }
    
    private function approveUserEmail($user_type_id, $user_id){
        $email_componenet = new Email();
        if($user_type_id == config('constants.BUYER')){
            return $email_componenet->buyerApprovalEmail($user_id);
        }
        if($user_type_id == config('constants.VENDOR')){
            return $email_componenet->vendorApprovalEmail($user_id);
        }
        return $email_componenet->expertApprovalEmail($user_id);
    }

    public function declineUser(Request $request) {
        $form_data = $request->all();
        $id = $form_data['id'];
        $success = 0;
        if (isValidUuid($id)) {
            $user_data = User::find($id)->toArray();
            $success = User::updateUser($id,['admin_approval_status' => config('constants.REJECTED'), 'status' =>  config('constants.REJECTED')]);
            $user_type_id = $user_data['user_type_id'];
                if($user_type_id == config('constants.BUYER')){
                    Email::buyerAccountRejectionEmail($user_data['id']);
                }
        }
        return ['success' => $success, 'user_type_id' => $user_type_id];
    }

    function userVerficationEmail($user_type_id, $id)
    {
        try
        {
            if ($user_type_id == config('constants.EXPERT'))
            {
                $response = \App\Components\Email::verificationToExpert(['id' => $id]);
            }
            if (isBuyerAndVendor($user_type_id))
            {
                $response = \App\Components\Email::buyerVerificationEmail(['id' => $id]);
            }
            return 1;
        } catch (Exception $ex)
        {
            return 0;
        }
    }

    function getProjects(Request $request, $buyer_id)
    {
        if (isValidUuid($buyer_id))
        {
            $input_data = $request->all();
            $order_by = $input_data['orderBy'] ?? 'ASC';
            $bread_crumb = 'My Projects';
            $query = PostJob::getPostJobWithBuyerId($buyer_id, 1);
            $result = (isset($input_data['data-sort']) && !empty($input_data['data-sort'])) ? $query->orderBy($input_data['data-sort'], $input_data['orderBy']) : $query->orderBy('post_jobs.created_at', $order_by);
            $total = 0;
            $published_projects = [];
            if (PostJob::getPostJobWithBuyerId($buyer_id, 1)->count())
            {
                $bread_crumb = 'Published Projects';
                $total = $result->count();
                $published_projects = $result->paginate(config('constants.ADMIN_PER_PAGE_LISTING_COUNT'));
                if(_count($input_data))
                {
                    $current_url = $this->currentUrlWithParameters($input_data);
                    $published_projects->withPath($current_url);
                }
                return view('admin.admin.buyers_common_listing', compact('bread_crumb', 'published_projects', 'total'));
            }
            return view('admin.admin.buyers_common_listing', compact('bread_crumb', 'published_projects', 'total'));
        }
        return view('errors.404');
    }

    function expertServicePackages(Request $request, $expert_id) {
        if (isValidUuid($expert_id)) {
            $service_package_data = $request->all();
            $order_by = ($service_package_data['orderBy']) ?? 'ASC';
            $published_service_package_count = ServicePackage::fetchServicePackages(['user_id' => $expert_id, 'publish' => 'TRUE', 'is_approved' => 'TRUE'], 'count');
            $bread_crumb = 'Published Service Packages';
            $service_packages = ServicePackage::getServicePackages(['user_id' => $expert_id, 'publish' => 'TRUE', 'is_approved' => 'TRUE'], [], ['order_by' => ['name', $order_by], 'paginate' => config('constants.ADMIN_PER_PAGE_LISTING_COUNT')]);
            if(_count($service_package_data))
            {
                $current_url = $this->currentUrlWithParameters($service_package_data);
                $service_packages->withPath($current_url);
            }
            return view('admin.admin.expertServicePackageListing', compact('bread_crumb', 'service_packages', 'published_service_package_count'));
        }
        return view('errors.404');
    }
    
    function exportExpertServicePackages($expert_id){
        if (isValidUuid($expert_id)) {
            $service_packages = ServicePackage::getServicePackages(['user_id' => $expert_id, 'publish' => 'TRUE', 'is_approved' => 'TRUE'], ['deliverables', 'servicePackageTags.Tags', 'servicePackageCategory', 'servicePackageType'], ['order_by' => ['created_at', 'ASC']]);
            $this->getServicePackageCsv($service_packages, 'Published');
        }
        return back();
    }
    
    function exportDraftedServicePackages($expert_id){
        if (isValidUuid($expert_id)) {
            $service_packages = ServicePackage::getServicePackages(['user_id' => $expert_id, 'publish' => 'FALSE'], ['deliverables', 'servicePackageTags.Tags', 'servicePackageCategory', 'servicePackageType'], ['order_by' => ['created_at', 'ASC']]);
            $this->getServicePackageCsv($service_packages, 'Drafted');
        }
        return back();
    }
    
    function exportPendingServicePackages(){
        $service_packages = ServicePackage::getServicePackages(['publish' => 'TRUE', 'is_approved' => 'FALSE', 'is_rejected' => 'FALSE'], ['deliverables', 'servicePackageTags.Tags', 'servicePackageCategory', 'servicePackageType'], ['order_by' => ['created_at', 'ASC']]);
        $this->getServicePackageCsv($service_packages, 'Pending');
    }
    
    function exportAllDraftedServicePackages(){
        $service_packages = ServicePackage::getServicePackages(['publish' => 'FALSE', 'is_approved' => 'FALSE', 'is_rejected' => 'FALSE'], ['deliverables', 'servicePackageTags.Tags', 'servicePackageCategory', 'servicePackageType'], ['order_by' => ['created_at', 'ASC']]);
        $this->getServicePackageCsv($service_packages, 'Drafted');
    }
    
    function exportApprovedServicePackages(){
        $service_packages = ServicePackage::getServicePackages(['publish' => 'TRUE', 'is_approved' => 'TRUE'], ['deliverables', 'servicePackageTags.Tags', 'servicePackageCategory', 'servicePackageType'], ['order_by' => ['created_at', 'ASC']]);
        $this->getServicePackageCsv($service_packages, 'Approved');
    }
    
    function exportRejectedServicePackages(){
        $service_packages = ServicePackage::getServicePackages(['is_approved' => 'FALSE', 'is_rejected' => 'TRUE'], ['deliverables', 'servicePackageTags.Tags', 'servicePackageCategory', 'servicePackageType'], ['order_by' => ['created_at', 'ASC']]);
        $this->getServicePackageCsv($service_packages, 'Rejected');
    }
    
    private function getServicePackageCsv($service_packages, $status){
        if(_count($service_packages)){
            $service_package_detail = $this->getServicePackageDetail($service_packages->toArray(), $status);
            $this->makeCsv($service_package_detail);
        }
    }
    
    private function getServicePackageDetail($service_packages, $status){
        $result = [];
        foreach($service_packages as $key=> $service_package){
            $deliverables = [];
            $tags = [];
            if (_count($service_package['deliverables'])) {
                foreach ($service_package['deliverables'] as $deliverable) {
                    $deliverables[] = $deliverable['deliverable'];
                }
            }
            if (_count($service_package['service_package_tags'])) {
                foreach ($service_package['service_package_tags'] as $tag) {
                    $tags[] = $tag['tags']['name'];
                }
            }
            $result[$key]['Expert name'] = userName($service_package['user_id']);
            $result[$key]['Service package name'] = $service_package['name'];
            $result[$key]['Description'] = $service_package['description'];
            $result[$key]['Information from buyer'] = $service_package['buyer_remarks'];
            $result[$key]['Price'] = ($service_package['price']) ? '$'.$service_package['price'] : '';
            $result[$key]['Duration'] = $service_package['duration'] . " day(s)";
            if($status!='Rejected'){
                $result[$key]['Hidden by expert'] = ($service_package['is_hidden']) ? 'Yes' : 'No';
            }
            $result[$key]['Created Date'] = date('d-m-Y', strtotime($service_package['created_at']));
            $result[$key]['Subscription type'] = $service_package['subscription_type'];
            $result[$key]['Package type'] = $service_package['service_package_type']['name'];
            $result[$key]['Deliverables'] = (_count($deliverables)) ? implode(', ', $deliverables) : '';
            $result[$key]['Tags'] = (_count($tags)) ? implode(', ', $tags) : '';
            $result[$key]['Category'] = $service_package['service_package_category']['name'];
            $result[$key]['Status'] = $status;
            $result[$key]['No. of EOIs'] = $service_package['communication_count'];
        }
        return $result;
    }
    
    function approvedServicePackages(Request $request) {
        $service_package_data = $request->all();
        if (!$service_package_data){
            $service_package_data['orderBy'] = 'DESC';
        }
        $published_service_package_count = ServicePackage::fetchServicePackages(['publish' => 'TRUE', 'is_approved' => 'TRUE'], 'count');
        $bread_crumb = 'Approved Service Packages';
        $condition = ['publish' => 'TRUE', 'is_approved' => 'TRUE'];
        $service_packages = ServicePackage::getPackageListingWithUserSorting($condition, config('constants.ADMIN_PER_PAGE_LISTING_COUNT'), $service_package_data);
        if(_count($service_package_data))
        {
            $current_url = $this->currentUrlWithParameters($service_package_data);
            $service_packages->withPath($current_url);
        }
        return view('admin.admin.service_packages_common_listing', compact('bread_crumb', 'service_packages', 'published_service_package_count'));
    }
    
    function rejectedServicePackages(Request $request) {
        $service_package_data = $request->all();
        $rejected_service_package_count = ServicePackage::fetchServicePackages(['is_approved' => 'FALSE', 'is_rejected' => 'TRUE'], 'count');
        $bread_crumb = 'Rejected Service Packages';
        $condition = ['is_approved' => 'FALSE', 'is_rejected' => 'TRUE'];
        $service_packages = ServicePackage::getPackageListingWithUserSorting($condition, config('constants.ADMIN_PER_PAGE_LISTING_COUNT'), $service_package_data);
        if(_count($service_package_data))
        {
            $current_url = $this->currentUrlWithParameters($service_package_data);
            $service_packages->withPath($current_url);
        }
        return view('admin.admin.service_packages_common_listing', compact('bread_crumb', 'service_packages', 'rejected_service_package_count'));
    }
    
    function getDraftedServicePackages(Request $request, $expert_id) {
        if (isValidUuid($expert_id)) {
            $service_package_data = $request->all();
            $order_by = ($service_package_data['orderBy']) ?? 'ASC';
            $drafted_service_package_count = ServicePackage::fetchServicePackages(['user_id' => $expert_id, 'publish' => 'FALSE'], 'count');
            $bread_crumb = 'Drafted Service Packages';
            $service_packages = ServicePackage::getServicePackages(['user_id' => $expert_id, 'publish' => 'FALSE'], [], ['order_by' => ['name', $order_by], 'paginate' => config('constants.ADMIN_PER_PAGE_LISTING_COUNT')]);
            if(_count($service_package_data))
            {
                $current_url = $this->currentUrlWithParameters($service_package_data);
                $service_packages->withPath($current_url);
            }
            return view('admin.admin.draftServicePackageListing', compact('result', 'bread_crumb', 'service_packages', 'drafted_service_package_count'));
        }
        return view('errors.404');
    }
    
    function editServicePackage(Request $request, $service_package_id) {
        if (ctype_digit($service_package_id)) {
            $request_type = request()->segment(2);
            $approved = ($request->approved) ?? '';
            $draft = ($request->draft) ?? '';
            $all_draft = ($request->all_draft) ?? '';
            $bread_crumb = 'Service Package';
            $published_service_package = ServicePackage::getServicePackages(['id' => $service_package_id], ['deliverables', 'servicePackageType'],['first']);
            $expert_id = $published_service_package['user_id'];
            $back_url = $this->backUrlForEditServicePackage($request_type, $expert_id, $approved, $draft, $all_draft);
            $back_link = $back_url;
            if(!empty($approved) && $approved=='true'){
                $bread_crumb = 'Approved';
                $back_link = 'admin/servicepackages';
            }elseif(!empty($approved) && $approved=='false'){
                $bread_crumb = 'Pending';
                $back_link = 'admin/pendingservicepackages';
            }elseif(!empty($all_draft)){
                $bread_crumb = 'Drafted';
                $back_link = 'admin/alldraftedservicepackages';
            }
            $tags= ServicePackageComponent::getServicePackageTags($service_package_id);
            if (!_count($published_service_package)) {
                return view('errors.404');
            }
            $categories= Category::getAllCategories();
            $featured_listing = ServicePackageType::listNameId(['is_featured' => TRUE]);
            return view('admin.admin.editServicePackage', compact('published_service_package', 'bread_crumb', 'request_type', 
                    'approved', 'tags', 'categories', 'draft', 'all_draft', 'back_url', 'featured_listing', 'back_link'));
        }
        return view('errors.404');
    }
    
    private function backUrlForEditServicePackage($request_type, $expert_id, $approved, $draft, $all_draft){
        $ssl=getenv('APP_SSL');
        if(isset($request_type) && $request_type=='editpendingservicepackage'){
            $back_url = url('admin/expert/servicepackages', [$expert_id], $ssl);
        }elseif(!empty($draft)){
            $back_url = url('admin/expert/draftedservicepackages', [$expert_id], $ssl);
        }elseif(!empty($all_draft)){
            $back_url = url('admin/alldraftedservicepackages', [], $ssl);
        }elseif(!empty($approved) && $approved=='false'){
            $back_url = url('admin/pendingservicepackages', [], $ssl);
        }elseif(!empty($approved) && $approved=='true'){
            $back_url = url('admin/servicepackages', [], $ssl);
        }else{
            $back_url = url('admin/expert/servicepackages', [$expert_id], $ssl);
        }
        return $back_url;
    }

    function viewPublishedProject($project_id) {
        if (ctype_digit($project_id)) {
            return Redirect::To('admin/project/' . $project_id);
        }
        return view('errors.404');
    }
    
    private function getDeliverablesAndTags($service_package){
        $deliverable = $tag = '';
        if (_count($service_package['deliverables'])) {
            foreach ($service_package['deliverables'] as $deliverable) {
                $deliverables[] = $deliverable['deliverable'];
            }
            $deliverable="<ul><li>";
            $deliverable.= implode('<li> ', $deliverables);
            $deliverable.="</ul>";
        }
        if (_count($service_package['service_package_tags'])) {
            foreach ($service_package['service_package_tags'] as $tag) {
                $tags[] = $tag['tags']['name'];
            }
            $tag = implode(', ', $tags);
        }
        return ['deliverable' => $deliverable, 'tag' => $tag];
    }
    
    function viewServicePackage(Request $request, $service_package_id) {
        if (ctype_digit($service_package_id)) {
            $all_drafted_service_packages = FALSE;
            $form_data = $request->all();
            $request_type = request()->segment(2);
            $bread_crumb = config('constants.PUBLISHED_SERVICE_PACKAGE');
            if($request_type=='approvedservicepackage'){
                $bread_crumb = config('constants.APPROVED_SERVICE_PACKAGE');
            }elseif($request_type=='rejectedservicepackage'){
                $bread_crumb = config('constants.REJECTED_SERVICE_PACKAGE');
            }elseif($request_type=='draftedservicepackage'){
                $bread_crumb = config('constants.DRAFTED_SERVICE_PACKAGE');
                if(_count($form_data) && array_key_exists('all', $form_data) && $form_data['all'] == 'true'){
                    $all_drafted_service_packages = TRUE;
                }
            }
            $published_service_package = ServicePackage::getServicePackages(['id' => $service_package_id], ['deliverables', 'servicePackageTags.Tags', 'servicePackageCategory', 'servicePackageType'],['first']);
            $deliverable_tag_info = $this->getDeliverablesAndTags($published_service_package);
            $deliverable = $deliverable_tag_info['deliverable'];
            $tag = $deliverable_tag_info['tag'];
            $webflow_page = WebflowPage::getPageByInternalId($service_package_id, config('constants.SERVICE_PACKAGE'));
            $webflow_url = '-';
            if ($webflow_page){
                $webflow_url = $webflow_page->webflow_url;
            }
            if (!_count($published_service_package)) {
                return view('errors.404');
            }
            return view('admin.admin.viewServicePackage', compact('published_service_package', 'bread_crumb', 'request_type', 'deliverable', 'tag','webflow_url', 'all_drafted_service_packages'));
        }
        return view('errors.404');
    }
    
    function viewPendingServicePackage($service_package_id) {
        if (ctype_digit($service_package_id)) {
            $bread_crumb = config('constants.PENDING_SERVICE_PACKAGE');
            $published_service_package = ServicePackage::getServicePackages(['id' => $service_package_id], ['deliverables', 'servicePackageTags.Tags', 'servicePackageCategory', 'servicePackageType'],['first']);
            if (!_count($published_service_package)) {
                return view('errors.404');
            }
            $deliverable_tag_info = $this->getDeliverablesAndTags($published_service_package);
            $deliverable = $deliverable_tag_info['deliverable'];
            $tag = $deliverable_tag_info['tag'];
            return view('admin.admin.viewPendingServicePackage', compact('published_service_package', 'bread_crumb', 'deliverable', 'tag'));
        }
        return view('errors.404');
    }

    function viewPendingProject($project_id) {
        if (ctype_digit($project_id)) {
            return Redirect::To('admin/project/' . $project_id);
        }
        return view('errors.404');
    }

    function viewHub(Request $request, $hub_id){

        if (ctype_digit($hub_id)) {
            $hub = (new ServiceHub)->getHubWithVendorDetails($hub_id);
            if (!_count($hub)){
                return view('errors.404');
            }
            $hubs_count = (new CommonFunctionsComponent)->hubsCount();
            $hub_bread_crumb = $this->getHubBreadCrumb($hub);
            $bread_crumb = $hub_bread_crumb['bread_crumb'];
            $back_url = $hub_bread_crumb['back_url'];
            $first_applicant_view = '';
            $hub_info = (new ServiceHub())->getServiceHubWithApprovedExpert($hub->user_id);
            $right_hand_block = (new ServiceHubComponent())->serviceHubRightHandBlock($hub_info);
            $all_experts_listing = (new ServiceHubComponent())->allExpertsList($hub_info);

            return view('admin.admin.viewHub',
                compact('hub', 'hubs_count', 'hub_info', 'all_experts_listing', 'first_applicant_view', 'right_hand_block', 'bread_crumb', 'back_url'));
        }
        return view('errors.404');
    }

    private function getHubBreadCrumb($hub){

        switch ($hub->status){
            case config('constants.SERVICE_HUB_STATUS.LIVE'):
                $bread_crumb['back_url'] = 'admin/' . config('constants.ADMIN_HUBS_VIEWS.LIVE_HUBS');
                $bread_crumb['bread_crumb'] = config('constants.ADMIN_HUBS_BREADCRUMB.LIVE_HUBS');
                break;
            case config('constants.SERVICE_HUB_STATUS.PENDING'):
                $bread_crumb['back_url'] = 'admin/' . config('constants.ADMIN_HUBS_VIEWS.PENDING_HUBS');
                $bread_crumb['bread_crumb'] = config('constants.ADMIN_HUBS_BREADCRUMB.PENDING_HUBS');
                break;
            case config('constants.SERVICE_HUB_STATUS.ARCHIVED'):
                $bread_crumb['back_url'] = 'admin/' . config('constants.ADMIN_HUBS_VIEWS.ARCHIVED_HUBS');
                $bread_crumb['bread_crumb'] = config('constants.ADMIN_HUBS_BREADCRUMB.ARCHIVED_HUBS');
                break;
        }
        return $bread_crumb;
    }

    function viewProject(Request $request, $project_id)
    {
        if (ctype_digit($project_id))
        {
            $project = PostJob::find(trim($project_id));
            if (!_count($project))
                return view('errors.404');
            $rebook_project = $project->rebook_project;
            $from_buyer_project_listing = isset($request->from_buyer_project_listing) ? true : false;
            $contract_status='';

            if ($project->accepted_contract_id) {
                $status = ( new Contract)->ContractCompletedOrInProgress($project_id) ?? false;
                $contract_status = (!$status) ? 'contract_completed' : 
                    ($status && $status->complete_status == config('constants.COMPLETED'))? 
                    'contract_completed' : 'in_contract';
            }
            $type = config('constants.PROJECT');
            $bread_crumb = $this->getPageBreadcrumb($project->publish, $project->visibility_date, $from_buyer_project_listing, '', $contract_status)['bread_crumb'];
            $back_url = $this->getPageBreadcrumb($project->publish, $project->visibility_date, $from_buyer_project_listing, $project->user_id, $contract_status)['back_url'];
            $deliverables = Deliverable::findByCondtion(['post_job_id' => $project_id, 'type' => 'project']);
            $buyer_detail = User::getBuyerInformation($project->user_id);
            $tools = JobsSkill::getProjectToolsByProjectId($project_id);
            $skills = JobsSkill::getProjectSkillsByProjectId($project_id);
            $buyer_information = userInfo($project->user_id)[0];
            $redirect_url = ($request->all()['redirect-url']) ?? '';
            $type_of_organization = (!empty($buyer_detail[0]['company_name'])) ? trimFirstName($buyer_detail[0]['company_name']) :
                                    TypeOfOrganization::getTypeOfOrganizationNameByID($buyer_detail[0]['type_of_organization_id']);
            (!empty($project->upload_document)) ? $images = json_decode($project->upload_document, TRUE) : $images = [];
            $office_location = (!empty($project->office_location)) ? 
                $project->office_location : 
                $buyer_detail[0]['office_location'];
            $project_label = 'Project';
            if (_count(app('request')->input()) && array_key_exists('source', app('request')->input()))
            {
                $bread_crumb = app('request')->input('source');
                $back_url='admin/buyerView/'.$project->user_id;
                $project_label = 'Client';
                
                if(app('request')->input('source') == config('constants.REBOOK'))
                {
                    $bread_crumb = 'Rebooked project';
                    $back_url='admin/rebookingProjects';
                }
            }
            $webflow_page = WebflowPage::getPageByInternalId($project_id, config('constants.PROJECT'));
            $webflow_url = '-';
            if ($webflow_page){
                $webflow_url = $webflow_page->webflow_url;
            }
            $project_details=['project', 'bread_crumb', 'deliverables', 'office_location', 
                'images', 'tools', 'skills', 'buyer_detail', 'from_buyer_project_listing', 'type_of_organization', 
                'buyer_information', 'project_label', 'back_url', 'redirect_url', 'webflow_url'];
            $user_type = $buyer_information->user_type_id;
            $user_list = Communication::expertList($project->user_id,'project', $project_id);
            $contracts = Contract::getAllAcceptedContracts($project_id);
            $user_information = ($contracts)?getUserDetails($contracts[0]['user_id']):[];
            $communication_data=['user_list', 'user_type', 'contracts', 'user_information'];

            $business_data = [];
            $expert_business_type = '0';
            $expert_business_details = [];
            $expert_business_address = [];
            $buyer_business_details = [];
            $buyer_business_address = [];
            if ($contracts){
                $business_information = new BusinessInformation();
                $expert_business_information = $business_information->getUserBusinessInformation($contracts[0]['user_id']);
                if ($expert_business_information)
                {
                    $expert_business_type = $expert_business_information->type;
                    $expert_business_details = $expert_business_information->businessDetails;
                    $expert_business_address = $expert_business_information->businessAddress;
                }
                $buyer_business_information = $business_information->getUserBusinessInformation($contracts[0]['buyer_id']);
                if ($buyer_business_information)
                {
                    $buyer_business_details = $buyer_business_information->businessDetails;
                    $buyer_business_address = $buyer_business_information->businessAddress;
                }
            }
            $business_data =[
                'expert_business_type',
                'expert_business_details',
                'expert_business_address',
                'buyer_business_details',
                'buyer_business_address'
            ];
            $projects_count = (new CommonFunctionsComponent)->projectsCount();

            return view('admin.admin.viewProject', compact('projects_count', 'type', 'rebook_project', 
                array_merge($project_details, $communication_data, $business_data)));
        }
        return view('errors.404');
    }

    private function getPageBreadcrumb($publish_status, $visibility_date, $from_buyer_project_listing=false, $user_id = '', $in_contract = '') {
       
        if ($from_buyer_project_listing)
            return ['back_url' => "admin/getProjects/$user_id", 'bread_crumb' => config('constants.BUYER_PROJECT')];
        
        if ($publish_status == config('constants.PROJECT_REJECTED'))
            return ['back_url' => 'admin/archivedProjects', 'bread_crumb' => config('constants.ARCHIVED_PROJECT')];
        
        if ($publish_status == config('constants.PROJECT_PENDING'))
            return ['back_url' => 'admin/pendingProjects', 'bread_crumb' => config('constants.PENDING_PROJECT')];
        
        if ($in_contract == 'contract_completed')
            return ['back_url' => 'admin/completedProjects', 'bread_crumb' => config('constants.CONTRACT_COMPLETED')];

        if ($in_contract =='in_contract')
            return ['back_url' => 'admin/inContractProjects', 'bread_crumb' => config('constants.IN_CONTRACT_PROJECT')];
        
        if ( $in_contract =='' && strtotime(date('d-m-Y')) > strtotime(date('d-m-Y', strtotime($visibility_date))))
            return ['back_url' => 'admin/expiredProjects', 'bread_crumb' => config('constants.EXPIRED_PROJECT')];
        
        if ($publish_status == config('constants.PUBLISHED'))
            return ['back_url' => 'admin/liveProjects', 'bread_crumb' => config('constants.LIVE_PROJECT')];
    }
    
    public function updateProjectStatus(Request $request)
    {
        $response = 0;
        $form_data = $request->all();
        if (array_key_exists('user_id', $form_data) && 
            !empty($form_data['user_id']) && 
            !isValidUuid($form_data['user_id']))
        {
            return 0;
        }
        $id = $form_data['id'];
        $user_information = userInfo($form_data['user_id'])[0];
        $admin_approval_status = $user_information->admin_approval_status;
        $project_details = PostJob::findByCondition(['id' => $id]);
        $response = PostJob::updatePostData(['publish' => config('constants.PUBLISHED'), 'publish_date' => Carbon::now()], $id);
        (new SegmentComponent)->projectTracking(
            $form_data['user_id'], $id, $project_details[0]->job_title, "Project Approved", $project_details[0]->created_at->toDateTimeString()
        );
        if ($response == config('constants.UPDATED'))
        {
            $visibility_date = date("Y-m-d H:i:s", strtotime("+3 day"));
            PostJob::updatePostData(['visibility_date' => $visibility_date], $id);
            if (User::updateUser($form_data['user_id'], ['admin_approval_status' => config('constants.ACCEPTED'), 'admin_approval_time' => Carbon::now()]))
            {
                if ($admin_approval_status != config('constants.APPROVED'))
                {
                    $this->approveUserEmail($user_information->user_type_id, $form_data['user_id']);
                }
            }
            if($project_details[0]->publish != config('constants.PUBLISHED'))
                Email::newProjectEmailNotification(['project_id' => $id]);
            
            if (isset($form_data['user_id']) && !empty($form_data['user_id']))
            {
                Email::projectVerficationEmail(['user_id' => $form_data['user_id'], 'project_id' => $id]);
                $response = 1;
            }
        }
        return $response;
    }

    public function rejectProject(Request $request)
    {
        $form_data = $request->all();
        $id = $form_data['id'];
        $response = 0;
        if (ctype_digit($id))
        {
            $project_details = PostJob::findByCondition(['id' => $id]);
            $response = PostJob::where('id', $id)
                ->update(['publish' => config('constants.PROJECT_REJECTED')]);
            (new SegmentComponent)->projectTracking(
                $project_details[0]->user_id, $id, $project_details[0]->job_title, "Project Rejected", $project_details[0]->created_at->toDateTimeString()
            );
        }
        return $response;
    }

    public function exportProjects(Request $request) {
        $today = date('Y-m-d H:i:s'); 
        $result = PostJob::exportExpiredProjects([config('constants.PUBLISHED'), config('constants.PROJECT_PENDING')],$today);
        $user_information = excelProjectInformation($result, config('constants.EXPIRED_PROJECT'));
        $this->makeCsv($user_information);
    }
    
    public function exportInContractProjects() {
        $result = (new PostJob)->exportInContractProjects();
        $user_information = excelProjectInformation($result, config('constants.IN_CONTRACT_PROJECT'));
        $this->makeCsv($user_information);
    }
    
    public function exportCompletedProjects() {
        $result = (new PostJob)->exportCompletedProjects();
        $user_information = excelProjectInformation($result, config('constants.CONTRACT_COMPLETED'));
        $this->makeCsv($user_information);
    }

    public function exportArchivedProjects(Request $request) {
        $all_projects=PostJob::exportProjectsInformation(config('constants.PROJECT_REJECTED'), date('Y-m-d H:i:s'));
        $user_information = excelProjectInformation($all_projects);
        $this->makeCsv($user_information);
    }

    public function exportProjectsInformation($publish) {
        $result = PostJob::exportProjectsInformation($publish,date('Y-m-d H:i:s'));
        $user_information = excelProjectInformation($result);
         $this->makeCsv($user_information);
    }

    public function exportBuyerProjects($publish, $buyer_id) {
        $result = PostJob::exportPublishedBuyerProjects($publish, $buyer_id, 'asc');
        $user_information = excelProjectInformation($result);
        $this->makeCsv($user_information);
    }

    function editLiveProject(Request $request, $project_id) {
        if (ctype_digit($project_id)) {
            $project = PostJob::find(trim($project_id));
            if((_count($project->contract) 
                && $project->contract->status== config('constants.ACCEPTED')) 
                || $project->publish == config('constants.ARCHIVED'))
                return back();
            $from_buyer_project_listing=isset($request->from_buyer_project_listing)?true:false;
            $breadcrumb = $this->getPageBreadcrumb($project->publish, $project->visibility_date, $from_buyer_project_listing)['bread_crumb'];
            $back_url = $this->getPageBreadcrumb($project->publish, $project->visibility_date, $from_buyer_project_listing, $project->user_id)['back_url'];
            if (isset($project) && _count($project) == 0) {
                return view('errors.404');
            }
            $type_of_org_list = TypeOfOrganization::listAll();
            $deliverables = Deliverable::findByCondtion(['post_job_id'=>$project_id,'type'=>'project']);
            $buyer_detail = BuyerProfile::getBuyerInformation($project->user_id);
            $tools= JobsSkill::getProjectToolsByProjectId($project_id);
            $skills= JobsSkill::getProjectSkillsByProjectId($project_id);
            $office_location = (!empty($project->office_location))?$project->office_location:$buyer_detail[0]['office_location'];
            $redirect_url = $request->get('redirect-url') ?? '';
            $projects_count = (new CommonFunctionsComponent)->projectsCount();
            return view('admin.admin.editProject', compact('project','buyer_detail', 'breadcrumb', 'deliverables', 'projects_count',
                'type_of_org_list','tools','skills','office_location','from_buyer_project_listing', 'back_url', 'redirect_url'));
        }
        return view('errors.404');
    }

    public function updateUserProfileMandateFieldsCheckScript() {
        $expert_profiles = User::getUserWithSkills();
        $experts_that_have_completed_the_basic_profile = [];
        if (_count($expert_profiles)) {
            foreach ($expert_profiles as $key => $expert_profile) {
                $mandate_field_completeness = mandateFieldsCompleteness($expert_profile);
                if ($mandate_field_completeness['basic_profile_completness']) {
                    $experts_that_have_completed_the_basic_profile[] = $expert_profile['id'];
                }
            }
        }
        if (_count($experts_that_have_completed_the_basic_profile)) {
            $query = UserProfile::updateExpertFields($experts_that_have_completed_the_basic_profile);
        }
        if (isset($query) && $query == TRUE) {
            return 'updated';
        }
        return 'not updated';
    }

    public function exportArchivedExpert() {
        $result = User::findByCondition(['status' => config('constants.REJECTED'), 
            'user_type_id' => config('constants.EXPERT')], [], 
            ['order_by' => ['name', 'asc']]);
        if(_count($result)){
            $result = $result->toArray();
            foreach ($result as $keys => $val) {
                $user_information[$keys]['First Name'] = $val['name'];
                $user_information[$keys]['Last Name'] = $val['last_name'];
                $user_information[$keys]['Email'] = $val['email'];
                $user_information[$keys]['Phone'] = $val['phone_num'];
                $user_information[$keys]['Vat Number'] = $val['vat_country_code'].$val['vat_number'];
                $user_information[$keys]['MM ID'] = $val['mm_unique_num'];
                $user_information[$keys]['Application Date'] = date('d-m-Y', strtotime($val['created_at']));
            }
            $this->makeCsv($user_information);
        }
    }
    
    public function exportArchivedBuyer() {
        $result = User::findByCondition(['status' => config('constants.REJECTED'), 
            'user_type_id' => config('constants.BUYER')], [], 
            ['order_by' => ['name', 'asc']]);
        if(_count($result)){
            foreach ($result as $keys => $val) {
                $user_information[$keys]['First Name'] = $val['name'];
                $user_information[$keys]['Last Name'] = $val['last_name'];
                $user_information[$keys]['Email'] = $val['email'];
                $user_information[$keys]['Phone'] = $val['phone_num'];
                $user_information[$keys]['Vat Number'] = $val['vat_country_code'].$val['vat_number'];
                $user_information[$keys]['How soon do you need to get a project done?'] = $val['buyer_profile']['expected_project_post_time'] ? config('constants.EXPECTED_PROJECT_POST_TIME.'.$val['buyer_profile']['expected_project_post_time']) : '';
                $user_information[$keys]['MM ID'] = $val['mm_unique_num'];
                $user_information[$keys]['Application Date'] = date('d-m-Y', strtotime($val['created_at']));
            }
            $this->makeCsv($user_information);
        }
    }

    public function exportNotVerifiedBuyer(Request $request) {
        $has_array = [];
        $user_type = config('constants.BUYER');
        if (isset($request['user_type'])){
            $user_type = $request['user_type'];
        }
        $result = User::findByCondition([
                'user_type_id' => $user_type,
                'users.status' => config('constants.PENDING'),
                'users.is_deleted' => config('constants.PENDING'),
                ], [
                'buyer_profile'], ['order_by' =>
                ['name', 'asc']
                ], $has_array);
        $user_information = [];
        foreach ($result as $keys => $val) {
            $parent_comapny = '';

            if (isset($val['buyer_profile']['parent_company']) && ($val['buyer_profile']['parent_company'] != '-1')) {
                $parent_comapny = $val['buyer_profile']['parent_company'];
            }
            $type_of_organization = '';
            $type_of_org = getTypeOfOrganization();
            foreach ($type_of_org as $type) {
                if ($val['buyer_profile']['type_of_organization_id'] == $type->id) {
                    $type_of_organization = $type->name;
                }
            }
            $user_information[$keys]['First Name'] = $val['name'];
            $user_information[$keys]['Last Name'] = $val['last_name'];
            $user_information[$keys]['Email'] = $val['email'];
            $user_information[$keys]['MM ID'] = $val['mm_unique_num'];
            $user_information[$keys]['Application Date'] = date('d-m-Y', strtotime($val['created_at']));
            $user_information[$keys]['Company Name'] = $val['buyer_profile']['company_name'];
            $user_information[$keys]['Company Url'] = $val['buyer_profile']['company_url'];
            $user_information[$keys]['How soon do you need to get a project done?'] = $val['buyer_profile']['expected_project_post_time'] ? config('constants.EXPECTED_PROJECT_POST_TIME.'.$val['buyer_profile']['expected_project_post_time']) : '';
            $user_information[$keys]['Phone'] = $val['phone_num'];
            $user_information[$keys]['Office Location'] = ($val['buyer_profile']['office_location']) ? str_replace('<br/>', ', ', $val['buyer_profile']['office_location']) : '';
            $user_information[$keys]['Parent Company'] = $parent_comapny;
            $user_information[$keys]['Bio'] = ($val['buyer_profile']['bio']) ? strip_tags($val['buyer_profile']['bio']) : '-';
            $user_information[$keys]['Type of Organization'] = $type_of_organization;
            $post = '';
            $post = getPostJobs($val['id']);
            if (isset($post) && !empty($post)) {
                end($post);
                $key = key($post);
            }
            $postInfo = [];
            if (isset($post) && !empty($post)) {
                foreach ($post as $k => $ps) {
                    $postInfo[] = trim($ps->job_title);
                }
            }
            $finalPost = implode(',', $postInfo);
            $user_information[$keys]['Projects (titles)'] = ($finalPost) ? $finalPost : '';
        }

        $this->makeCsv($user_information);
    }
    
    private function makeCsv($user_information){
        return Excel::create('Measure Match' . strtotime(date('h:i:s')), function($excel) use ($user_information) {
                    $excel->sheet('mySheet', function($sheet) use ($user_information) {
                        $sheet->fromArray($user_information);
                    });
                })->download('csv');
    }

    public function exportSkills(Request $request) {
        if ($request->get('upload_file')) {
            $request->validate([
                'file' => 'required|file|mimes:png|max:512',
                'skill_id' => 'required',
            ]);
            $url = uploadFile($request->file('file'));
            $skill_id = explode(',', $request->get('skill_id'));
            (new Skill())->whereIn('id', $skill_id)->update(['logo_url' => $url]);
           return json_encode(['success' => true]);
        }
        $skill_name = $request->get('name');
        $results = (new Skill())->getExpertSkills($skill_name);
        return view('admin.admin.expert_skills', compact('results', 'skill_name'));
    }
    
    public function getContractDetailsPopup(Request $request) {
        $contract = Contract::find($request->id);
        return ['success' => 1, 'content' => view('admin.admin.include.contract_view_edit_popup', compact('contract'))->render()];
    }
     public function updateContractDetails(Request $request) {
        $form_data = $request->all();
        $contract_id = $form_data['contract_id'];
        $custom_messages= __('custom_validation_messages');
        $messages = [
            'job_start_date.required' => $custom_messages['post_job']['job_start_date'],
            'job_end_date.required' => $custom_messages['post_job']['job_end_date'],
            'rate.required' => $custom_messages['post_job']['rate'],
        ];
        $validator = Validator::make($form_data, [
                'job_start_date' => 'required',
                'job_end_date' => 'required',
                'rate' => 'required',
                ], $messages);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->errors())->withInput();
        } else {

            $data['job_start_date'] = date('Y-m-d', strtotime($form_data['job_start_date']));
            $data['job_end_date'] = date('Y-m-d', strtotime($form_data['job_end_date']));
            $data['rate'] = isset($form_data['rate']) ? $form_data['rate'] : '';
            return Contract::updateContractInformation($contract_id, $data);
        }
    }

    public function exportExpertsToWebflow(){
        if((new WebflowComponent())->exportAllApprovedExperts()){
            return ['success' => true];
        }
        return ['success' => false];
    }

    public function exportServicePackagesToWebflow(){
        if ((new WebflowComponent())->exportAllApprovedServicePackages()){
            return ['success' => true];
        }
        return ['success' => false];
    }

    public function switchVendorInviteSetting(Request $request){
        $user_id = $request->all()['id'];

        if (isValidUuid($user_id)) {
            $vendor = User::find($user_id);
            if ($vendor->user_type_id == config('constants.VENDOR') && $vendor->admin_approval_status == config('constants.APPROVED')) {
                $json = json_decode($vendor->settings, 1);
                $invite_mandatory = filter_var($json['invite_mandatory'], FILTER_VALIDATE_BOOLEAN);
                updateUserSettingsById(['invite_mandatory' => !$invite_mandatory], $user_id);
                return ['success' => true];
            }
        }
        return ['success' => false];
    }


    public function hardDeleteExpert(Request $request){
        $input = $request->all();
        $expert_id = $input['id'];
        $expert_status = $input['status'];

        switch (true){
            case $expert_status === config('constants.UNVERIFIED_LABEL'):
                $this->hardDeleteUnverifiedExpert($expert_id);
                return redirect('admin/notverifiedexperts');
        }
        return redirect('admin/expertListing');
    }

    private function hardDeleteUnverifiedExpert($user_id){
        (new UsersCommunication)->deleteUserCommunications($user_id);
        (new UserProfile)->deleteUserProfile($user_id);
        (new User)->forceDeleteUser($user_id);

    }
}
