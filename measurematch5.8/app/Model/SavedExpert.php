<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SavedExpert extends Model {

    public function expert() {
        return $this->belongsTo('App\Model\User', 'expert_id');
    }

    public function post_job() {
        return $this->hasOne('App\Model\PostJob', 'id', 'post_job_id');
    }

    public static function getExperts($conditions, $count = 0, $withs = array(), $limit = 0, $offset = 0) {
         $expert_listing = FALSE;
        if ($count == 1) {
            return static::where($conditions)->count();
        } else {
            $experts = SavedExpert::where($conditions);
            if (!empty($experts)) {
                if (isset($withs) && !empty($withs)) {
                    foreach ($withs as $with) {
                        $experts->with($with);
                    }
                }
                if ($limit > 0) {
                    $experts->limit($limit)->offset($offset);
                }
                $expert_listing = $experts
                    ->get()
                    ->toArray();
            }
            return $expert_listing;
        }
    }

    public static function findByCondition($conditions, $fields = null, $type = [], $null_options = []) {
        if (_count($type) && $type['type'] == 'count') {
            $query = static::where($conditions);
            if(_count($null_options)){
                foreach ($null_options as $key => $value){
                    if($value == 'null'){
                        $query->whereNull($key);
                    }else{
                        $query->whereNotNull($key);
                    }
                }
            }
            $response = $query->count();
        } elseif ($type['type'] == 'list') {
            $response = static::where($conditions)->pluck($fields)->all();
        } else {
            $query = static::where($conditions);
            if(_count($null_options)){
                foreach ($null_options as $key => $value){
                    if($value == 'null'){
                        $query->whereNull($key);
                    }else{
                        $query->whereNotNull($key);
                    }
                }
            }
            $response = $query->get()->toArray();
        }
        return $response;
    }

    public static function getPostJobList($condition_array) {
        return static::select('post_job_id')
                ->where($condition_array)
                ->with('post_job')
                ->groupBy('post_job_id')
                ->get()
                ->toArray();
    }
    
    public static function getGloballlySavedExperts($buyer_id){
        return SavedExpert::where('buyer_id', $buyer_id)
                ->whereNull('post_job_id')
                ->with('expert.user_profile')
                ->orderBy('created_at','desc')
                ->paginate(config('constants.SAVED_EXPERT_PER_PAGE_LISTING_COUNT'));
    }
    
    public static function deleteSaved($conditions, $null_options = []){
        $query = self::where($conditions);
        if(_count($null_options)){
            foreach ($null_options as $key => $value){
                if($value == 'null'){
                    $query->whereNull($key);
                }else{
                    $query->whereNotNull($key);
                }
            }
        }
        if(!array_key_exists('post_job_id', $conditions)){
            $query->whereNotNull('post_job_id');
        }
        return $query->delete();
    }
}
