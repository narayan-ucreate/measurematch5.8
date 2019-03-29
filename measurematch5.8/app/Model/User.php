<?php

namespace App\Model;

use DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
     use SoftDeletes;
     use Uuids;
     use Notifiable;

    protected $keyType = 'string';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $incrementing = false;
    protected $fillable = [
      'name', 'email', 'password',
    ];
    
    protected $appends = [
       'full_name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      'password', 'remember_token',
    ];

    /**
     * Filter User Method
     *
     * @return type
     */
    public function filter_user() {
        return $this->hasOne('App\Model\User', 'id');
    }

    public function businessInformation() {
        return $this->hasOne('App\Model\BusinessInformation', 'user_id');
    }

    /**
     * User Profile Method
     *
     * @return type
     */
    public function user_profile() {
        return $this->hasOne('App\Model\UserProfile', 'user_id');
    }
    
    public function expertCommunication() {
        return $this->hasmany('App\Model\Communication', 'user_id');
    }
    
    public function servicePackage() {
        return $this->hasmany('App\Model\ServicePackage', 'user_id');
    }

    public function buyerCommunication() {
        return $this->hasmany('App\Model\Communication', 'buyer_id');
    }

    public function user_profile_summary() {
        return $this->hasOne('App\Model\UserProfile', 'user_id')->where('summary', '!=', '')->where('hide_profile', 0);
    }

    public function user_profile_picture() {
        return $this->hasOne('App\Model\UserProfile', 'user_id')->where('profile_picture', '!=', '')->select(['user_id', 'profile_picture']);
    }

    public function completeMandatoryFields() {
        return $this->hasOne('App\Model\UserProfile', 'user_id')->whereRaw('completed_required_seven_experts_fields=1');
    }

    public function incompleteMandatoryFields() {
        return $this->hasOne('App\Model\UserProfile', 'user_id')->where('completed_required_seven_experts_fields', 0);
    }

    /**
     * Buyer Profile Method
     *
     * @return type
     */
    public function buyer_profile() {

        return $this->hasOne('App\Model\BuyerProfile', 'user_id');
    }

    /**
     * Post Jobs Method
     *
     * @return type
     */
    public function post_jobs() {

        return $this->hasmany('App\Model\PostJob', 'user_id');
    }

    /**
     * User Skills Method
     *
     * @return type
     */
    public function user_skills() {

        return $this->hasmany('App\Model\UsersSkill', 'user_id');
    }

    /**
     * User Employment Detail Method
     *
     * @return type
     */
    public function user_employment_detail() {

        return $this->hasmany('App\Model\EmploymentDetail', 'user_id')->orderBy('end_date', 'DESC');
    }

    /**
     * User Education Detail Method
     *
     * @return type
     */
    public function user_education_detail() {

        return $this->hasmany('App\Model\EducationDetail', 'user_id')->orderBy('start_date', 'DESC');
    }

    /**
     * User Certification Method
     *
     * @return type
     */
    public function user_certification() {

        return $this->hasmany('App\Model\UsersCertification', 'user_id')->orderBy('start_date', 'DESC');
    }

    /**
     * User Languages Method
     *
     * @return type
     */
    public function user_languages() {

        return $this->hasmany('App\Model\UsersLanguage', 'user_id')->orderBy('created_at', 'DESC');
    }

    /**
     * Users Category Method
     *
     * @return type
     */
    public function UsersCategory() {

        return $this->hasmany('App\Model\UsersCategory', 'user_id');
    }

    /**
     * Users Communication Method
     *
     * @return type
     */
    public function UsersCommunication() {

        return $this->hasmany('App\Model\UsersCommunication', 'user_id');
    }

    /**
     * Communication Method
     *
     * @return type
     */
    public function communication() {

        return $this->hasmany('App\Model\Communication', 'job_post_id', 'job_post_id');
    }

    /**
     * Contracts Method
     *
     * @return type
     */
    public function contracts() {
        return $this->hasmany('App\Model\Contract', 'user_id')->orderBy('job_start_date', 'DESC');
    }

    public function contract_feedbacks() {
        return $this->hasmany('App\Model\Contract', 'user_id')->where('buyer_feedback_status', 1)->orderBy('feedback_time', 'DESC');
    }

    public function userServiceHubs() {
        return $this->hasmany('App\Model\ServiceHubAssociatedExpert', 'user_id')->where('status', 1);
    }



    /**
     * Messages Method
     *
     * @return type
     */
    public function messages() {
        return $this->hasmany('App\Model\Message', 'sender_id')->orderBy('messages.created_at', 'DESC');
    }
    
    public function serviceHubAssociatedExpert()
    {
        return $this->hasMany('App\Model\ServiceHubAssociatedExpert', 'user_id', 'id');
    }

    public static function findByCondition($conditions = [], $withs = [], $query_options = [], $has_array = []) {

        if (_count($conditions)) {
            $conitions_array = [];
            foreach ($conditions as $key => $val) {
                $conitions_array[$key] = $val;
            }
        }

        $query = User::where($conitions_array);
        if (isset($withs) && !empty($withs)) {
            foreach ($withs as $with) {
                $query = $query->with($with);
            }
        }

        if (_count($has_array)) {
            foreach ($has_array as $key => $has_method) {
                $query = $query->has($has_method);
            }
        }

        if (isset($query_options['with_trashed'])) {
            $query->withTrashed();
        }
        if (isset($query_options['order_by'])) {
            $query->orderBy($query_options['order_by'][0], $query_options['order_by'][1]);
        }
        if (isset($query_options['page_size'])) {
            $result = $query->paginate($query_options['page_size']);
        } else {
            if (array_key_exists('type', $query_options)) {
                if ($query_options['type'] == 'count') {
                    $result = $query->count();
                }else {
                $result = $query->get();
                }
            } else {
                $result = $query->get();
            }
        }
        
        return $result;
    }

    public static function userFirstDetailWithFullProfile($id, $is_vendor = false) {
        return User::where('id', $id)
            ->with('user_profile.remote_work')
            ->with('user_skills.skill')
            ->with('user_languages.language')
            ->with('user_certification')
            ->with('user_employment_detail')
            ->with('user_education_detail')
            ->with('contract_feedbacks')
            ->when($is_vendor, function($query) {
                $query->with('userServiceHubs')
                    ->with(['userServiceHubs.serviceHub']);
            })
            ->first();
    }


    public static function userDetailWithFullProfile($id, $info_section, $ajax = false) {
        return User::where('id', $id)
            ->when(!$ajax || $info_section == 'profile-summary', function($query) {
                return $query
                    ->with('user_profile.remote_work')
                    ->with('user_skills.skill');
            })
            ->when($info_section == 'profile-summary', function($query) {
                return $query->with([
                    'contract_feedbacks' => function($query) {
                        $query->select(
                            'id',
                            'user_id',
                            'expert_rating',
                            'feedback_comment',
                            'feedback_time',
                            'buyer_feedback_status',
                            'type',
                            'service_package_id',
                            'buyer_id',
                            'job_post_id'
                        );
                    }, 'contract_feedbacks.buyer' => function($query) {
                        $query->select('user_id', 'office_location', 'first_name', 'company_name');
                    }, 'contract_feedbacks.servicePackage' => function($query) {
                        $query->select('id', 'name');
                    }, 'contract_feedbacks.post_jobs' => function($query) {
                        $query->select('id', 'job_title');
                    }, 'userServiceHubs.serviceHub']);
            })
            ->when($info_section == 'profile-skills', function($query) {
                return $query->with('user_skills.skill')->with('user_languages.language');
            })
            ->when($info_section == 'work-history', function($query) {
                return $query->with('user_employment_detail');
            })
            ->when($info_section == 'profile-education', function($query) {
                return $query->with('user_education_detail', 'user_certification');
            })
            ->first()->toArray();
    }

    public static function getSellerDetails($user_id, $admin_approval_status = 1){
        return User::where('id', $user_id)
            ->where('admin_approval_status', $admin_approval_status)
                ->has('user_skills')
                ->with('user_profile.remote_work')
                ->with('user_skills.skill')
                ->with('user_languages.language')
                ->with('user_certification')
                ->with('user_employment_detail')
                ->with('user_education_detail')
                ->with('contract_feedbacks')
                ->get()->toArray();
    }
    public static function getUserInformation($email) {
        return User::where('email', $email)->where('user_type_id', '1')->get()->toArray();
    }
    public static function getUserInformationWithEmail($email){
        return User::where('email', $email)->get()->toArray();
    }
    public static function getUserByEmail($email){
        return User:: where('email', '=', trim(strtolower($email)))->first();
    }

    public static function getUserById($id) {
        return User::where('id', $id)->first();
    }
    
    public static function getUserWithId($id){
        User::where('id', $id)->get()->toArray();
    }

    public static function getUserType($id) {
        return User::find($id, ['user_type_id'])->user_type_id;
    }

    public static function getUserInformationInBuyerSearch($user_id) {
        return User::where('id', $user_id)->with('user_profile')
            ->with('user_skills.skill')->with('user_languages.language')->with('user_certification')
            ->with('user_employment_detail')->with('user_education_detail')->first();
    }

    public static function getExpertInformation($id) {
        return User::leftJoin('employment_details', 'employment_details.user_id', '=', 'users.id')->leftJoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')->where('users.id', $id)->orderBy('employment_details.created_at', 'desc')->get();
    }

    public static function getBuyerInformation($id) {
        return User::leftJoin('buyer_profile', 'buyer_profile.user_id', '=', 'users.id')->where('users.id', $id)->orderBy('buyer_profile.created_at', 'desc')->get();
    }

    public static function getUserInformationWithId($id) {
        return User::where('users.id', $id)->get();
    }

    public static function getPendingBuyers() {
        return User::join('buyer_profile as b', 'b.user_id', '=', 'users.id')->select('*', 'users.id as id')->where('users.status', config('constants.APPROVED'))->where('users.user_type_id',  config('constants.BUYER'))->where('users.admin_approval_status',  config('constants.PENDING'));
    }

    public function getPendingVendors() {
        return User::join('buyer_profile as b', 'b.user_id', '=', 'users.id')
            ->select('*', 'users.id as id')
            ->where('users.status', config('constants.APPROVED'))
            ->where('users.user_type_id',  config('constants.VENDOR'))
            ->where('users.admin_approval_status',  config('constants.PENDING'));
    }

    public static function getBuyer() {
        return User::join('buyer_profile as b', 'b.user_id', '=', 'users.id')->select('*', 'users.id as id')->where('users.status', config('constants.APPROVED'))->where('users.user_type_id', config('constants.BUYER'))->where('users.admin_approval_status',  config('constants.APPROVED'));
    }

    public function getVendors() {
        return User::join('buyer_profile as b', 'b.user_id', '=', 'users.id')
            ->select('*', 'users.id as id')
            ->where('users.status', config('constants.APPROVED'))
            ->where('users.user_type_id', config('constants.VENDOR'))
            ->where('users.admin_approval_status',  config('constants.APPROVED'));
    }

    public static function getUnverifiedBuyers() {
        return User::join('buyer_profile as b', 'b.user_id', '=', 'users.id')->select('*', 'users.id as id')->where('users.status', config('constants.PENDING'))->where('users.user_type_id', config('constants.BUYER'))->where('users.is_deleted', config('constants.PENDING'));
    }

    public function getUnverifiedVendors() {
        return User::join('buyer_profile as b', 'b.user_id', '=', 'users.id')
            ->select('*', 'users.id as id')
            ->where('users.status', config('constants.PENDING'))
            ->where('users.user_type_id', config('constants.VENDOR'))
            ->where('users.is_deleted', config('constants.PENDING'));
    }
    
    public function getFullNameAttribute()
    {
        return ucwords("{$this->name} {$this->last_name}");
    }

    public static function getUserProfileWithSkills($field_value = null, $order_by = null) {
        $query = User::with(['user_profile' => function($query) {
                 return $query->select('currency', 'user_id', 'expert_type', 'describe', 'daily_rate', 'current_city', 'country', 'summary', 'remote_id');
            }, 'user_skills'])
            ->select(
                'users.name as name',
                'last_name',
                'email',
                'phone_num',
                'mm_unique_num',
                'users.created_at',
                'last_name',
                'vat_country_code',
                'vat_number',
                'users.id as id'
                );
            if(!empty($field_value) && !empty($order_by)){
                $query->orderBy($field_value, $order_by);
            }
            $query->where('users.status', config('constants.APPROVED'))
            ->where('users.user_type_id', config('constants.EXPERT'))
            ->where('users.admin_approval_status', config('constants.APPROVED'))
            ->where('users.is_deleted', config('constants.NOT'))
            ->has('user_profile')
            ->with(['serviceHubAssociatedExpert' => function($query){
                    return $query->select('user_id');
            }])
            ->with(['serviceHubAssociatedExpert.serviceHub' => function($query){
                return $query->select('id', 'name');
            }]);
        return $query;
    }

    public static function getUserProfileWithIncompleteData() {
        return User::join('user_profiles as up', 'up.user_id', '=', 'users.id')
            ->select('*', 'users.id as id')
            ->where('admin_approval_status', config('constants.PENDING'))
            ->where('users.status', config('constants.APPROVED'))
            ->where('users.user_type_id', config('constants.EXPERT'))
            ->has('incompleteMandatoryFields')
            ->with(['serviceHubAssociatedExpert' => function($query){
                    return $query->select('user_id');
            }])
            ->with(['serviceHubAssociatedExpert.serviceHub' => function($query){
                return $query->select('id', 'name');
            }]);
    }

    public static function getSideHustlersExperts(){
        return User::where('admin_approval_status', config('constants.PENDING'))
            ->where('users.status', config('constants.SIDE_HUSTLER'))
            ->where('users.user_type_id', config('constants.EXPERT'));
    }

    public static function getSideHustler($id){
        return User::where('id', $id);
    }

    public static function getExpertWithIncompleteData() {
        return User::select('*', 'users.id as id')
            ->with(['serviceHubAssociatedExpert' => function($query){
                return $query->select('user_id');
            }])
            ->with(['serviceHubAssociatedExpert.serviceHub' => function($query){
                return $query->select('id', 'name');
            }])
            ->where('admin_approval_status', config('constants.PENDING'))->where('users.status', '1')->where('users.user_type_id', config('constants.EXPERT'))->has('incompleteMandatoryFields')->with('user_profile','user_skills.skill')->with('user_profile.remote_work');
    }

    public static function getNonVerifiedExperts() {
        return User::join('user_profiles as up', 'up.user_id', '=', 'users.id')
                ->select('*', 'users.id as id')
                ->where('users.status', config('constants.PENDING'))
                ->where('users.user_type_id', config('constants.EXPERT'))
                ->where('users.admin_approval_status', config('constants.PENDING'))
                ->where('users.is_deleted', config('constants.PENDING'))
                ->with('user_profile')
                ->with('user_skills')
                ->with(['serviceHubAssociatedExpert' => function($query){
                    return $query->select('user_id');
                }])
                ->with(['serviceHubAssociatedExpert.serviceHub' => function($query){
                    return $query->select('id', 'name');
                }]);
    }

    public static function getBlockedBuyers()
    {
        return User::select('*', 'users.id as id')
                ->where('users.status', config('constants.REJECTED'))
                ->where('users.user_type_id', config('constants.BUYER'));
    }

    public function getArchivedVendors()
    {
        return User::select('*', 'users.id as id')
                ->where('users.status', config('constants.REJECTED'))
                ->where('users.user_type_id', config('constants.VENDOR'));
    }

    public static function getBlockedExperts()
    {
        return User::select('*', 'users.id as id')
                ->where('users.status', config('constants.REJECTED'))
                ->where('users.user_type_id', config('constants.EXPERT'))
                ->with(['serviceHubAssociatedExpert' => function($query){
                    return $query->select('user_id');
                }])
                ->with(['serviceHubAssociatedExpert.serviceHub' => function($query){
                    return $query->select('id', 'name');
                }]);
    }

    public static function getBlockedUser() {
        return User::select('*', 'users.id as id')->where('users.status', '2');
    }

    public static function updateUserStatusToBlocked($id) {
        return User::where('id', $id)
            ->update(['status' => '2', 'is_deleted' => '1']);
    }

    public static function updateUserApprovalStatus($user_id, $verified_status, $status) {
        return User::where('id', $user_id)
            ->update(['status' => $verified_status, 'is_deleted' => '0', 'admin_approval_status' => $status]);
    }

    public static function userFullProfileByUserId($id) {
        return User::where('id', $id)
            ->with('user_profile.remote_work')
            ->with('user_skills.skill')->with('user_certification')
            ->with('user_employment_detail')->with('user_education_detail')
            ->first()->toArray();
    }

    public static function getBuyerProfileWithSummary() {
        return User::where(array('user_type_id' => config('constants.EXPERT'), 'hide_profile' => '0', 'status' => '1', 'admin_approval_status' => '1'))
            ->has('user_skills')
            ->whereHas('user_profile', function($summ) {
                $summ->WhereNotNull('summary');
            })
            ->with('user_profile.remote_work')
            ->with('user_skills.skill')
            ->with('user_languages.language')
            ->with('user_certification')
            ->with('user_employment_detail')
            ->with('user_education_detail')
            ->get()->toArray();
    }

    public static function getUserWithSkills() {
        return User::select('id')->has('user_profile')->with('user_profile.remote_work')->with('user_skills.skill')->get()->toArray();
    }

    public static function searchByJobPosition($job_title, $per_page) {
        return User::where('user_type_id', config('constants.BUYER'))
            ->with('user_profile.remote_work')
            ->with('user_skills.skill')
            ->with('user_languages.language')
            ->with('user_certification')
            ->whereHas('user_employment_detail', function ($query) use($job_title) {
                $query->where('position_title', 'like', '%' . $job_title . '%');
            })->with('user_education_detail')->take($per_page)->get()->toArray();
    }
    public static function getExpertWithIncompleteProfileView($id){
        return User::findByCondition(['user_type_id' => config('constants.EXPERT'),
                'users.id' => $id
                ], ['user_profile', 'user_profile.remote_work', 'user_skills'], [], ['incompleteMandatoryFields']);
    }
    public static function getNotVerifiedExpertView($id){
        return User::findByCondition(['user_type_id' => config('constants.EXPERT'),
                'users.admin_approval_status' => config('constants.PENDING'),
                'users.status' => config('constants.PENDING'),
                'users.id' => $id
                ], ['user_profile', 'user_profile.remote_work', 'user_skills']);
    }
    public static function updateUser($id,$data_to_update_array){
        return User::where('id', $id)->update($data_to_update_array);
    }
    public static function getExpertProfileInformation($order = null,$order_by = null, $query_options = []){
        $query = User::join('user_profiles as up', 'up.user_id', '=', 'users.id')
                ->select('*', 'users.id as id')
                ->where('users.status', config('constants.APPROVED'))
                ->where('users.user_type_id', config('constants.EXPERT'))
                ->where('users.admin_approval_status', config('constants.PENDING'))
                ->has('completeMandatoryFields')
                ->with('user_profile')
                ->with('user_skills')
                ->with(['serviceHubAssociatedExpert' => function($query){
                    return $query->select('user_id');
                }])
                ->with(['serviceHubAssociatedExpert.serviceHub' => function($query){
                    return $query->select('id', 'name');
                }]);
            if (isset($order['data-sort']) && !empty($order['data-sort'])) {
                $sorting_field_in_array = explode(',', $order['data-sort']);
                foreach ($sorting_field_in_array as $field_value) {
                    $query = $query->orderBy($field_value, $order_by);
                }
            } else {
                $query->orderBy('users.created_at', $order_by);
            }
            if(_count($query_options) && array_key_exists('paginate', $query_options)){
                $result = $query->paginate(25);
            }else{
                $result = $query->get();
            }
           return $result;
    }
    public static function getBuyerActivationLink(){
        return User::whereIn('user_type_id', [1, 2])->where('admin_approval_status', '=', 1)->where('is_deleted', '!=', 1)->where('status', 0)->where('pending_activation_email_sent', FALSE);
    }
    
    public function scopeUserBasicConditions($query, $priority, $skill_name = '')
    {
        $fields_to_select = "users.id,
                users.created_at,
                users.deleted_at, 
                $priority as priority";

        if (!empty($skill_name))
            $fields_to_select = "users.id,
                users.deleted_at,
                $priority as priority, "
                . "'$skill_name' as skill_name";

        return $query->selectRaw($fields_to_select)
                ->where('users.hide_profile', config('constants.NOT'))
                ->where('users.status', config('constants.APPROVED'))
                ->where('users.admin_approval_status', config('constants.APPROVED'))
                ->where('users.verified_status', config('constants.APPROVED'))
                ->where('users.is_deleted', config('constants.NOT'));
    }

    public static function firstLastNameSearch($keywords, $priority){
        $query = Static::userBasicConditions($priority)
        ->where(function($q) use ($keywords){
            $count = 0;
            foreach($keywords as $string){
                $count++;
                if($count==1){
                    $q->where('name', 'iLike', '%'.$string.'%')
                    ->orWhere('last_name', 'iLike', '%'.$string.'%');
                }else{
                    $q->orWhere('name', 'iLike', '%'.$string.'%')
                    ->orWhere('last_name', 'iLike', '%'.$string.'%');
                }
            }
        });
        return $query;
    }
    
    public static function userProfileSearch($keywords, $priority, $skill_name = ''){
        $query = Static::userBasicConditions($priority, $skill_name)
        ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
        ->where(function($q) use ($keywords){
            $count = 0;
            foreach($keywords as $string){
                $count++;
                if($count==1){
                    $q->where('user_profiles.summary', 'iLike', '%'.$string.'%')
                    ->orWhere('user_profiles.describe', 'iLike', '%'.$string.'%')
                    ->orWhere('user_profiles.description', 'iLike', '%'.$string.'%');
                }else{
                    $q->orWhere('user_profiles.summary', 'iLike', '%'.$string.'%')
                    ->orWhere('user_profiles.describe', 'iLike', '%'.$string.'%')
                    ->orWhere('user_profiles.description', 'iLike', '%'.$string.'%');
                }
            }
        });
        
        return $query;
    }
    
    public static function userSkillsSearch($keywords, $priority, $skill_name = ''){
        $query = Static::userBasicConditions($priority, $skill_name)
        ->leftJoin('users_skills', 'users.id', '=', 'users_skills.user_id')
        ->leftJoin('skills', 'skills.id', '=', 'users_skills.skill_id');

        if (_count($keywords) > 1) {
            $query->where(function($q) use ($keywords){
                $count = 0;
                foreach ($keywords as $string) {
                    $count++;
                    if($count==1){
                        $q->where('skills.name', 'iLike', '%'.$string.'%');
                    }else{
                        $q->orWhere('skills.name', 'iLike', '%'.$string.'%');
                    }
                }
            });
        } else {
            foreach ($keywords as $string) {
                $query->where('skills.name', 'iLike', '%'.$string.'%');
            }
        }
        return $query;    
    }
    
    public static function userEducationSearch($keywords, $priority, $skill_name = ''){
        $query = Static::userBasicConditions($priority, $skill_name)
        ->leftJoin('education_details', 'users.id', '=', 'education_details.user_id')
        ->where(function($q) use ($keywords){
            $count = 0;
            foreach($keywords as $string){
                $count++;
                if($count==1){
                    $q->where('education_details.name', 'iLike', '%'.$string.'%')
                    ->orWhere('education_details.field_of_study', 'iLike', '%'.$string.'%');
                }else{
                    $q->orWhere('education_details.name', 'iLike', '%'.$string.'%')
                    ->orWhere('education_details.field_of_study', 'iLike', '%'.$string.'%');
                }
            }
        });
        return $query;
    }
    
    public static function userCertificateSearch($keywords, $priority, $skill_name = ''){
        $query = Static::userBasicConditions($priority, $skill_name)
        ->leftJoin('users_certifications', 'users.id', '=', 'users_certifications.user_id');
        
        if (_count($keywords) > 1) {
            $query->where(function($q) use ($keywords){
                $count = 0;
                foreach ($keywords as $string) {
                    $count++;
                    if($count==1){
                        $q->where('users_certifications.name', 'iLike', '%'.$string.'%');
                    }else{
                        $q->orWhere('users_certifications.name', 'iLike', '%'.$string.'%');
                    }
                }
            });
        } else {
            foreach ($keywords as $string) {
                $query->where('users_certifications.name', 'iLike', '%'.$string.'%');
            }
        }
        return $query;
    }
    
    public static function userEmploymentSearch($keywords, $priority, $skill_name = ''){
        $query = Static::userBasicConditions($priority, $skill_name)
        ->leftJoin('employment_details', 'users.id', '=', 'employment_details.user_id')
        ->where(function($q) use ($keywords){
            $count = 0;
            foreach($keywords as $string){
                $count++;
                if($count==1){
                    $q->where('employment_details.position_title', 'iLike', '%'.$string.'%')
                    ->orWhere('employment_details.summary', 'iLike', '%'.$string.'%');
                }else{
                    $q->orWhere('employment_details.position_title', 'iLike', '%'.$string.'%')
                    ->orWhere('employment_details.summary', 'iLike', '%'.$string.'%');
                }
            }
        });
        return $query;
    }
    
    public static function expertSearchBasisOfExpressionOfInterest($keywords, $priority, $skill_name = '', $skill_id = '')
    {
        $query = Static::userBasicConditions($priority, $skill_name)
        ->leftJoin('contracts', 'contracts.user_id', '=', 'users.id')            
        ->leftJoin('communications', 'communications.user_id', '=', 'users.id')
        ->leftJoin('post_jobs', 'communications.job_post_id', '=', 'post_jobs.id')
        ->leftJoin('jobs_skills', 'jobs_skills.job_post_id', '=', 'jobs_skills.job_post_id')
        ->leftJoin('deliverables', function($join)
            {
                $join->on('deliverables.contract_id', '=', 'contracts.id');
                $join->on('deliverables.post_job_id', '=', 'post_jobs.id');
            })
        ->where('contracts.project_deliverables', 'iLike', '%'.$skill_name.'%');
        if(!empty($skill_id))
        {
            $query->orWhere('jobs_skills.skill_id', (int)$skill_id);
        }
        $query->orWhere(function($q) use ($keywords){
            $count = 0;
            
            foreach($keywords as $string){
                $count++;
                if($count==1){
                    $q->where('contracts.project_deliverables', 'iLike', '%'.$string.'%')
                    ->orWhere('deliverables.deliverable', 'iLike', '%'.$string.'%');
                }else{
                    $q->orWhere('contracts.project_deliverables', 'iLike', '%'.$string.'%')
                    ->orWhere('deliverables.deliverable', 'iLike', '%'.$string.'%');
                }
            }
        });
        return $query;
    }
    
    public static function expertSearchBasisOfSkillsInProfile($priority, $skill_name = '', $skill_id = '')
    {
        $query = Static::userBasicConditions($priority, $skill_name)            
        ->leftJoin('users_skills', 'users_skills.user_id', '=', 'users.id')
        ->where('skill_id', $skill_id);
        return $query;
    }
    
    public static function expertSearchBasisOfTags($priority, $skill_name = '', $tag_id = '')
    {
        $query = Static::userBasicConditions($priority, $skill_name)  
        ->leftJoin('service_packages', 'service_packages.user_id', '=', 'users.id')
        ->leftJoin('service_package_tags', 'service_package_tags.service_package_id', '=', 'service_packages.id')
        ->where('service_package_tags.tag_id', $tag_id);
        return $query;
    }
    
    public static function sortExpertResult($query, $location_array, $remote_option){
        if(isset($query) && !empty($query)){
            $count_result = static::selectRaw('count(distinct(users.id)) as total')
                ->from(\DB::raw(' ( ' . $query->toSql() . ' ) AS users '))
                ->mergeBindings($query->getQuery())
                ->has('completeMandatoryFields');
            
            $result = static::selectRaw('users.id, users.created_at, sum(priority) as sum')
                ->from(\DB::raw(' ( ' . $query->toSql() . ' ) AS users '))
                ->mergeBindings($query->getQuery())
                ->has('completeMandatoryFields')
                ->with('completeMandatoryFields');
        }else{
            $count_result = static::selectRaw('count(users.id) as total')
                ->where('users.hide_profile', config('constants.NOT'))
                ->where('users.status', config('constants.APPROVED'))
                ->where('users.admin_approval_status', config('constants.APPROVED'))
                ->where('users.verified_status', config('constants.APPROVED'))
                ->where('users.is_deleted', config('constants.NOT'))
                ->has('completeMandatoryFields');
            
            $result = static::selectRaw('users.id, 1 as sum')
                ->where('users.hide_profile', config('constants.NOT'))
                ->where('users.status', config('constants.APPROVED'))
                ->where('users.admin_approval_status', config('constants.APPROVED'))
                ->where('users.verified_status', config('constants.APPROVED'))
                ->where('users.is_deleted', config('constants.NOT'))
                ->has('completeMandatoryFields')
                ->with('completeMandatoryFields');
        }
       
        if(isset($location_array) && _count($location_array)){
            self::searchWithExpertLocation($count_result, $location_array);
            self::searchWithExpertLocation($result, $location_array);
        }
        
        if(isset($remote_option) && !empty($remote_option)){
            self::searchWithRemoteOptions($count_result, $location_array, $remote_option);
            self::searchWithRemoteOptions($result, $location_array, $remote_option);
        }
        if(isset($query) && !empty($query)){
            $result->orderBy('sum','desc')->orderBy('users.created_at','desc')->groupBy('users.id', 'users.created_at');
        }else{
            $result->orderBy('users.created_at','desc')->groupBy('users.id');
        }
        
        return ['success' => 1, 'count' => $count_result->first()->toArray(),
                    'data' => $result];
    }
    
    private static function searchWithExpertLocation($result, $location_array){
        return $result->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->where(function($q) use ($location_array){
            $count = 0;
            foreach($location_array as $string){
                $count++;
                if($count === 1){
                $query = 'user_profiles.current_city iLike \'%'.$string.'%\' '
                        . 'or user_profiles.postcode iLike \'%'.$string.'%\' '
                        . 'or user_profiles.first_address iLike \'%'.$string.'%\' '
                        . 'or user_profiles.second_address iLike \'%'.$string.'%\'';
                }else{
                    $query.= ' or user_profiles.current_city iLike \'%'.$string.'%\' '
                        . 'or user_profiles.postcode iLike \'%'.$string.'%\' '
                        . 'or user_profiles.first_address iLike \'%'.$string.'%\' '
                        . 'or user_profiles.second_address iLike \'%'.$string.'%\'';
                }
            }
            $q->whereRaw($query);
        });
    }
    
    private static function searchWithRemoteOptions($result, $location_array, $remote_option){
        if(isset($location_array) && _count($location_array)){
            $result->whereRaw('remote_id='.$remote_option);
        }else{
            $result->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->where(function($q) use ($remote_option){
                $q->whereRaw('remote_id='.$remote_option);
            });
        }
        return $result;
    }
    
    public function updateUserInfoByEmail($email, $info) 
    {

        return $this->whereEmail($email)->update($info);
    }

    public function approvedBuyersCount()
    {
        return User::where('users.status', config('constants.APPROVED'))
            ->where('users.user_type_id', config('constants.BUYER'))
            ->where('users.admin_approval_status', config('constants.APPROVED'))
            ->count();
    }

    public function approvedVendorsCount()
    {
        return User::where('users.status', config('constants.APPROVED'))
            ->where('users.user_type_id', config('constants.VENDOR'))
            ->where('users.admin_approval_status', config('constants.APPROVED'))
            ->count();
    }

    public function pendingBuyersCount()
    {
        return User::where('users.status', config('constants.APPROVED'))
                ->where('users.user_type_id', config('constants.BUYER'))
                ->where('users.admin_approval_status', config('constants.PENDING'))
                ->count();
    }

    public function pendingVendorsCount()
    {
        return User::where('users.status', config('constants.APPROVED'))
            ->where('users.user_type_id', config('constants.VENDOR'))
            ->where('users.admin_approval_status', config('constants.PENDING'))
            ->count();
    }

    public function unverifiedBuyersCount()
    {
        return User::where('users.status', config('constants.PENDING'))
                ->where('users.user_type_id', config('constants.BUYER'))
                ->where('users.is_deleted', config('constants.PENDING'))
                ->count();
    }

    public function unverifiedVendorsCount()
    {
        return User::where('users.status', config('constants.PENDING'))
            ->where('users.user_type_id', config('constants.VENDOR'))
            ->where('users.is_deleted', config('constants.PENDING'))
            ->count();
    }

    public function archivedBuyersCount()
    {
        return User::where('users.status', config('constants.REJECTED'))
                ->where('users.user_type_id', config('constants.BUYER'))
                ->count();
    }

    public function archivedVendorsCount(){
        return User::where('users.status', config('constants.REJECTED'))
            ->where('users.user_type_id', config('constants.VENDOR'))
            ->count();
    }

    public function approvedExpertsCount()
    {
        return User::where('users.status', config('constants.APPROVED'))
                ->where('users.user_type_id', config('constants.EXPERT'))
                ->where('users.admin_approval_status', config('constants.APPROVED'))
                ->where('users.is_deleted', config('constants.NOT'))
                ->has('user_profile')
                ->count();
    }

    public function expertsToInterviewCount()
    {
        return User::where('users.status', config('constants.APPROVED'))
                ->where('users.user_type_id', config('constants.EXPERT'))
                ->where('users.admin_approval_status', config('constants.PENDING'))
                ->has('completeMandatoryFields')
                ->count();
    }

    public function expertsWithIncompleteProfilecount()
    {
        return User::where('admin_approval_status', config('constants.PENDING'))
                ->where('users.status', config('constants.APPROVED'))
                ->where('users.user_type_id', config('constants.EXPERT'))
                ->has('incompleteMandatoryFields')
                ->count();
    }

    public function sideHustlersExpertsCount()
    {
        return User::where('admin_approval_status', config('constants.PENDING'))
                ->where('users.status', config('constants.SIDE_HUSTLER'))
                ->where('users.user_type_id', config('constants.EXPERT'))
                ->count();
    }

    public function unverifiedExpertsCount()
    {
        return User::join('user_profiles as up', 'up.user_id', '=', 'users.id')
                ->where('users.status', config('constants.PENDING'))
                ->where('users.user_type_id', config('constants.EXPERT'))
                ->where('users.admin_approval_status', config('constants.PENDING'))
                ->where('users.is_deleted', config('constants.PENDING'))
                ->count();
    }

    public function archivedExpertscount()
    {
        return User::where('users.status', config('constants.REJECTED'))
                ->where('users.user_type_id', config('constants.EXPERT'))
                ->count();
    }

    public function groupUsers($skill_query)
    {
        $result = [];
        $query = static::selectRaw('array_agg(users.id) AS user_ids, users.skill_name')
                ->from(\DB::raw(' ( ' . $skill_query->toSql() . ' ) AS users '))
                ->mergeBindings($skill_query->getQuery())
                ->with('user_profile_picture')
                ->groupBy('users.skill_name')
                ->havingRaw('count(users.id) > 3')
                ->get();
        if(_count($query))
                $result = $query->toArray();
        
        return $result;
    }
    
    public function userDetailsWithCommunicationStatus($id) {
        return User::where('id', $id)
            ->with(['UsersCommunication'])
            ->first();
    }

    public function userInfoWithBusinessDetails($reciver_id) {
        return $this->where('id', $reciver_id)->with('businessInformation.businessDetails')->first();
    }
    
    public function buyerProfileWithCategories($id)
    {
        return self::where('id', $id)->with('buyer_profile')->with('UsersCategory')->with('UsersCommunication')->get()->toArray();
    }

    public static function forceDeleteUser($user_id) {
        return User::where('id', '=', $user_id)->forceDelete();
    }
}
