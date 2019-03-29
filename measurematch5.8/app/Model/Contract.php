<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model {
    
    use SoftDeletes;

    protected $table = 'contracts';
    protected $fillable = ['job_post_id','communications_id','status','job_start_date','job_end_date date','rate_variable','rate character','project_deliverables','upload_document','buyer_id','user_id','complete_status','buyer_feedback_status','expert_rating','feedback_comment','feedback_time','expert_complete_status','is_promotional_coupon_applied','payment_mode','service_package_id','type','subscription_type','monthly_days_commitment','monthly_billing_date'];

    public function post_jobs() {
        return $this->belongsTo('App\Model\PostJob', 'job_post_id', 'id');
    }

    /**
     * Buyer Method
     * 
     * @return type
     */
    public function buyer() {
        return $this->belongsTo('App\Model\BuyerProfile', 'buyer_id', 'user_id');
    }

    /**
     * Seller Method
     * 
     * @return type
     */
    public function seller() {
        return $this->belongsTo('App\Model\UserProfile', 'user_id', 'user_id');
    }

    /**
     * Payments
     * @return type
     */
    
    public function communication(){
        return $this->belongsTo('App\Model\Communication', 'communications_id', 'id');
    }
    public function payments() {
        return $this->belongsTo('App\Model\Payment', 'contract_id', 'id');
    }

    public function expert() {
        return $this->belongsTo('App\Model\User', 'user_id');
    }
    
    public function expertName() {
        return $this->belongsTo('App\Model\User', 'user_id')->select('name', 'last_name', 'id');
    }
    
    public function servicePackage() {
        return $this->belongsTo('App\Model\ServicePackage', 'service_package_id', 'id');
    }
    public function contractDeliverables() {
        return $this->hasMany('App\Model\Deliverable', 'contract_id', 'id')->where('type','contract');
    }
    public function deliverables() {
        return $this->hasMany('App\Model\Deliverable', 'contract_id', 'id')
            ->where('type','contract');
    }
    public function Terms() {
        return $this->hasMany('App\Model\ContractTerm', 'contract_id', 'id');
    }

    public static function updateContract($id, $buyer_feedback_status, $expert_rating, $feedback_comment, $feedback_time) {
        $response = Contract::where('id', $id)->update(['buyer_feedback_status' => $buyer_feedback_status, 'expert_rating' => $expert_rating, 'feedback_comment' => $feedback_comment, 'feedback_time' => $feedback_time]);
        return $response;
    }
    
    public function contractWithDeliverablesAndTerms($conditions, $expert_profile_picture = false)
    {
        $contract = Contract::where($conditions)
                    ->with(['deliverables' => function($query)
                        {
                            $query->select([
                                'id', 
                                'contract_id', 
                                'rate as price', 
                                'quantity', 
                                'title', 
                                'deliverable as description', 
                                'rate_unit as rate_type',
                                ]);
                        },
                        'Terms', 'expert.businessInformation.businessDetails'
                    ])->orderBy('id', 'asc');
        if($expert_profile_picture)
            $contract = $contract->with('expert.user_profile');
            
        $contract = $contract->first();
        if(_count($contract))
            $contract = $contract->toArray();
        return $contract;
    }

    public static function updateExpertCompleteStatus($contract_id) {
        return Contract::where('id', $contract_id)->update(['complete_status' => 1, 'expert_complete_status' => 1]);
    }

    public static function updateContractInformation($contract_id, $update) {
        return Contract::where('id', $contract_id)->update($update);
    }

    public static function updatePromotionalCouponcodeApplied($contract_id, $status) {
        return Contract::where('id', $contract_id)->update(['is_promotional_coupon_applied' => $status]);
    }

    public static function updateExpertContractStatus($contract_id) {
        return Contract::where('id', $contract_id)->update(['status' => 1]);
    }

    public static function getContractInformation($contract_id) {
        return Contract::where('id', $contract_id)->get()->toArray();
    }
    
    public static function getContractInformationInObject($contract_id) {
        return Contract::where('id', $contract_id)->get();
    }
    
    public static function updateExpertContractCompleteStatus($contract_id) {
        return Contract::where('id', $contract_id)->update(['expert_complete_status' => 1]);
    }

    public static function getBuyercontractsCount($buyer_id) {
        return Contract::where('buyer_id', $buyer_id)->where('status', '1')->count();
    }

    public static function getFirstPromotionalCouponApplied($buyer_id) {
        return Contract::where('buyer_id', $buyer_id)->where('is_promotional_coupon_applied', TRUE)->first();
    }

    public static function getContractStatus($project_id) {
        return Contract::where('type', 'project')->where('job_post_id', $project_id)->select('status')->get()->toArray();
    }

    public static function getAcceptedContractDetail($project_id) {
        return Contract::where([['job_post_id', $project_id], ['status', 1]]);
    }

    public static function getContractDetail($contract_id) {
        return Contract::where('id', $contract_id);
    }

    public static function getStatusOfContract($job_id, $user_id) {
        return Contract::where('job_post_id', $job_id)->where('buyer_id', $user_id)->pluck('status')->toArray();
    }

    public static function updateContractData($data, $contract_id) {
        return Contract::where('id', $contract_id)->update($data);
    }

    public static function findByCondition($conditions = [], $withs = [], $query_options = [], $type='') {
        if (is_array($conditions) && _count($conditions)) {
            $conitions_array = [];
            foreach ($conditions as $key => $val) {
                $conitions_array[$key] = $val;
            }
        }
        $query = Contract::where($conitions_array);
        if (isset($withs) && !empty($withs)) {
            foreach ($withs as $with) {
                $query = $query->with($with);
            }
        }
        if (isset($query_options['order_by'])) {
            $query->orderBy($query_options['order_by'][0], $query_options['order_by'][1]);
        }
        if($type=='first'){
        return $query->first();    
        }elseif($type=='count'){
        return $query->count();    
        }else{
        return $query->get();
        }
    }
    public static function getContractInformationWithStartDate($nextweekdate,$status,$type){
        return Contract::where('job_start_date', $nextweekdate)->where('status', $status)->where('type', $type);
    }
    public static function getCurrentlyOngoingContracts($job_start_date,$job_end_date,$type='project'){
        return Contract::where('job_start_date', '<=', $job_start_date)->where('job_end_date', '>=', $job_end_date)->where('status', config('constants.APPROVED'))->where('complete_status','!=', config('constants.APPROVED'))->where('type', $type)->get();
    }
    public static function getContractWithLastDate($next_week_date,$status){
        return Contract::where('job_end_date', $next_week_date)->where('status', $status);
    }
    public static function getFirstContract($contract_id){
        return Contract::where('id', $contract_id)->first();
    }
     public static function fetchContracts($conditions, $type='') {
        if($type=='count'){
            $result = self::where($conditions)->count();
        }elseif($type=='first'){
            $result = self::where($conditions)->first();
            if(_count($result)){
                $result = $result->toArray();
            }
        }else{
            $result = self::where($conditions)->orderBy('created_at', 'desc')
            ->with('buyer')        
            ->get();
            if(_count($result)){
                $result = $result->toArray();
            }
        }
       return $result;
    }
    public static function monthlyContractsNotFinishedToday($conditions){
        $result = self::where($conditions)
            ->whereDate('finished_on', '<', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->get();
            if(_count($result)){
                $result = $result->toArray();
            }
        return $result;
    }
    public static function getContractBroughtStatus($service_package_id,$status){
        return self::where('service_package_id',$service_package_id)->where('status',$status)->count();
    }
    public static function getFirstContractWithCommunication($communication_id){
        return self::where('communications_id',$communication_id)->orderBy('created_at', 'asc')->first();
    }
    public static function getParentContracts($conditions) {
      return Contract::where($conditions)->whereNull('parent_contract_id')->get();
    }
    public function getContractsAcceptedCount($coummnication_id) {
      return Contract::where(['communications_id'=>$coummnication_id,'status'=>config('constants.ACCEPTED')])->count();
    }
    public function getContractUniqueId($contract_id) {
      return Contract::where('id',$contract_id)->value('unique_id');
    }
    public static function getAllAcceptedContracts($project_id) {
        return Contract::where(['job_post_id'=> $project_id, 'status' => config('constants.ACCEPTED')])
               ->orderBy('id','DESC')->get()->toArray();
    }
    public function ContractCompletedOrInProgress($project_id) {
        return Contract::select('complete_status')  
               ->where('job_post_id',$project_id)     
               ->where('contracts.status', config('constants.ACCEPTED'))
               ->whereNull('contracts.deleted_at')
               ->where('is_extended', false)
               ->first();
    }
    public function getLatestContractDetails($communication_id) {
        return Contract::whereCommunicationsId($communication_id)
                        ->select('job_start_date','job_end_date','rate','rate_variable','status','subscription_type','parent_contract_id')
                        ->orderBy('created_at','DESC')->first();
    }

    public function getJobOrPackageContract($identifier, $type) {
        return $this
            ->when($type == config('constants.PROJECT'), function($query) use ($identifier) {
                return $query->whereJobPostId($identifier);
            })
            ->when($type == config('constants.SERVICE_PACKAGE'), function($query) use ($identifier) {
                return $query->whereServicePackageId($identifier);
            })->first(['id']);

    }
}
