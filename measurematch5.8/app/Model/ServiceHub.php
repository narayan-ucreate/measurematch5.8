<?php

namespace App\Model;

use App\Model\VendorInvitedExpert;
use Illuminate\Database\Eloquent\Model;
use App\Model\BaseModel;

class ServiceHub extends BaseModel
{
    protected $table = 'service_hubs';
    public $timestamps = true;
    protected $fillable = [
        'user_id', 'name', 'logo', 'sales_email', 'service_website', 'description', 'publish'
    ];

    public static $rules = [
        'user_id' => 'required',
        'name' => 'required',
        'logo' => 'required',
        'sales_email' => 'required|email',
        'service_website' => 'required',
        'description' => 'required',
        'publish' => 'required'
    ];

    protected static $messages = [
        'first_name' => 'Please provide First Name.',
        'last_name' => 'Please provide Last Name.',
        'email' => 'Please provide valid Email.',
        'booking_id' => 'Please provide Booking ID of event.',
        'status' => 'Please provide valid status value.'
    ];



    public function updateData($id, $data) {
        $this->whereId($id)->update($data);
    }

    public function serviceCategories() {
        return $this->hasMany('App\Model\ServiceHubCategory', 'service_hub_id', 'id');
    }

    public function vendor_profile() {
        return $this->hasOne(BuyerProfile::class, 'user_id', 'user_id');
    }



    public function myServiceHubStatus() {
        return $this->hasOne(ServiceHubAssociatedExpert::class, 'service_hub_id');
    }


    public function unapprovedApplicants() {
        return $this->hasMany(ServiceHubAssociatedExpert::class, 'service_hub_id')->where(['is_applicant' => true, 'status' => config('constants.PENDING')]);
    }
    
    public function declinedApplicants() {
        return $this->hasMany(ServiceHubAssociatedExpert::class, 'service_hub_id')->where(['is_applicant' => true, 'status' => config('constants.REJECTED')]);
    }

    public function approveExperts() {
        return $this->hasMany(ServiceHubAssociatedExpert::class, 'service_hub_id')->where(['service_hub_associated_experts.status' => config('constants.APPROVED')]);
    }

    public function myApplicationInfo() {
        return $this->hasOne(ServiceHubApplicant::class, 'service_hub_id');
    }

    public function vendor_user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function serviceExperts() {
        return $this->hasMany(VendorInvitedExpert::class, 'service_hub_id', 'id');
    }

    public function scopeStatusHub($scope, $status) {
        return $scope->whereStatus($status);
    }

    public function scopePublishHub($scope, $publish) {
        return $scope->wherePublish($publish);
    }

    public function getServiceHubInfo($user_id, $id = '') {
        return $this
            ->when(!$id, function($query) use ($user_id) {
                return $query->whereUserId($user_id);
            })
            ->when($id, function($query) use ($id, $user_id) {
                return $query
                    ->with(['vendor_profile', 'myServiceHubStatus' => function($query) use($user_id) {
                        $query->where('user_id', $user_id);
                    }, 'myApplicationInfo' => function($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    }, 'myApplicationInfo.rejectedDetails'])
                    ->whereId($id);
            })
            ->when($user_id, function($query) use ($user_id) {
                return $query->with(['serviceCategories']);
            })
            ->first();
    }
    
    public function serviceHubWithRelatedModels($condition, $related_models)
    {
        return self::where($condition)->with($related_models)->first();
    }

    public function getPendingHubs(){
        $query = $this->StatusHub(config('constants.SERVICE_HUB_STATUS.PENDING'))
            ->wherePublish(true)
            ->has('vendor_profile')
            ->with('vendor_profile')
            ->with('vendor_user')
            ->withCount('approveExperts');
        return $query;
    }
    
    public function getArchivedHubs(){
        $query = $this->StatusHub(config('constants.REJECTED'))
            ->with('vendor_profile')
            ->with('vendor_user')
            ->withCount('approveExperts');
        return $query;
    }

    public function getLiveHubs(){
        $query = $this->StatusHub(config('constants.SERVICE_HUB_STATUS.LIVE'))
            ->with('vendor_profile')
            ->with('vendor_user')
            ->withCount('approveExperts');
        return $query;
    }

    public function getHubWithVendorDetails($id){
        return $this
            ->with('vendor_profile')
            ->with('vendor_user')
            ->with('serviceCategories')
            ->with('serviceExperts')
            ->with(['unapprovedApplicants.applicantDetail.expertDetail' => function($query)
            {
                $query->select('id',
                    'name',
                    'last_name');
            }])
            ->with(['unapprovedApplicants.applicantDetail.expertDetail.user_profile' => function($query)
            {
                $query->select('user_id',
                    'expert_type',
                    'profile_picture',
                    'current_city',
                    'country');
            }])
            ->where('service_hubs.id', '=', $id)
            ->get()
            ->first();
    }

    public function pendingHubsCount(){
        return $this->StatusHub(config('constants.SERVICE_HUB_STATUS.PENDING'))->has('vendor_profile')->wherePublish(true)->count();
    }
    
    public function liveHubsCount(){
        return $this->StatusHub(config('constants.SERVICE_HUB_STATUS.LIVE'))->count();
    }

    public function archivedHubsCount(){
        return $this->StatusHub(config('constants.SERVICE_HUB_STATUS.ARCHIVED'))->count();
    }

    public function getServiceHubWithApprovedExpert($user_id)
    {
        return $this->whereUserId($user_id)
                ->with([
                    'serviceExperts',
                    'unapprovedApplicants.applicantDetail.expertDetail',
                    'approveExperts.expertDetail' => function($query) {
                        $query->select('id', 'name', 'last_name', 'email');
                    },
                    'approveExperts.expertDetail.user_profile' => function($query) {
                        $query->select('user_id',
                            'expert_type',
                            'profile_picture',
                            'current_city',
                            'country');
                    },
                    'unapprovedApplicants.applicantDetail.expertDetail' => function($query)
                    {
                        $query->select('id',
                            'name',
                            'last_name');
                    },
                    'unapprovedApplicants.applicantDetail.expertDetail.user_profile' => function($query)
                    {
                        $query->select('user_id',
                            'expert_type',
                            'profile_picture',
                            'current_city',
                            'country');
                    },
                    'declinedApplicants' => function($query){
                        $query->select('id', 'user_id', 'service_hub_id');
                    },
                    'declinedApplicants.expertDetail' => function($query){
                        $query->select('id',
                                'name',
                                'last_name');
                    },
                    'declinedApplicants.expertDetail.user_profile' => function($query){
                        $query->select('user_id',
                                'expert_type',
                                'profile_picture',
                                'current_city',
                                'country');
                    }
                ])
                ->withCount('unapprovedApplicants')
                ->withCount('declinedApplicants')
                ->withCount('myApplicationInfo')
                ->PublishHub(true)
                ->first();
    }

    public function getPublishServiceHub() {
        return $this->PublishHub(true)
            ->where('status', config('constants.APPROVED'))
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name', 'logo']);
    }
    
    public function serviceHubName($service_hub_id)
    {
        return self::select('name')->where('id', $service_hub_id)->first();
    }

    public function getVendorServiceHubAndAssociatedExperts($vendor_id, $expert_id) {
        return $this
            ->wherePublish(true)
            ->whereUserId($vendor_id)
            ->with(['approveExperts' => function($query) use ($expert_id) {
                $query->where('user_id', $expert_id);
            }])
            ->whereHas('approveExperts', function($query) use ($expert_id) {
                $query->where('user_id', $expert_id);
            })
            ->get();
    }
}
