<?php

namespace App\Http\Controllers;

use App\Components\SegmentComponent;
use App\Components\ServiceHubComponent;
use App\Model\BuyerProfile;
use App\Model\ServiceHub;
use App\Model\ServiceHubCategory;
use App\Model\ServiceHubApplicant;
use App\Model\ServiceHubAssociatedExpert;
use App\Model\VendorInvitedExpert;
use App\Model\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\ServiceHub\CreateHub;
use App\Http\Requests\storeSeriviceHubApplicatnt;
use App\Components\Email;
use Auth;
use App\Model\ServiceHubRejectedApplicantDetail;

class ServiceHubController extends Controller
{
    function index($step = null)
    {
        $user_id = $this->getUserId();
        $user_setting = json_decode(\Auth::user()->settings, true);
        $invited_experts = [];
        $inviting_experts_mandatory = (isset($user_setting) && array_key_exists('invite_mandatory', $user_setting) && $user_setting['invite_mandatory'] == 'true' ) ? true : false;
        if(null != $step && !in_array($step, $this->allowedSteps()))
            return redirect(route('service-hubs-create'));
        $vendor_data = (new BuyerProfile())->getCompanyNameByBuyerId($user_id);
        $hub_info = (new ServiceHub())->getServiceHubInfo($user_id);
        if ($step != config('constants.VENDOR_HUB_STEP_1') && (!$hub_info || $hub_info == null)) {
            return redirect(route('service-hubs-create', [ config('constants.VENDOR_HUB_STEP_1')]));
        }
        if(!empty($hub_info))
            $invited_experts = (new VendorInvitedExpert())->findWithConditions(['service_hub_id' => $hub_info->id]);
        if($step == config('constants.VENDOR_HUB_STEP_3') && $inviting_experts_mandatory && !_count($invited_experts))
            return redirect(route('service-hubs-create', [ config('constants.VENDOR_HUB_STEP_2')]));
        return view('service-hub.index', compact('vendor_data', 'step', 'hub_info', 'invited_experts', 'inviting_experts_mandatory'));
    }

    private function getUserId() {
        return \Auth::user()->id;
    }
    
    private function allowedSteps()
    {
        return [
            config('constants.VENDOR_HUB_STEP_1'),
            config('constants.VENDOR_HUB_STEP_2'),
            config('constants.VENDOR_HUB_STEP_3')
        ];
    }

    public function vendorServiceHubsDetails($id) {
        $hub_info = (new ServiceHub())->getServiceHubInfo($this->getUserId(), $id);
        $is_buyer = isBuyer();
        $experts_listing = (new ServiceHubAssociatedExpert)->approvedExpertListing(['service_hub_id' => $id, 'status' => 1]);
        $view_more = 1;
        if($experts_listing->lastPage() == $experts_listing->currentPage())
            $view_more = 0;
        if (!$hub_info)
            return view('errors.404');
        return view('service-hub.service_hub_details', compact('hub_info', 'view_more', 'experts_listing', 'is_buyer'));
    }
    
    function vendorHubs()
    {
        $service_hub_component = new ServiceHubComponent();
        $first_applicant_view = '';
        $hub_info = (new ServiceHub())->getServiceHubWithApprovedExpert($this->getUserId());
        $right_hand_block = $service_hub_component->serviceHubRightHandBlock($hub_info);
        $all_experts_listing = $hub_info ? $service_hub_component->allExpertsList($hub_info) : [];
        return view('service-hub.my_hubs', compact('hub_info', 'all_experts_listing', 'first_applicant_view', 'right_hand_block'));
    }

    function vendorServiceHubs() {
        $service_hubs = (new ServiceHub())->getPublishServiceHub();
        return view('service-hub.vendor_service_hub', compact('service_hubs'));
    }

    function appyToServiceHub(storeSeriviceHubApplicatnt $request)
    {
        $exist = (new ServiceHubApplicant)->isApplicantExist($request->service_hub_id, $this->getUserId());
        $response = 0;
        if(!$exist){
            $applicant['user_id'] = $this->getUserId();
            $applicant['service_hub_id'] = $request->service_hub_id;
            $applicant['total_experience'] = $request->total_experience;
            $applicant['recent_case_study'] = $request->recent_case_study;
            $applicant['status'] = config('constants.SERVICE_HUB_EXPERT_STATUS.PENDING');
            $applicant['is_applicant'] = 'true';
            $service_hub_info = (new ServiceHub())->find($applicant['service_hub_id']);            
            $service_hub_associated_expert = ServiceHubAssociatedExpert::create($applicant);
            if($service_hub_associated_expert)
            {
               $applicant['service_hub_associated_expert_id'] = $service_hub_associated_expert->id;
               ServiceHubApplicant::create($applicant);
               Email::emailToVendorWhenApplyToServiceHub(['vendor_id' => $service_hub_info->user_id]);
            }
            $response = 1;
        }
        return $response;
    }

    function store(CreateHub $request) {
        $inputs = \Request::all();
        try {
            $user_id = \Auth::user()->id;
            \DB::beginTransaction();
            $service_object = (new ServiceHub());
            $hub_info = $service_object->getServiceHubInfo($user_id);
            if (isset($inputs['steps']) && $inputs['steps'] == 3) {
                $service_object->updateData($hub_info->id, ['publish' => true]);
                $new_hub = ServiceHub::find($hub_info->id);
                (new SegmentComponent)->hubTracking($hub_info->id, $new_hub->user_id, $new_hub->name, 'Hub Created');
            } else {
                $inputs['logo'] = $hub_info && !isset($inputs['logo']) ? $hub_info->logo : uploadFile($inputs['logo']);
                $inputs['user_id'] = $user_id;
                $inputs['publish'] = false;
                $hub_info &&  $service_object->updateData($hub_info->id, $this->dataToBeUpdated($inputs));
                $hub_id = $hub_info ? $hub_info->id : '';
                $update = true;
                if (!$hub_info) {
                    $hub_info = ServiceHub::create($inputs);
                    $update = false;
                    $hub_id = $hub_info->id;
                }
                $categories_name = $this->sanatizeServiceCategory($inputs, $hub_id);
                $update && ServiceHubCategory::whereServiceHubId($hub_id)->delete();
                ServiceHubCategory::insert($categories_name);
            }
            \DB::commit();
            if (isset($inputs['steps']) && $inputs['steps'] == 3) {
                $this->sendInviteEmails($hub_info->id);
                return redirect(route('service-hubs'))->withSuccess(['success' => true]);
            }
            return ['redirect_url' => route('service-hubs-create', [ config('constants.VENDOR_HUB_STEP_2')])];
        } catch(\Exception $e) {
            \DB::rollback();
        }
    }
    
    private function sendInviteEmails($service_hub_id)
    {
        $vendor_invited_experts = new VendorInvitedExpert;
        $invited_experts = $vendor_invited_experts
            ->findWithConditions(['service_hub_id' => $service_hub_id, 'email_sent' => false]);
        if(_count($invited_experts))
        {
            foreach ($invited_experts as $expert)
            {
                (new Email)->inviteExpertsByVendor([
                    'first_name' => $expert['first_name'],
                    'email' => $expert['email'],
                    'vendor_id' => Auth::user()->id]);
            }
            $vendor_invited_experts->updateWithConditions (['service_hub_id' => $service_hub_id], ['email_sent' => true]);
        }            
    }

    private function sanatizeServiceCategory($inputs, $hub_id) {
        $categories_name = [];
        foreach($inputs['service_category_name'] as $name) {
            $categories_name [] = [
                'name' => $name,
                'service_hub_id' => $hub_id,
                'created_at' => date('Y-m-d H:m:s'),
                'updated_at' => date('Y-m-d H:m:s'),
            ];
        }
        return $categories_name;
    }

    private function dataToBeUpdated($inputs) {
        return [
            'name' => $inputs['name'],
            'logo' => $inputs['logo'],
            'sales_email' => $inputs['sales_email'],
            'service_website' => $inputs['service_website'],
            'description' => $inputs['description'],
        ];
    }
    
    public function approveExpert(Request $request)
    {
        $response = config('constants.NOTACCEPTED');
        $inputs = $request->all();
        if((new ServiceHubAssociatedExpert)->updateData(['user_id' => $inputs['expert_id']], ['status' => config('constants.APPROVED')]))
        {
            (new Email)->ApproveExpertByVendor([
                    'expert_id' => $inputs['expert_id'],
                    'vendor_company_name' => $inputs['vendor_company_name']
                ]
            );
            $response = config('constants.APPROVED');
        }            
        return $response;
    }
    
    public function declineExpert(Request $request)
    {
        $response = config('constants.NOTACCEPTED');
        $inputs = $request->all();
        $validator = \Validator::make($inputs, [
                'message' => 'required',
                'service_hub_applicant_id' => 'required',
                'expert_id' => 'required'
                ]);
        if ($validator->fails())
            return config('constants.NOTACCEPTED');
        
        if((new ServiceHubAssociatedExpert)->updateData(['user_id' => $inputs['expert_id']], ['status' => config('constants.REJECTED')]))
        {
            ServiceHubRejectedApplicantDetail::create($inputs);
            (new Email)->DeclineExpertByVendor([
                    'expert_id' => $inputs['expert_id'],
                    'message' => $inputs['message'],
                    'vendor_company_name' => $inputs['vendor_company_name']
                ]
            );
            $response = config('constants.TRUE');
        }
        return $response;
    }
    
    public function viewHub(Request $request, $service_hub_id)
    {
        $input_data = $request->all();
        $experts_listing = (new ServiceHubAssociatedExpert)->approvedExpertListing(['service_hub_id' => $service_hub_id, 'status' => 1]);
        if(_count($input_data))
        {
            $view_more = 1;
            if($experts_listing->lastPage() == $experts_listing->currentPage())
                $view_more = 0;
            return ['view_more' => $view_more,
                    'html' => view('service-hub.more_approved_experts', compact('experts_listing', 'view_more'))->render()];
        }
        $hub_info = (new ServiceHub)->serviceHubWithRelatedModels(['id' => $service_hub_id], ['serviceCategories']);
        if(empty($hub_info))
            return 0;
        return view('service-hub.view_hub_pop_up', compact('hub_info', 'experts_listing'))->render();
    }
    
    public function markApplicantAsSeen($applicant_id)
    {
        (new ServiceHubApplicant)->udpateData(['id' => $applicant_id], ['is_read' => true]);
    }
    
    public function allExpertsList($hub_info = null, $expert_id = null)
    {
        return (new ServiceHubComponent())->allExpertsList($expert_id, $hub_info);
    }

    public function serviceHubRightHandBlock($hub_info = null, $expert_id = null)
    {
        return (new ServiceHubComponent())->serviceHubRightHandBlock($expert_id, $hub_info);
    }

    public function applicantDetails($expert_user_id)
    {
        return (new ServiceHubComponent())->applicantDetails($expert_user_id);
    }
    
    public function approveRejectServiceHub(Request $request)
    {
        if(!array_key_exists('id', $request->all())
            || !ctype_digit($request->id))
            return config('constants.FALSE');
        $id = $request->id;
        if((new ServiceHub)->updateData($id, ['status' => $request->status]))
            return config('constants.TRUE');
        return config('constants.FALSE');
    }
}
