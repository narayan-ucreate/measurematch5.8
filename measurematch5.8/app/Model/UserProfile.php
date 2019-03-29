<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model {

    /**
     * User Method
     *
     * @return type
     */
    public function user() {
        return $this->hasOne(User::class);
    }


    public function expertBasicInfo() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }



    /**
     * Remote Work
     *
     * @return type
     */
    public function remote_work() {
        return $this->belongsTo('App\Model\RemoteWork', 'remote_id');
    }
    public function activeExpertProfiles() {
        return $this->hasOne('App\Model\User', 'id', 'user_id')->where([['hide_profile', 0], ['status', 1], ['admin_approval_status', 1], ['verified_status', 1], ['is_deleted', 0]]);
    }
    public static function scopeUserId($query,$user_id) {
        return $query->where('user_id', $user_id);

    }

    public function userServicePackages() {
        return $this->hasMany(ServicePackage::class, 'user_id', 'user_id')->whereIsApproved(true)->whereIsHidden(false)->wherePublish(true)->select('name', 'description', 'id', 'user_id', 'price', 'subscription_type');
    }

    public static function updateEmailSentToAdminField($id) {
        return UserProfile::userId($id)->update(['completed_required_seven_experts_fields' => '1', 'expert_profile_review_email_to_admin_date' => date('Y-m-d G:i:s')]);
    }

    public static function updateProfilePicture($id, $fullurl) {
        return UserProfile::userId($id)->update(['profile_picture' => $fullurl]);
    }

    public static function getUserProfile($id) {
        return UserProfile::userId($id)->get()->toArray();
    }

    public static function Profile($id) {
        return UserProfile::userId($id)->first();
    }

    public function userSkills() {
        return $this->hasMany(UsersSkill::class, 'user_id', 'user_id');
    }

    public function expertRating() {
        return $this->hasMany(Contract::class, 'user_id', 'user_id')->select('user_id', 'expert_rating');
    }

    public function userProfileWithAssociatedData($id) {
        return $this
            ->with([
                'remote_work',
                'expertBasicInfo' => function ($query) {
                    $query->select('name', 'id', 'last_name','email');
                },
                'userSkills' => function ($query) {
                    $query
                        ->join('skills', 'skills.id', '=', 'users_skills.skill_id')
                        ->select('name', 'user_id', 'users_skills.id');
                },
                'userServicePackages.servicePackageTags.Tags' => function ($query) {
                    $query->select('name', 'id');
                }
            ])
            ->whereUserId($id)
            ->first();
    }

    public static function profileDetail($id){
        return UserProfile::userId($id)->first()->toArray();
    }
    public static function updateExpertFields($id){
        return UserProfile::whereIn('user_id', $id)->update(['completed_required_seven_experts_fields' => 1]);
    }
    public static function getNullPictureCount($id){
        return UserProfile::where('user_id', $id)->where('profile_picture', '!=', NULL)->count();
    }
    public static function searchLocationRemoteOption($location, $remote_option, $type){
        $result = FALSE;
        if(!empty($location)){
            $query = UserProfile::where(function($q) use ($location)
            {
                $q->where('current_city', 'iLike', '%'.$location.'%')
                    ->orWhere('postcode', 'iLike', '%'.$location.'%')
                    ->orWhere('first_address', 'iLike', '%'.$location.'%')
                    ->orWhere('second_address', 'iLike', '%'.$location.'%');
            });
            if($remote_option){
                $query->where('remote_id', $remote_option);
            }
        }elseif($remote_option){
            $query = UserProfile::where('remote_id', $remote_option);
        }
        if(isset($query)){
            if($type == 'lists'){
                $result = $query->pluck('user_id')->all();
            }else{
                $result = $query->$type();
            }
        }
        return $result;
    }

    public static function getRandomActiveExpertProfiles($except_ids,$limit){
        return UserProfile::inRandomOrder()->has('activeExpertProfiles')->whereNotIn('user_id',[$except_ids])->take($limit)->get();

    }
    public static function updateData($conditions, $data_to_update){
        return self::where($conditions)->update($data_to_update);
    }
    public function expertCountriesCount($expert_ids)
    {
        return self::select('country')
            ->where('country', '!=', '')
            ->whereIn('user_id', $expert_ids)
            ->groupBy('country')
            ->get()
            ->count();
    }
    public function profilePictures($expert_ids)
    {
        return UserProfile::whereIn('user_id', $expert_ids)->pluck('profile_picture', 'user_id')->toArray();
    }

    public static function deleteUserProfile($user_id) {
        return UserProfile::where('user_id', '=', $user_id)->delete();
    }
}
