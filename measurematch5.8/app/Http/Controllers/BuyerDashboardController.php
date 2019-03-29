<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Communication;
use App\Model\PostJob;
use App\Model\Contract;
use App\Model\BuyerProfile;
use App\Model\UsersSkill;
use App\Model\SavedExpert;
use Auth;
use DB;
use Redirect;
use App\Model\Category;
use App\Model\Skill;
use App\Model\Deliverable;
use App\Model\JobsSkill;
use App\Model\JobViewer;

class BuyerDashboardController extends Controller
{

    /**
     * Construct Method
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Show Interest Status Method
     * old dashboard design
     * @param type $user_id
     * @param type $buyer_id
     * @param type $job_id
     *
     * @return string
     */
    public function showInterestStatus($user_id, $buyer_id, $job_id)
    {
        $checkStatus = DB::table('communications')
            ->where('buyer_id', '=', $buyer_id)
            ->where('user_id', '=', $user_id)
            ->where('job_post_id', '=', $job_id)
            ->get();
        if ($checkStatus) {
            $response = $checkStatus;
        } else {
            $response = '';
        }
        return $response;
    }


    /**
    * New buyer dashboard design
    */
    public function integratedBuyerDashboard()
    {
        if ((Auth::check())) {
            if (Auth::user()->user_type_id == config('constants.BUYER')) {
                return Redirect::To('/');
            }
        }
        return Redirect::To('/login');
    }
    public function addNameDetailInSession(Request $request)
    {
        if (!empty(Auth::user() && Auth::user()->id) && Auth::user()->user_type_id == config('constants.BUYER')) {
            $form_data = $request->all();
            $result_arr = [];
            if (!empty($form_data['project_name']) && !empty($form_data['project_detail'])) {
                $request->session()->put('project_name_detail_'.Auth::user()->id, $form_data);
                if ($request->session()->has('project_name_detail_'.Auth::user()->id)) {
                    $result_arr = ['success' => 1];
                } else {
                    $result_arr = ['success' => 0, 'error_msg' => __('global.internal_server_error')];
                }
            } else {
                $result_arr = ['success' => 0, 'error_msg' => __('buyer_dashboard.project_name_compulsory')];
            }
        } else {
            $result_arr = ['success' => 0, 'error_msg' => __('buyer_dashboard.not_authorized_to_process')];
        }

        return $result_arr;
    }

    /*New buyer dashbord design*/
    public function integratedMyProjects(Request $request)
    {
        $welcome = $request->get('welcome');
        $user_id = Auth::user()->id;
        $posted_projects = PostJob::getPostedProjects($user_id);
        $buyer_data = BuyerProfile::getBuyerDetail($user_id);
        $pending_project = PostJob::getPublishedJobs($user_id, config('constants.PROJECT_PENDING'));
        $drafts = PostJob::getDraftsJobs($user_id);
        $communication_detail = Communication::fetchCommunications(
                ['buyer_id' => Auth::user()->id,
                'type' => 'service_package'], '', 
                ['orderBy' => 'contract_action_date'], 
                ['servicePackageDetail',
                'relatedContract',
                'unreadServicePackagesMessageCount',
                'expertProfilePicture']
        );
        return view('buyerdashboard.integrated_buyer_my_projects', compact(
                'buyer_data',
                'posted_projects',
                'drafts',
                'pending_project',
                'welcome',
                'communication_detail'
        ));
    }

    public function projectProgress($id)
    {
        if (Auth::user()->user_type_id==config('constants.BUYER')) {
            //checking if the project belongs to user
            if (!ctype_digit($id)) {
                return view('errors.404');
            }
            $job_details = PostJob::getFirstAcceptedContractUser($id, Auth::user()->id);
            if (_count($job_details)==0) {
                return view('errors.404');
            }
            $skill_array = $this->getSkillArray($job_details);
            $random_experts = $this->recommendedExperts($skill_array, 6);

            if (_count($random_experts) > 6) {
                $random_experts = array_slice($random_experts, 0, 6);
            }

            $job_details = $job_details->toArray();
            $images = [];
            if (!empty($job_details['upload_document'])) {
                $images = json_decode($job_details['upload_document'], true);
            }
            $skills = $tools = [];
            foreach ($job_details['jobsskill'] as $key=>$skill_array) {
                if ($skill_array['skill']['is_tool'] === true) {
                    $tools[] = $skill_array['skill']['name'];
                }
                if ($skill_array['skill']['is_tool'] !== true) {
                    $skills[] = $skill_array['skill']['name'];
                }
            }

            $contracts_count= Contract::findByCondition(['job_post_id'=>$id,'parent_contract_id'=>null], [], [], 'count');
            $location_preference_array = array(
                                            '1'=>'Only work remotely',
                                            '2'=>'Only work on site',
                                            '3'=>'Can work remotely and on site'
                                        );
            return view('buyerdashboard.project_progress', compact('job_details', 'location_preference_array', 'random_experts', 'contracts_count', 'images', 'skills', 'tools'));
        } else {
            return view('errors.404');
        }
    }
   
    private function recommendedExperts($skill_array, $limit)
    {
        $random_experts_per_skill = [];
        $random_experts_with_no_skill_match = [];
        $users_to_exclude = [];
        $random_experts = [];
        if (_count($skill_array)) {
            for ($i=0; $i<count($skill_array); $i++) {
                $experts_count = 0;
                $random_expert_array = $this->get_random_experts_per_skill($skill_array[$i], $users_to_exclude, $limit);
                if (_count($random_expert_array)) {
                    $users_to_exclude_result = $this->get_users_excluded_from_random_experts($random_expert_array);
                    if (_count($users_to_exclude_result)) {
                        $users_to_exclude = array_merge($users_to_exclude, $users_to_exclude_result);
                    }
                    $random_experts_per_skill = array_merge($random_experts_per_skill, $random_expert_array);
                    $experts_count+=count($random_experts_per_skill);
                }
                if ($experts_count >= $limit) {
                    break;
                }
            }
        }
        $random_experts = $random_experts_per_skill;
        if (_count($random_experts_per_skill) < $limit) {
            if (_count($users_to_exclude)) {
                $random_experts_with_no_skill_match = UsersSkill::getUserSKillsNotExists($users_to_exclude, $limit);
            } else {
                $random_experts_with_no_skill_match = UsersSkill::getRandomUserSkills($limit);
            }
            $random_experts = array_merge($random_experts_per_skill, $random_experts_with_no_skill_match);
        }
        return $random_experts;
    }
    
    public function get_users_excluded_from_random_experts($random_experts_per_skill)
    {
        foreach ($random_experts_per_skill as $key => $random_experts_per_skill_values) {
            $users_to_exclude[] = $random_experts_per_skill_values['user_id'];
        }
        return $users_to_exclude;
    }
    
    private function get_random_experts_per_skill($skill_id, $users_to_exclude = array(), $limit)
    {
        return UsersSkill::getRandomUserSkillWithUserId($skill_id, $limit, $users_to_exclude);
    }

    //ajax request for saving expert by user
    public function saveExpert($id)
    {
        $response = [];
        if (Auth::user()->user_type_id ==config('constants.BUYER')) {
            if (empty($id) || !isValidUuid($id)) {
                $response = ['result' => 'error',
                            'error_message' => 'This expert could not be fetched. Please try again with another expert.'
                            ];
            } else {
                $check_alredy_exists = SavedExpert::findByCondition(['expert_id' => $id, 'buyer_id' => Auth::user()->id], '', ['type' => 'count'], ['post_job_id' => 'null']);
                if ($check_alredy_exists==0) {
                    $save_expert = new SavedExpert;
                    $save_expert->expert_id = $id;
                    $save_expert->buyer_id = Auth::user()->id;
                    if ($save_expert->save()) {
                        $response = ['result' => 'saved'];
                    } else {
                        $response = ['result' => 'error',
                                 'error_message' => 'This expert could not be saved. Please try again.'
                                 ];
                    }
                } else {
                    $response = ['result' => 'error'];
                    if (SavedExpert::deleteSaved(['expert_id' => $id, 'buyer_id' => Auth::user()->id], ['post_job_id' => 'null'])) {
                        $response = ['result' => 'unsaved'];
                    }
                }
            }
        } else {
            //throw error
            $response = ['result' => 'error',
                        'error_message' => 'You are not authorized to access this page.'
                        ];
        }
        return $response;
    }

    public function savedExpertsListing(Request $request)
    {
        if (Auth::user()->user_type_id == config('constants.BUYER')) {
            $ssl=getenv('APP_SSL');
            $saved_experts = SavedExpert::getGloballlySavedExperts(Auth::user()->id);
            $saved_experts->setPath(url('savedexpertlisting', [], $ssl));
            if ($request->ajax()) {
                return view('buyerdashboard.saved_experts_ajax_listing', compact('saved_experts'))->render();
            } else {
                return view('errors.404');
            }
        } else {
            return 0;
        }
    }

    public function pastMatchingExpertsListing(Request $request)
    {
        if (Auth::user()->user_type_id == config('constants.BUYER')) {
            $ssl=getenv('APP_SSL');
            $past_matches = Contract::select('user_id', DB::raw('count(*) as total'))
                                        ->groupBy('user_id')
                                        ->where('buyer_id', Auth::user()->id)
                                        ->where('status', '1')
                                        ->with('expert.user_profile')
                                        ->paginate(6);
            $past_matches->setPath(url('pastmatchingexpertlisting', [], $ssl));
            if ($request->ajax()) {
                return view('buyerdashboard.past_matching_experts_ajax', compact('past_matches'))->render();
            } else {
                return view('errors.404');
            }
        } else {
            return 0;
        }
    }

    public function recommendedExpertsAjax($id)
    {
        if (Auth::user()->user_type_id==2) {
            //checking if the project belongs to user
            if (!ctype_digit($id)) {
                return view('errors.404');
            }
            $job_details = PostJob::where([['id', $id],['user_id', Auth::user()->id]])->with('jobsskill.skill')->with('contract')->first();
            if (_count($job_details)==0) {
                return view('errors.404');
            }

            $skill_array = $this->getSkillArray($job_details);
            $random_experts = $this->recommendedExperts($skill_array, 6);

            if (_count($random_experts) > 6) {
                $random_experts = array_slice($random_experts, 0, 6);
            }
            return view('buyerdashboard.recommended_experts_ajax', compact('random_experts'));
        } else {
            return view('errors.404');
        }
    }

    public function getSkillArray($job_details)
    {
        $skill_array = [];
        if (isset($job_details->jobsskill) && _count($job_details->jobsskill)) {
            foreach ($job_details->jobsskill as $id => $skills) {
                $skill_array[] = $skills->skill_id;
            }
        }
        return $skill_array;
    }

    public function softDeleteProject($id)
    {
        $response_array = [];

        if (false) {
            //Auth::user()->user_type_id == config('constants.BUYER')
            if (!ctype_digit($id)) {
                //send error message
                $response_array = ['result' => 'error',
                                'error_msg' => 'No job found. Please try again.'
                                ];
            }
            $post_job = PostJob::find($id);
            if (_count($post_job)) {
                $post_job ->delete();
                if ($post_job->trashed()) {
                    //send success message
                    $response_array = ['result' => 'deleted'];
                } else {
                    //send error message
                    $response_array = ['result' => 'error',
                        'error_msg' => 'Job could not be deleted. Please try again.'
                        ];
                }
            } else {
                //send error message.
                $response_array = ['result' => 'error',
                                'error_msg' => 'No job found. Please try again.'
                                ];
            }
        } else {
            $response_array = ['result' => 'error',
                                'error_msg' => 'You are not authorized to access this page.'
                                ];
        }
        return $response_array;
    }

    public function deleteProject($id)
    {
        $response_array = ['result' => 'error',
                            'error_msg' => 'You are not authorized to access this page.'
                            ];
        if (Auth::user()->user_type_id == config('constants.BUYER')) {
            if (!ctype_digit($id)) {
                //send error message
                $response_array = ['result' => 'error',
                                'error_msg' => 'No job found. Please try again.'
                                ];
            }
            $post_job = PostJob::find($id);
            if (_count($post_job)) {
                if ($post_job->publish == config('constants.PROJECT_PENDING')) {
                    Deliverable::deleteDeliverableByProjectId($id);
                    JobsSkill::deleteSkillJob($id);
                    JobViewer::deleteJobViewer($id);
                    if ($post_job ->forceDelete()) {
                        $response_array = ['result' => 'deleted'];
                    } else {
                        $response_array = ['result' => 'error','error_msg' => config('constants.PROJECT_DELETE_ERROR')];
                    }
                }
            }
        }
        return $response_array;
    }
}
