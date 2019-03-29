<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceHubAssociatedExpert extends Model
{
    protected $table = 'service_hub_associated_experts';
    protected $fillable = ['user_id', 'service_hub_id', 'status', 'is_applicant'];

    public function serviceHub()
    {
        return $this->belongsTo('App\Model\ServiceHub', 'service_hub_id', 'id');
    }
    
    public function applicantDetail()
    {
        return $this->hasOne('App\Model\ServiceHubApplicant', 'service_hub_associated_expert_id', 'id');
    }
    
    public function updateData($conditions, $update_data)
    {
        return self::where($conditions)->update($update_data);
    }
    
    public function expertDetail()
    {
        return $this->belongsTo('App\Model\User', 'user_id', 'id')->where('admin_approval_status', 1);
    }

    public function findWithConditions($conditions, $type, $related_models = [])
    {
        $result = self::where($conditions);
        if(_count($related_models))
        {
            $result = $result->with($related_models);
        }
        return $result->$type();
    }
    
    public function approvedExpertListing($conditions)
    {
        return self::where($conditions)->with('expertDetail.user_profile')->paginate(config('constants.APPROVED_EXPERT_SERVICE_HUB_COUNT'));
    }
}
