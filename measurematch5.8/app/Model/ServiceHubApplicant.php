<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceHubApplicant extends Model
{
    protected $table = 'service_hub_applicants';
    protected $fillable = ['user_id',
        'service_hub_id',
        'total_experience',
        'recent_case_study',
        'service_hub_associated_expert_id'];

    public function serviceHub()
    {
        return $this->belongsTo('App\Model\ServiceHub');
    }
    
    public function serviceHubAssociatedExpert()
    {
        return $this->belongsTo('App\Model\ServiceHubAssociatedExpert', 'user_id', 'user_id');
    }

    public function isApplicantExist($service_hub_id, $user_id)
    {
        return $this->whereServiceHubId($service_hub_id)->whereUserId($user_id)->first();
    }
    
    public function expertDetail()
    {
        return $this->hasOne('App\Model\User', 'id', 'user_id');
    }

    public function rejectedDetails() {
        return $this->hasOne(ServiceHubRejectedApplicantDetail::class, 'service_hub_applicant_id');
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
    
    public function udpateData($conditions, $data)
    {
        return self::where($conditions)->update($data);
    }
}