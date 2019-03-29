<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Communication;
use App\Model\PostJob;
use App\Model\Contract;
use App\Model\BuyerProfile;
use Auth;
use DB;
use Redirect;
use App\Model\User;

class DashboardController extends Controller {

    /**
     * Construct Method
     */
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function dashboard(Request $request) {
        if (calculateProfileCompletePercentageStatus()['basic_profile_completness'] == false || Auth::user()->admin_approval_status != 1) {
            return redirect('/expert/profile-summary');
        } else {
            return redirect('/expert/projects-search');
        }
    }
    
    /**
     * Jobs View Method
     * 
     * @param Request $request
     * 
     * @return type
     */
    public function jobsView(Request $request) {
        if (isExpert() && Auth::user()->admin_approval_status == config('constants.TRUE')) {
            $project_id = app('request')->input('sellerid');
            if (!empty($project_id) && is_numeric($project_id)) {
                $job_preview = PostJob::getSkillsWithPostId($project_id);
                if (!empty($job_preview)) {
                    $expert_job_interest = Communication::fetchCommunications(['job_post_id' => $project_id], 'count');
                    $post_company = BuyerProfile::getBuyerProfile($job_preview['user_id'])->first();
                    $job_id = $job_preview['id'];
                    $user_id = Auth::user()->id;
                    $buyer_id = $post_company->user_id;
                    $response = $this->showInterestStatus($user_id, $buyer_id, $job_id);
                    if (isset($response) && !empty($response)) {
                        $status = $response[0]->status;
                    } else {
                        $status = 3;
                    }
                    return view('sellerdashboard.jobs_view', compact('job_preview', 'post_company', 'status', 'expert_job_interest'));
                } else {
                    return view('errors.404');
                }
            } else {
                return view('errors.404');
            }
        } else {
            return redirect('/');
        }
    }

    /**
     * Show Interest Status Method
     * 
     * @param type $user_id
     * @param type $buyer_id
     * @param type $job_id
     * 
     * @return string
     */
    public function showInterestStatus($user_id, $buyer_id, $job_id) {
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
}
