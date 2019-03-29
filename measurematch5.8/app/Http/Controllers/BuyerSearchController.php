<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Model\User;
use App\Model\PostJob;
use App\Model\SavedExpert;

class BuyerSearchController extends Controller {

    /**
     * Construct Method
     */
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function searchResult(Request $request){
        if (!Auth::user()->admin_approval_status) {
           return redirect('/project/create');
        }
        $original_keywords = trim($request->search);
        $location = trim($request->location);
        $remote_option = $request->selectremoteoption;
        $location_array = [];
        $result = '';
        $show_load_more_button = TRUE;
        $listed_experts_number = config('constants.NOT');
        $limit = config('constants.BUYER_SEARCH_PER_PAGE');
        
        if(!empty($original_keywords)){
            $result = $this->getSearchQuery($original_keywords);
        }
       
        if(!empty($location)){
            $location_array = $this->getLocationArray($location);
        }
         
        $saved_projects_list_for_expert = SavedExpert::getPostJobList([['buyer_id', Auth::user()->id], ['post_job_id', '!=', null], ['post_job_id', '!=', config('constants.ALL_PROJECTS')]]);
        $all_saved_experts = SavedExpert::findByCondition([['buyer_id', Auth::user()->id], ['post_job_id', config('constants.ALL_PROJECTS')]], 'expert_id',['type'=>'list']);

        $output = User::sortExpertResult($result, $location_array, $remote_option);
        if($output['success']){
            $total_records = $output['count']['total'];
            if(isset($request->listed_experts_number)){
                $listed_experts_number = $request->listed_experts_number;
            }
            $users = $output['data']->limit($limit)->offset($listed_experts_number)->get()->toArray();
            $users_count = _count($users);
            
            if($listed_experts_number+$limit>=$total_records || $users_count<$limit){
                $show_load_more_button = FALSE;
            }
            if (\Request::ajax())
            {
                return $result = [
                    'view' => view('buyer.searchexpertlistajax',
                        compact('users',
                            'all_saved_experts'))->render(),
                            'show_load_more_button' => $show_load_more_button
                ];
            }
            else
            {
                return view('buyer.newbuyersearchresult',
                    compact('users',
                        'show_load_more_button',
                        'original_keywords',
                        'location',
                        'remote_option',
                        'saved_projects_list_for_expert',
                        'all_saved_experts'));
            }
        }
    }
    
    private function getSearchQuery($original_keywords){
        $name_keywords_query = $this->getExpertSearchQueryByName([$original_keywords], 1);
        $other_keywords_query = $this->getExpertSearchQuery([$original_keywords], 0);
        $splitted_keywords = explode(" ",$original_keywords);

        if(is_array($splitted_keywords) && _count($splitted_keywords)>1){
            $splitted_keywords_query_by_name = $this->getExpertSearchQueryByName($splitted_keywords, 0);
            $other_splitted_keywords_query = $this->getExpertSearchQuery($splitted_keywords, 0);
        }

        $result = $name_keywords_query->union($other_keywords_query);
        if(isset($splitted_keywords_query) && !empty($splitted_keywords_query)){
            $result = $result->union($splitted_keywords_query_by_name)->union($other_splitted_keywords_query);
        }
        
        return $result;
    }
    
    private function getLocationArray($location){
        $location_array = explode(',', $location);
        return $this->removeAbrevationsFromLocation($location_array);
    }
    
    private function removeAbrevationsFromLocation($location_array)
    {
        if(_count($location_array) > 1)
        {
            foreach ($location_array as $key => $location)
            {
                if(strlen(trim($location)) < 3)
                    unset($location_array[$key]);
                else
                    $location_array[$key] = trim($location);
            }
        }
        return $location_array;
    }

    private function getExpertSearchQueryByName($keywords, $priority){
        return User::firstLastNameSearch($keywords, $priority);
    }

    private function getExpertSearchQuery($keywords, $priority){
        return User::userProfileSearch($keywords, $priority)
        ->union(User::userSkillsSearch($keywords, $priority))
        ->union(User::userEducationSearch($keywords, $priority))
        ->union(User::userCertificateSearch($keywords, $priority))
        ->union(User::userEmploymentSearch($keywords, $priority));
    }

    public function activeProjectsListing(Request $request){
        $expert_id = $request->expert_id;
        $jobs_list = PostJob::getPostedProjectsWithNoContractAccepted(Auth::user()->id)->toArray();
        $check_if_project_is_saved_in_all_projects = SavedExpert::findByCondition([['buyer_id', Auth::user()->id], ['expert_id', $request->expert_id], ['post_job_id', config('constants.ALL_PROJECTS')]],'',['type'=>'count']);
        $saved_projects_list_for_expert = SavedExpert::findByCondition([['buyer_id', Auth::user()->id], ['expert_id', $request->expert_id], ['post_job_id', '!=', null], ['post_job_id', '!=', config('constants.ALL_PROJECTS')]], 'post_job_id',['type'=>'list']);

        $saved = FALSE;
        if($check_if_project_is_saved_in_all_projects==0){
            $save_expert = new SavedExpert;
            $save_expert->post_job_id = config('constants.ALL_PROJECTS');
            $save_expert->buyer_id = Auth::user()->id;
            $save_expert->expert_id =$expert_id;
            if($save_expert->save()){
                $saved = TRUE;
            }
        }
        $all_records = SavedExpert::getExperts([['buyer_id', Auth::user()->id], ['post_job_id', config('constants.ALL_PROJECTS')]], 1);
        return $result = [
                    'view' => view('buyer.activeprojectslisting', compact('jobs_list', 'expert_id', 'saved_projects_list_for_expert'))->render(),
                    'saved' => $saved,
                    'all_records' => $all_records,
                    'jobs_count' => _count($jobs_list)
                ];
    }

    public function savedExpertsListing(Request $request){
        $load_more_results = FALSE;
        if(isset($request->post_job_id) && $request->post_job_id != config('constants.ALL_PROJECTS')){
            $condition_array = [['buyer_id', Auth::user()->id], ['post_job_id', $request->post_job_id]];
        }else{
            $condition_array = [['buyer_id', Auth::user()->id], ['post_job_id', config('constants.ALL_PROJECTS')]];
        }
        $total_records = SavedExpert::getExperts($condition_array, 1);
        $all_records = SavedExpert::getExperts([['buyer_id', Auth::user()->id], ['post_job_id', config('constants.ALL_PROJECTS')]], 1);
        
        if(!$request->post_job_id){
            $request->post_job_id = config('constants.ALL_PROJECTS');
        }
        if($total_records == 0 && $request->post_job_id != config('constants.ALL_PROJECTS')){
            $request->post_job_id = config('constants.ALL_PROJECTS');
            $condition_array = [['buyer_id', Auth::user()->id], ['post_job_id', config('constants.ALL_PROJECTS')]];
            $total_records = $all_records;
        }
        $selected_project = $request->post_job_id;
        $saved_projects_list_for_expert = SavedExpert::getPostJobList([['buyer_id', Auth::user()->id], ['post_job_id', '!=', null], ['post_job_id', '!=', config('constants.ALL_PROJECTS')]]);
        
        if($selected_project == config('constants.ALL_PROJECTS')){
            $saved_experts_heading = 'ALL SAVED EXPERTS ('.$total_records.')';
        }else{
            $project_detail = PostJob::getPostJobDetails($selected_project);
            $saved_experts_heading = strtoupper($project_detail->job_title).' ('.$total_records.')';
        }
        $limit = config('constants.BUYER_SEARCH_PER_PAGE');
        $listed_experts_number = 0;
        $show_load_more_button = TRUE;
        
        if(isset($request->listed_experts_number)){
            $load_more_results = TRUE;
            $listed_experts_number = $request->listed_experts_number;
        }
        if($listed_experts_number+$limit>=$total_records){
            $show_load_more_button = FALSE;
        }
        $users = SavedExpert::getExperts($condition_array, 0, ['expert.user_profile'], $limit, $listed_experts_number);
        $users_count = _count($users);
        if($users_count<$limit){
            $show_load_more_button = FALSE;
        }
        
        return $result = [
                    'view' => view('buyer.savedexpertlistajax', compact('users', 'total_records', 'saved_projects_list_for_expert', 'selected_project', 'saved_experts_heading', 'load_more_results'))->render(),
                    'show_load_more_button' => $show_load_more_button,
                    'total_experts' => $all_records
                ];
    }
    
    public function saveExpert(Request $request){
        $response = [];
        if(Auth::user()->user_type_id ==config('constants.BUYER')){
            $expert_id = $request->expert_id;
             if(empty($expert_id) || !isValidUuid($expert_id)){
                 $response = ['result' => 'error',
                             'error_message' => 'This expert could not be fetched. Please try again with another expert.'
                             ];
             }else{
                 $job_id = $request->job_id;
                 $check_alredy_exists = SavedExpert::getExperts([['expert_id', $expert_id], ['buyer_id', Auth::user()->id], ['post_job_id', $job_id]], 1);
                 if($check_alredy_exists==0){
                     $save_expert = new SavedExpert;
                     $save_expert->expert_id = $expert_id;
                     $save_expert->buyer_id = Auth::user()->id;
                     $save_expert->post_job_id = $job_id;
                     if($save_expert->save()){
                         $response = ['result' => 'saved'];
                     }else{
                         $response = ['result' => 'error',
                                  'error_message' => 'This expert could not be saved. Please try again.'
                                  ];
                     }
                 }
             }
        }else{
            //throw error
            $response = ['result' => 'error',
                         'error_message' => 'You are not authorized to access this page.'
                         ];
        }
        return $response;
    }
    
    public function unsaveExpertForProject(Request $request){
        $response = ['result' => 'error',
                         'error_message' => 'You are not authorized to access this page.'
                         ];
        if(Auth::user()->user_type_id ==config('constants.BUYER')){
            $expert_id = $request->expert_id;
            $post_job_id = $request->post_job_id;
            $selected_project = $request->selected_project;
             if(empty($expert_id) || !isValidUuid($expert_id)){
                 $response = ['result' => 'error',
                             'error_message' => 'This expert could not be fetched. Please try again with another expert.'
                             ];
             }else{
                if($post_job_id==config('constants.ALL_PROJECTS')){
                    $unsave_expert = SavedExpert::deleteSaved(['expert_id' => $expert_id, 'buyer_id' => Auth::user()->id]);
                }else{
                    $unsave_expert = SavedExpert::deleteSaved(['expert_id' => $expert_id, 'buyer_id' => Auth::user()->id,
                                                                'post_job_id' => $post_job_id]);
                }
                
                $saved_projects_list_for_expert = SavedExpert::getPostJobList([['buyer_id', Auth::user()->id], ['post_job_id', '!=', null], ['post_job_id', '!=', config('constants.ALL_PROJECTS')]]);
                $response = ['result' => 'error',
                             'error_message' => 'This expert could not be unsaved. Please try again.'
                             ];
                $all_records = SavedExpert::getExperts([['buyer_id', Auth::user()->id], ['post_job_id', config('constants.ALL_PROJECTS')]], 1);
                if($unsave_expert){
                    $response = ['result' => 'unsaved',
                                'all_records' => $all_records];
                }
             }
        }
        return $response;
    }
    
    public function updateUserSetting(Request $request){
        $input_data = $request->all();
        if(!empty($input_data) && _count($input_data)){
            return updateUserSetting($input_data);
        }
    }
}
