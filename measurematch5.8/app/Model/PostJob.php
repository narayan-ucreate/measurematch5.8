<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class PostJob extends Model {

    use SoftDeletes;

    protected $table = 'post_jobs';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'post_is_negotiable', 'job_num', 'job_title', 'description', 'remote_id', 'job_start_date', 'project_duration', 'job_end_date', 'rate_variable', 'rebook_project',
        'currency', 'publish', 'publish_date', 'office_location', 'rate', 'hide_company_name', 'user_id', 'upload_document','visibility_date','budget_approval_status'
    ];

    /**
     * Jobs skill Method
     *
     * @return type
     */
    public function jobsskill() {
        return $this->hasMany('App\Model\JobsSkill', 'job_post_id', 'id');
    }

    /**
     * Jobs Category Method
     *
     * @return type
     */
    public function jobscategory() {

        return $this->hasmany('App\Model\JobsCategory', 'job_post_id', 'id');
    }

    /**
     * Drafts Method
     *
     * @return type
     */
    public function drafts() {

        return $this->hasmany('App\Model\Draft', 'job_post_id', 'id');
    }

    /**
     * User Method
     *
     * @return type
     */
    public function user()
    {
        return $this->belongsTo('App\Model\User', 'user_id', 'id');
    }
    public function activeUser()
    {
        return $this->belongsTo('App\Model\User', 'user_id', 'id')
            ->where([
                    'status' => config('constants.APPROVED'),
                    'is_deleted' => config('constants.NOT'),
                    'admin_approval_status' => config('constants.APPROVED'),
                    'hide_profile' => config('constants.NOT')
                ]
            );
    }

    /**
     * Buyer Method
     *
     * @return type
     */
    public function buyer()
    {
        return $this->belongsTo('App\Model\BuyerProfile', 'user_id', 'user_id');
    }

    /**
     * Remote Method
     *
     * @return type
     */
    public function remote() {
        return $this->belongsTo('App\Model\RemoteWork', 'remote_id');
    }

    /**
     * Communication Method
     *
     * @return type
     */
    public function communication() {
        return $this->hasMany('App\Model\Communication', 'job_post_id')->orderBy('communications.created_at', 'DESC');
    }

    public function jobViewer() {
        return $this->hasMany('App\Model\JobViewer', 'job_posted_id');
    }

    public function communicationAscendingOrder()
    {
        return $this->hasMany('App\Model\Communication', 'job_post_id')->orderBy('communications.created_at', 'ASC');
    }

    public function communicationList()
    {
        return $this->hasMany('App\Model\Communication', 'job_post_id')->select('id', 'job_post_id');
    }

    /**
     * Buyer Profile
     *
     * @return type
     */
    public function buyer_profile() {
        return $this->belongsTo('App\Model\BuyerProfile', 'user_id', 'user_id');
    }

    public function contract() {
        return $this->hasOne('App\Model\Contract', 'job_post_id', 'id');
    }

    public function acceptedContractInfo() {
        return $this->hasOne('App\Model\Contract', 'job_post_id', 'id')->where('status', 1);
    }

    public function acceptedContract() {
        return $this->hasOne('App\Model\Contract', 'id', 'accepted_contract_id');
    }

    public function contracts() {
        return $this->hasMany('App\Model\Contract', 'job_post_id', 'id');
    }
    
    public function latestActiveContract() {
        return $this->hasOne('App\Model\Contract', 'job_post_id', 'id')
                ->where('complete_status', config('constants.NOTCOMPLETED'))
                ->where('status', config('constants.ACCEPTED'))
                ->where('is_extended', false)
                ->whereNull('contracts.deleted_at');
    }

    public function deliverables() {
        return $this->hasMany('App\Model\Deliverable', 'post_job_id', 'id')->where('type', config('constants.PROJECT'));
    }

    public static function getPostInformationWithBuyerId($buyer_id) {
        return PostJob::where('user_id', $buyer_id)->get()->toArray();
    }

    public static function getPublishedJobs($user_id, $publish) {
        return PostJob::where('user_id', $user_id)->where('publish', '=', $publish)->get();
    }

    public static function getDraftsJobs($user_id) {
        return PostJob::where('user_id', $user_id)->where('publish', '=', config('constants.DRAFTED'))->orderBy('created_at', 'desc')->get();
    }

    public static function getProjectDetails($project_id) {
        return PostJob::with('jobsskill.skill')->with('jobscategory.category')->with('contract')->with('remote')->where('id', $project_id)->first();
    }

    public static function getProjectDetailsInArray($project_id) {
        return PostJob::with('jobsskill.skill')->with('jobscategory.category')->with('contract')->with('remote')->where('id', $project_id)->get()->toArray();
    }


    public static function getPostedProjects($user_id)
    {
        return PostJob::select('id', 'job_title', 'created_at', 'job_end_date', 'publish', 'accepted_contract_id')
            ->with([
                'contracts',
                'communicationAscendingOrder.expertProfilePicture',
                'communicationList.unreadProjectMessageCount',
                'acceptedContract' => function($query){
                    $query->select('id', 'user_id');
                },
                'acceptedContract.expert' => function($query){
                    $query->select('id', 'name');
                },
                'acceptedContract.expert.user_profile'
            ])
            ->where('user_id', $user_id)
            ->whereIn('publish', [config('constants.COMPLETED'), config('constants.PROJECT_PENDING')])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function jobPostedByBuyerDetail($id, $user_id) {
        return PostJob::where([['id', $id], ['user_id', $user_id]]);
    }

    public static function getPostJobInformation($job_post_id) {
        return PostJob::where('id', $job_post_id);
    }

    public static function getPostedProjectsWithNoContractAccepted($user_id) {
        return PostJob::where('user_id', $user_id)->where('accepted_contract_id', Null)->where('publish', 1)->get();
    }

    public static function countPostedProjectsWithNoContractAccepted($user_id) {
        return PostJob::where('user_id', $user_id)->where('accepted_contract_id', Null)->where('publish', 1)->count();
    }

    public static function updateProjectAcceptedContractedId($project_id, $contract_id) {
        return PostJob::where('id', $project_id)->update(['accepted_contract_id' => $contract_id]);
    }


    public static function updateProjectAcceptedContractedCompleteStatus($project_id) {
        return PostJob::where('id', $project_id)->update(['accepted_contract_complete_status' => TRUE]);
    }

    public static function getProjectDetailedInformation($post_id) {
        return PostJob::with('jobsskill.skill')->with('jobscategory.category')->with('drafts.post')->with('remote')->where('id', $post_id)->get()->toArray();
    }

    public static function getPostJobWithBuyerId($buyer_id, $publish) {
        return PostJob::where('user_id', $buyer_id)->where('publish', $publish);
    }

    public static function getPostJobWithPostId($project_id, $publish,$options=[]) {
        $projects =  PostJob::with('jobsskill', 'jobscategory', 'contract')->with('jobscategory.category')->with('jobsskill.skill')->where('id', $project_id)->where('publish', $publish)->paginate(25)->toArray();
        $skills=[];
        if(isset($options['filter']) && $options['filter']=='skills'){
            foreach($projects['data'][0]['jobsskill'] as $key=>$jobskill){
                $skills[]= $jobskill['skill']['name'];
            }
            $projects['data'][0]['skills']=implode(',',$skills);
            unset($projects['data'][0]['jobsskill']);
        }
        return $projects;
    }

    public static function getAllPostJobs($project_id) {
        return PostJob::with('jobsskill','jobscategory', 'contract')->with('jobscategory.category')->with('jobsskill.skill')->where('id', $project_id)->paginate(25)->toArray();
    }
    public static function scopeGetProjectsWithDetail($query) {
        return $query->with('user.buyer_profile', 'jobsskill', 'jobscategory', 'contract.expert')
            ->with('jobscategory.category')
            ->with('jobsskill.skill');
    }
    public static function exportPublishedBuyerProjects($publish, $user_id, $sort) {
        return PostJob::getProjectsWithDetail()
            ->where('publish', $publish)
            ->where('user_id', $user_id)
            ->orderBy('created_at', $sort)->get()->toArray();
    }
    public static function exportExpiredProjects($publish,$today) {
        return PostJob::getProjectsWithDetail()
            ->withCount('communication')
            ->whereIn('publish', $publish)
            ->where('visibility_date', '<', $today)
            ->doesntHave('acceptedContract')
            ->orderBy('created_at', 'asc')->get()->toArray();
    }
    public static function exportProjectsInformation($publish,$today) {
        $query= PostJob::getProjectsWithDetail()
                ->withCount('communication')
                ->where('publish', $publish)
                ->where(function($query) use ($today) {
               $query->where('post_jobs.visibility_date', '>=', $today)
                   ->orWhereNull('post_jobs.visibility_date');
            });
        $query=$query->orderBy('created_at', 'asc')->get()->toArray();
        return $query;
    }

    public static function scopePublished($query, $status) {
        $query->where('publish', '=', $status);
    }

    public function countProjectEoi()
    {
        return $this->hasMany(Communication::class, 'job_post_id')->where('type', 'project');
    }

    public static function getCurrentPublishedJobs() {
        return PostJob::published(1)
            ->where('visibility_date', '>=', date('Y-m-d H:i:s'))
            ->whereDoesntHave('contracts', function($query){
                return $query->where('status', config('constants.ACCEPTED'));
            })
            ->with(['buyer_profile' => function($query) {
                return $query->select('company_name', 'office_location', 'user_id', 'id', 'type_of_organization_id');
            }, 'buyer_profile.type_of_organisation'])

            ->orderBy('publish_date','desc')
            ->select(
                'id',
                'description',
                'job_title',
                'job_end_date',
                'rate_variable',
                'rate',
                'currency',
                'publish_date',
                'hide_company_name',
                'office_location',
                'user_id'
            )->withCount('countProjectEoi')
            ->withCount('jobViewer')
            ->paginate(config('constants.PROJECT_SEARCH_PAGINATION_LIMIT'))
            ->withPath(url('expert/projects-search', [], getenv('APP_SSL')))
            ->toArray();
    }

    public static function getJobsInRandomOrder($user_id, $id) {
        return PostJob::where('user_id',$user_id)->where('id', '!=', $id)->inRandomOrder()->take(config('constants.PER_PAGE'))->with('buyer')->get()->toArray();
    }

    public static function searchAllUserJobsByTitleInRandomOrder($user_id, $title) {
        return PostJob::where('user_id', '!=', $user_id)->where('job_title', 'like', '%' . $title . '%')->inRandomOrder()->take(config('constants.PER_PAGE'))
            ->with('buyer')
            ->get()->toArray();
    }

    public static function getSkillsWithPostId($id) {
        return PostJob::with([
            'jobsskill.skill',
            'jobscategory.category',
            'remote',
            'deliverables'
        ])
            ->where('id', $id)
            ->withCount('jobViewer')
            ->withCount('countProjectEoi')
            ->first()
            ->toArray();
    }

    public static function getPostJobDetails($postid) {
        return PostJob::where(['id' => $postid])
            ->first();
    }

    public function checkProjectPublishStatus($user_id,$project_id) {
        return PostJob::select('publish', 'rebook_project')->where(['user_id'=>$user_id, 'id' => $project_id])->first();
    }

    public static function getFirstAcceptedContractUser($id,$user_id){
        return PostJob::where([['id', $id], ['user_id', $user_id]])
            ->with(['jobsskill.skill', 'buyer', 'contracts.expert.user_profile', 'acceptedContract.expert.user_profile', 'deliverables'])
            ->first();
    }


    public static function createdAtDateOfProjects(){
        return PostJob::select('id', 'created_at')->where('publish', '1');
    }
    public static function updatePostData($data, $id) {
        return PostJob::where('id', $id)->update($data);
    }
    public static function updatePostDataByUser($data, $user_id) {
        return PostJob::where('user_id', $user_id)->update($data);
    }
    public static function getPostInformation($job_id) {
        return PostJob::where('id', $job_id)->get()->toArray();
    }
    public static function updatePublishedDate($project_id,$created_at){
        return PostJob::where('id', $project_id)->update(['publish_date'=>$created_at]);
    }
    public static function getAcceptanceContract($project_id){
        return PostJob::where('id', $project_id)->with('acceptedContract')->with('contracts')->withCount('communication')->first();
    }
    public static function getPostInformationWithUserIdAndDate($auth_id){
        return PostJob::
            where('post_jobs.user_id', '=', $auth_id)
            ->whereIn('publish', [config('constants.PUBLISHED'), config('constants.PROJECT_PENDING')])
            ->where('post_jobs.accepted_contract_id', NULL)
            ->get()
            ->where('rebook_project', false)
            ->toArray();
    }

    public static function getPostJobInArray($unique_post_ids){
        return PostJob::whereIn('id', $unique_post_ids);
    }
    public static function findByCondition($conditions = [], $withs = [], $query_options = []) {
        if (is_array($conditions) && _count($conditions)) {
            $conitions_array = [];
            foreach ($conditions as $key => $val) {
                $conitions_array[$key] = $val;
            }
        }
        $query = PostJob::where($conitions_array);
        if (isset($withs) && !empty($withs)) {
            foreach ($withs as $with) {
                $query = $query->with($with);
            }
        }
        if (array_key_exists('type', $query_options)) {
            if ($query_options['type'] == 'count') {
                $result = $query->count();
            }
        }else{
            $result = $query->get();
        }
        return $result;
    }
    public static function getPostListing($publish_status, $today, $order)
    {
        if (isset($order['orderBy']) && !empty($order['orderBy'])) {
            $order_by = $order['orderBy'];
        } else {
            $order_by = 'DESC';
        }
        $query = PostJob::join('users as u', 'u.id', '=', 'post_jobs.user_id')->select('*', 'post_jobs.id as id', 'post_jobs.created_at as created_at', DB::raw("concat(u.name, ' ', u.last_name) AS myname"))->with('user')->where('publish', $publish_status);

        if($publish_status != config('constants.PROJECT_PENDING'))
            $query->where('visibility_date', '>=', $today);
        else
            $query->where('visibility_date', '=', $today);

        if (isset($order['data-sort']) && !empty($order['data-sort'])) {
            $sorting_field_in_array = explode(',', $order['data-sort']);
            foreach ($sorting_field_in_array as $field_value) {
                $query = $query->orderBy($field_value, $order_by);
            }
        } else {
            $query->orderBy('post_jobs.created_at', $order_by);
        }
        return $query;
    }

    public static function activeJobsWithSkills($buyer_id)
    {
        return PostJob::select('id')->where([['accepted_contract_id', null], ['user_id', $buyer_id]])->with('jobsskill.skill')->get();
    }
    
    public static function getLiveProjects($today){
        return self::join('users as u', 'u.id', '=', 'post_jobs.user_id')
            ->join('buyer_profile', 'buyer_profile.user_id', '=', 'u.id')
            ->select('*', 'post_jobs.id as id', 'post_jobs.created_at as created_at', 'buyer_profile.company_name')
            ->with('buyer')
            ->withCount('communication')
            ->where('publish', '1')
            ->whereNull('accepted_contract_id')
            ->where('visibility_date', '>=', $today);
    }
    
    public static function getCompletedProjects(){
        return self::select('post_jobs.id as project_id', 'post_jobs.job_title', 'post_jobs.currency', 
                'buyer_profile.company_name', 'contracts.job_start_date', 'contracts.job_end_date', 'users.name', 
                'users.last_name')
            ->where([
                ['accepted_contract_complete_status', true]
            ])
            ->join('contracts', 'contracts.id', '=', 'post_jobs.accepted_contract_id')
            ->join('users', 'users.id', '=', 'contracts.user_id')
            ->join('buyer_profile', 'buyer_profile.user_id', '=', 'post_jobs.user_id')
            ->doesntHave('latestActiveContract');
    }

    public static function getRebookingProjects(){
        return self::join('users as u', 'u.id', '=', 'post_jobs.user_id')
            ->join('buyer_profile', 'buyer_profile.user_id', '=', 'u.id')
            ->join('communications', 'communications.job_post_id', '=', 'post_jobs.id')
            ->join('user_profiles', 'user_profiles.user_id', '=', 'communications.user_id')
            ->join('users as experts', 'experts.id', '=', 'communications.user_id')
            ->select('post_jobs.id as project_id', 'post_jobs.user_id as buyer_id', 'post_jobs.job_title',
                'post_jobs.rate', 'post_jobs.currency', 'post_jobs.publish_date', 'buyer_profile.company_name', 'rebook_project', 'experts.name', 'experts.last_name')
            ->withCount('communication')
            ->where('publish', config('constants.PUBLISHED'))
            ->where('rebook_project', true)
            ->doesntHave('acceptedContract');
    }

    public static function getArchivedProjects(){
        return self::join('users as u', 'u.id', '=', 'post_jobs.user_id')
            ->select('*', 'post_jobs.id as id', 'post_jobs.created_at as created_at', DB::raw("concat(u.name, ' ', u.last_name) AS myname"))
            ->where('publish', config('constants.PROJECT_REJECTED'));
    }
    public static function getExpiredProjects($today)
    {
        return self::join('users as u', 'u.id', '=', 'post_jobs.user_id')
            ->join('buyer_profile', 'buyer_profile.user_id', '=', 'u.id')
            ->select('post_jobs.id as project_id', 'post_jobs.user_id as buyer_id', 'post_jobs.job_title', 
                'post_jobs.rate', 'post_jobs.currency', 'post_jobs.publish_date', 'buyer_profile.company_name')
            ->withCount('communication')
            ->whereIn('publish', [config('constants.PUBLISHED'), config('constants.PROJECT_PENDING')])
            ->where('visibility_date', '<', $today)
            ->doesntHave('acceptedContract');
    }

    
    public static function getInContractProjects()
    {
        return self::join('users', 'users.id', '=', 'post_jobs.user_id')
            ->join('buyer_profile', 'buyer_profile.user_id', '=', 'users.id')
            ->select('post_jobs.id as project_id', 'post_jobs.user_id as buyer_id', 'post_jobs.job_title', 
                'post_jobs.rate', 'post_jobs.currency', 'contracts.user_id as expert_id', 'contracts.id as contract_id', 
                'buyer_profile.company_name', 'contracts.job_start_date', 'contracts.job_end_date', 'experts.name', 
                'experts.last_name')
            ->where('publish', config('constants.PUBLISHED'))
            ->join('contracts', function($contract_join){
                $contract_join->on('contracts.job_post_id', '=', 'post_jobs.id');
                $contract_join->where('contracts.status', config('constants.ACCEPTED'));
                $contract_join->where('contracts.complete_status', config('constants.NOTCOMPLETED'));
                $contract_join->whereNull('contracts.deleted_at');
                $contract_join->where('is_extended', false);
            })
            ->join('users as experts', 'experts.id', '=', 'contracts.user_id');
    }
    
    public function exportInContractProjects()
    {        
        return PostJob::with('user.buyer_profile', 'jobsskill.skill', 'jobscategory.category', 'latestActiveContract.expertName')
            ->where('publish', config('constants.APPROVED'))
            ->whereHas('contract', function($query){
                 $query->where('complete_status', config('constants.NOTCOMPLETED'));
                 $query->where('status', config('constants.ACCEPTED'));
                 $query->where('is_extended', false);
                 $query->whereNull('contracts.deleted_at');
            })
            ->withCount('communication')
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
    }
    
    public function exportCompletedProjects()
    {      
        return PostJob::with('user.buyer_profile', 'jobsskill.skill', 'jobscategory.category', 'contract.expertName')
            ->where([
                ['accepted_contract_complete_status', true]
            ])
            ->doesntHave('latestActiveContract')
            ->withCount('communication')
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
    }

    public function getJobInfo($buyer_id, $job_id) {
        return $this
            ->leftjoin('remote_works', 'remote_works.id', '=', 'post_jobs.remote_id')
            ->whereUserId($buyer_id)
            ->where('post_jobs.id', $job_id)
            ->first();
    }

    public function liveProjectsCount()
    {
        return PostJob::where('publish', config('constants.APPROVED'))
            ->where([
                ['visibility_date', '>=', date('Y-m-d H:i:s')],
                ['accepted_contract_complete_status', false]
            ])
            ->count();
    }

    public function completedProjectsCount(){
        return PostJob::where([
            ['visibility_date', '>=', date('Y-m-d H:i:s')],
            ['accepted_contract_complete_status', true]
        ])
        ->count();
    }

    public function archivedProjectsCount()
    {
        return PostJob::where('publish', config('constants.PROJECT_REJECTED'))
            ->count();
    }
    
    public function inContractProjectsCount()
    {
        return PostJob::where('publish', config('constants.APPROVED'))
            ->whereHas('contracts', function($query){
                 $query->where('complete_status', config('constants.NOTCOMPLETED'));
                 $query->where('status', config('constants.ACCEPTED'));
                 $query->where('is_extended', false);
                 $query->whereNull('contracts.deleted_at');
            })
            ->count();
    }

    public function expiredProjectsCount()
    {
        return PostJob::whereIn('publish', [config('constants.PUBLISHED'), config('constants.PROJECT_PENDING')])
            ->where('visibility_date', '<', date('Y-m-d H:i:s'))
            ->doesntHave('acceptedContract')
            ->count();
    }

    public function pendingProjectsCount()
    {
        return PostJob::join('users as u', 'u.id', '=', 'post_jobs.user_id')
            ->where('publish', config('constants.PROJECT_PENDING'))
            ->where('visibility_date', '=', null)
            ->count();
    }
    public static function getAllProjectsPublishedDate(){
        return PostJob::select('id', 'publish_date')->where('publish', config('constants.PUBLISHED'))->get();
    }
    public function getBuyerProjectCreatedCount($id)
    {
        return PostJob::where('user_id',$id)->count();
    }
    public function getBuyerProjectApprovedCount($id)
    {
        return PostJob::where(['user_id'=>$id, 'publish'=> config('constants.PUBLISHED')])->count();
    }
    
    public function fetchWithSelectedFields($conditions, $fields, $type)
    {
        $query  = self::where($conditions);
        if($type == 'first')
            $result = $query->first($fields);
        else
            $result = $query->get($fields);
        
        return $result;
    }
    
    public function getSelectedFields($select_fields, $conditions)
    {
        return self::select($select_fields)->where($conditions)->first();
    }
}
