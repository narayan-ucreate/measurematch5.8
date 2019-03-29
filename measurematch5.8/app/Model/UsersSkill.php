<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
class UsersSkill extends Model {

    protected $table = 'users_skills';
    public $timestamps = true;
    protected $fillable = [
        'skill_id', 'user_id', 'created_at'
    ];

    /**
     * User Method
     *
     * @return type
     */
    public function user() {
        return $this->hasOne('App\Model\User', 'id', 'user_id');
    }

    /**
     * Skill Method
     *
     * @return type
     */
    public function skill() {
        return $this->belongsTo('App\Model\Skill','skill_id');
    }

    /**
     * User Profile Method
     *
     * @return type
     */
    public function user_profile() {
        return $this->hasOne('App\Model\UserProfile', 'user_id', 'user_id');
    }

    /**
     * User Employment Method
     *
     * @return type
     */
    public function user_employment() {
        return $this->hasmany('App\Model\EmploymentDetail', 'user_id', 'user_id');
    }

    public function active_expert() {
        return $this->hasOne('App\Model\User', 'id', 'user_id')->where([['hide_profile', 0], ['status', 1], ['admin_approval_status', 1], ['verified_status', 1], ['is_deleted', 0]]);
    }

    public static function getUsersWithMatchingSkills($skill_id){
        return UsersSkill::select('user_id')->with('user')->whereIn('skill_id', $skill_id)->groupBy('user_id')->get()->toArray();
    }
    public static function getUserSkillWithId($skill_id,$id){
        return UsersSkill::with(['user', 'user_profile'
            , 'user_employment'])->whereIn('skill_id', $skill_id)->where('user_id', '!=', $id)->get();
    }
    public static function getUserSkillswithDeletedUser($id){
        return UsersSkill::with(['user', 'user_profile'
            , 'user_employment'])
            ->has('user_profile')
            ->has('active_expert')
            ->has('user.user_skills')
            ->where('user_id', '!=', $id)
            ->whereIn('user_id',function($q)
            { $q->select('id')
                ->from(with(new User)->getTable())
                ->where('is_deleted',0)->where('status','!=',2);
            })->get();
    }
    public static function getUserSkills($skill_id,$userid){
        return UsersSkill::where('skill_id', $skill_id)->where('user_id', $userid);
    }
    public static function deleteSkills($user_id,$id){
        return UsersSkill::where('user_id', $user_id)->where('skill_id', $id)->delete();

    }
    public static function countUsersSkills($user_id){
        return UsersSkill::where('user_id', $user_id)->count();
    }
    public static function getUserSkillsWithSkillIdAndUserId($skill_id,$user_id){
        return UsersSkill::where('skill_id', $skill_id)->where('user_id', $user_id)->exists();
    }
    public static function findByCondition($conditions = [], $withs = [], $query_options = [], $type='') {
        if (is_array($conditions) && _count($conditions)) {
            $conitions_array = [];
            foreach ($conditions as $key => $val) {
                $conitions_array[$key] = $val;
            }
        }
        $query = UsersSkill::where($conitions_array);
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
    public static function getRandomUserSkills($limit){
        return  UsersSkill::inRandomOrder()
            ->select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->has('active_expert')
            ->has('user.user_skills')
            ->take($limit)
            ->with('active_expert.user_profile')
            ->get()
            ->toArray();
    }
    public static function getUserSKillsNotExists($users_to_exclude,$limit){
        return  UsersSkill::whereNotIn('user_id', $users_to_exclude)
            ->select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->inRandomOrder()
            ->has('active_expert')
            ->has('user.user_skills')
            ->take($limit)
            ->with('active_expert.user_profile')
            ->get()
            ->toArray();
    }
    public static function getRandomUserSkillWithUserId($skill_id,$limit, $users_to_exclude = array()){
        $query = UsersSkill::where('skill_id', $skill_id)->inRandomOrder();
        if(!empty($users_to_exclude)){$query= $query->whereNotIn('user_id', $users_to_exclude);}
        $query=$query->has('active_expert')
            ->has('user.user_skills')
            ->take($limit)
            ->with('active_expert.user_profile')
            ->get()
            ->toArray();
        return $query;
    }
    public static function getUserToolsByUserId($user_id, $type = null){
        $query= DB::table('users_skills')->join('skills', 'users_skills.skill_id', '=', 'skills.id')
            ->where('users_skills.user_id', $user_id)
            ->where('skills.is_tool', true);
        if(!empty($type)){
            $result = $query->count();
        }else{
            $result = $query->get();
        }

        return $result;
    }
    public static function getUserSkillsByUserId($user_id, $type = null){
        $query= DB::table('users_skills')->join('skills', 'users_skills.skill_id', '=', 'skills.id')
            ->where('users_skills.user_id', $user_id)
            ->where('skills.is_tool', false);
        if (!empty($type)) {
            $result = $query->count();
        } else {
            $result = $query->get();
        }
        return $result;
    }
    public static function deleteRecord($conditions){
        self::where($conditions)->delete();
    }

    public function getPopularSkills($exclude_skills = [], $limit, $offset, $include_skills = []) {
        $exclude_ids = implode(',', $exclude_skills);
        $include_skills = "'" . implode ( "', '", $include_skills ) . "'";

        return \DB::select(
            ' SELECT * FROM   ('.
            $this->select(
                'skill_id',
                \DB::raw('Count(users_skills.user_id) as total_users'),
                \DB::raw("Array_to_string(Array_agg(user_profiles.profile_picture), ',') as images"),
                'skills.name as skill_name',
                'logo_url'
            )
                ->join('skills', 'users_skills.skill_id', '=', 'skills.id')
                ->join('user_profiles', 'users_skills.user_id', 'user_profiles.user_id')
                ->when($exclude_ids !='', function($query) use ($exclude_ids) {
                    return $query->whereRaw('skills.id not in ('. $exclude_ids.')');
                })
                ->when($include_skills !='', function($query) use ($include_skills) {
                    return $query->whereRaw("skills.name  in (".$include_skills.")");
                })
                ->groupBy('skill_id', 'skill_name', 'logo_url')
                ->havingRaw('Count(users_skills.user_id) > '.config('constants.NO_OF_USER_DISPLAY_ON_RESULT'))
                ->orderBy('total_users', 'desc')
                ->limit($limit)
                ->offset($offset)
                ->toSql(). ') AS skills ORDER  BY Random(); '
        );
    }
}
