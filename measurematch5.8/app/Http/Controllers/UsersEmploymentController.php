<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Authenticatable,
    CanResetPassword;
use App\Http\Requests;
use Auth;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Redirect;
use App\Model\User;
use Session;
use Validator;
use DB;
use App\Model\Language;
use App\Model\UserType;
use App\Model\UserProfile;
use App\Model\UsersLanguage;
use App\Model\UsersCertification;
use App\Model\EducationDetail;
use App\Model\EmploymentDetail;
use App\Model\UsersSkill;
use Carbon\Carbon;

class UsersEmploymentController extends Controller {

    /**
     * Construct Method
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Add Employment Method
     * 
     * @param Request $request
     * 
     * @return type
     */
    public function addEmploymentDetail(Request $request) {
        $form_data = $request->all();
        $rules = array(
            'employee_title' => 'required',
            'employee_company' => 'required',
        );
        $validator = Validator::make($form_data, $rules);
        if ($validator->fails()) {
            $error = $validator->errors()->toArray();
            return $error;
        } else {
             $employment_detail_exists=0;
            if (EmploymentDetail::where('user_id', Auth::user()->id)->count() > 0) {
               $employment_detail_exists = 1;
             }
            $employment_detail = new EmploymentDetail;
            $employment_detail->user_id = Auth::user()->id;
            $employment_detail->position_title = stripScriptingTagsInline($form_data['employee_title']);
            $employment_detail->company_name = stripScriptingTagsInline($form_data['employee_company']);
            if (!empty($form_data['startMonth']) && !empty($form_data['startYear'])) {
                $employment_detail->start_date = date('Y-m-d', strtotime('01-' . $form_data['startMonth'] . '-' . $form_data['startYear']));
            }
            $employment_detail->summary = stripScriptingTagsInline($form_data['eempdescription']);
            $employment_detail->location = stripScriptingTagsInline($form_data['eemplocation']);

            if ($form_data['is_current'] == 1) {
                $employment_detail->is_current = ((isset($form_data['is_current']) && !empty($form_data['is_current'])) ? TRUE : FALSE);
                $date = Carbon::now();
                $enddate = explode(' ', $date);
                $employment_detail->end_date = $enddate[0];
            } else {
                $employment_detail->is_current = FALSE;
                if (!empty($form_data['endMonth']) && !empty($form_data['endYear'])) {
                    $employment_detail->end_date = date('Y-m-d', strtotime('01-' . $form_data['endMonth'] . '-' . $form_data['endYear']));
                }
            }
            $employment_detail->save();
            if ($employment_detail->save()) {
                  $basic_profile_completeness = UserProfile::profileDetail(Auth::user()->id);
                  if($basic_profile_completeness['completed_required_seven_experts_fields']==config('constants.COMPLETED') && ($employment_detail_exists !=1) && Auth::user()->admin_approval_status == 1){
                  return Redirect::To('expert/projects-search')->with('success', 'Now that your profile has been completed, search for a Project.');
                  }
                return Redirect::To('expert/profile-summary')->with('warning', 'Account updated.');
            } else {
                return Redirect::To('expert/profile-summary')->with('warning', 'Account has not updated.');
            }
        }
    }

    public function editEmploymentDetail(Request $request) {
        $form_data = $request->all();
        $rules = array(
            'employee_title' => 'required',
            'empcompany' => 'required',
        );
        $validator = Validator::make($form_data, $rules);
        if ($validator->fails()) {
            $error = $validator->errors()->toArray();
            return $error;
        } else {

            $employment_detail = EmploymentDetail::find(trim($form_data['empid']));

            $employment_detail->position_title = stripScriptingTagsInline($form_data['employee_title']);
            $employment_detail->company_name = stripScriptingTagsInline($form_data['empcompany']);
            
            if (!empty($form_data['startMonth']) && !empty($form_data['startYear'])) {
                $employment_detail->start_date = date('Y-m-d', strtotime('01-' . $form_data['startMonth'] . '-' . $form_data['startYear']));
            }else{
                $employment_detail->start_date = NULL;
            }

            $employment_detail->summary = stripScriptingTagsInline($form_data['empdescription']);
            $employment_detail->location = stripScriptingTagsInline($form_data['emplocation']);

            if ($form_data['hidden_eis_current'] == 1) {
                $employment_detail->is_current = ((isset($form_data['hidden_eis_current']) && !empty($form_data['hidden_eis_current'])) ? TRUE : FALSE);
                $date = Carbon::now();
                $enddate = explode(' ', $date);
                $currentmonth = explode('-', $enddate[0]);

                $employment_detail->end_date = date('Y-m-d', strtotime('01-' . $currentmonth[1] . '-' . $currentmonth[0]));
                ;
            } else {
                if(!empty($form_data['endMonth']) && !empty($form_data['endYear'])){
                    $employment_detail->is_current = FALSE;
                    $employment_detail->end_date = date('Y-m-d', strtotime('01-' . $form_data['endMonth'] . '-' . $form_data['endYear']));
                }else{
                    $employment_detail->is_current = FALSE;
                    $employment_detail->end_date = NULL;
                }
            }

            if ($employment_detail->save()) {
                return Redirect::To('expert/profile-summary')->with('warning', 'Account updated.');
            }
        }
    }
}
