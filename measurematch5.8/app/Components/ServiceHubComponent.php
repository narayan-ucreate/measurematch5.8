<?php


namespace App\Components;


use App\Model\ServiceHub;
use App\Model\ServiceHubApplicant;
use App\Model\ServiceHubAssociatedExpert;
use App\Model\UserProfile;
use Auth;

class ServiceHubComponent
{
    public function allExpertsList($hub_info = null, $vendor_id = null)
    {
        $vendor_id = ($vendor_id != null) ? $vendor_id : Auth::user()->id;
        if($hub_info == null && $vendor_id != null)
            $hub_info = (new ServiceHub())->getServiceHubWithApprovedExpert($vendor_id);
        $applicants_count = $hub_info->unapproved_applicants_count ?? 0;
        $declined_count = $hub_info->declined_applicants_count ?? 0;
        $invited_count = isset($hub_info->serviceExperts) ? _count($hub_info->serviceExperts) : 0;
        $approved_count = isset($hub_info->approveExperts) ? _count($hub_info->approveExperts) : 0;
        $total_applications = $hub_info->my_application_info_count ?? 0;
        return view('service-hub.all_experts_list',
            compact('hub_info', 'applicants_count', 'declined_count',
                'invited_count', 'approved_count', 'total_applications'))
            ->render();
    }

    public function serviceHubRightHandBlock($hub_info = null, $vendor_id = null)
    {
        $vendor_id = ($vendor_id != null) ? $vendor_id : Auth::user()->id;
        if($hub_info == null && $vendor_id != null)
            $hub_info = (new ServiceHub())->getServiceHubWithApprovedExpert($vendor_id);
        if(!empty($hub_info->unapprovedApplicants) && isset($hub_info->unapprovedApplicants[0]->id))
            return $this->applicantDetails($hub_info->unapprovedApplicants[0]->id);
        
        if(!empty($hub_info->approveExperts) && isset($hub_info->approveExperts[0]->id))
            return $this->applicantDetails($hub_info->approveExperts[0]->id);
        
        return view('service-hub.default_live_hub_block');
    }

    public function applicantDetails($service_hub_associated_expert_id)
    {
        $applicant_info = (new ServiceHubAssociatedExpert)->findWithConditions([
            'id' => $service_hub_associated_expert_id],
            'first', ['serviceHub', 'applicantDetail']);
        $user_id = $applicant_info->user_id;
        if (Auth::user()->user_type_id == config('constants.VENDOR') && isset($applicant_info->applicantDetail->id)){
            (new ServiceHubApplicant)->udpateData(['id' => $applicant_info->applicantDetail->id], ['is_read' => true]);
        }
        $expert_profile = (new UserProfile)->userProfileWithAssociatedData($user_id);
        return view('service-hub.applicant_details', compact('expert_profile', 'user_id', 'applicant_info'))->render();
    }

}