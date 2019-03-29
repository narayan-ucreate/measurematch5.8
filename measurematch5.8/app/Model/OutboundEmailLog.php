<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OutboundEmailLog extends Model
{
    static function updateData($conditions, $data_to_update){
        return self::where($conditions)->update($data_to_update);
    }
    static function last24HourAndLessUnseenEmails($last_day_date) {
       $result = [];
       $query = self::whereDate('created_at', '=', $last_day_date)->where('is_seen', FALSE)->get();
       if(_count($query)){
           $result = $query->toArray();
       }
       return $result;
    }
    static function deleteRecord($conditions) {
        return self::where($conditions)->delete();
    }
    
    public function fetchNewProjectNotificationCount($expert_email, $project_id)
    {
        return OutboundEmailLog::where('user_email', $expert_email)
                ->where('post_job_id', $project_id)
                ->where('template_name', 'EXPERT_NEW_PROJECT_NOTIFICATION')
                ->count();
    }

}
