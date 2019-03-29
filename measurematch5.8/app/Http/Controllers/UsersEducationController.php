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
use App\Model\Skill;
use App\Model\UsersSkill;
use App\Model\UsersCourse;

class UsersEducationController extends Controller {

    /**
     * Construct Method
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Add Education Method
     * 
     * @param Request $request
     * 
     * @return type
     */
    public function addEducation(Request $request) {
        $id = Auth::user()->id;
        $form_data = $request->all();
        $messages = [
            'eduname.required' => 'Please enter coursename',
            'university.required' => 'Please enter university'];
        
        $validator = Validator::make($form_data, [
                'eduname' => 'required',
                'university' => 'required',
                 ], $messages);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->errors())->withInput();
        } else {
            $user_education = new EducationDetail;
            $user_education->user_id = $id;
            $user_education->field_of_study = stripScriptingTagsInline($form_data['eduname']);
            $user_education->name = stripScriptingTagsInline($form_data['university']);

            if (!empty($form_data['startMonth']) && !empty($form_data['startYear'])) {
                $user_education->start_date = date('Y-m-d', strtotime('01-' . $form_data['startMonth'] . '-' . $form_data['startYear']));
            }
            if (!empty($form_data['endMonth']) && !empty($form_data['endYear'])) {
                $user_education->end_date = date('Y-m-d', strtotime('01-' . $form_data['endMonth'] . '-' . $form_data['endYear']));
            }
            
            $user_education->save();
            if ($user_education->save()) {
                return Redirect::To('expert/profile-summary')->with('warning', 'Account updated.');
            } else {
                return Redirect::To('expert/profile-summary')->with('warning', 'Account has not updated.');
            }
        }
    }

    /**
     * Edit Education Method
     * 
     * @param Request $request
     * 
     * @return string
     */
    public function editEducation(Request $request) {
        $form_data = $request->all();
        $rules = array(
            'eeduname' => 'required',
            'euniversity' => 'required',
        );
        $validator = Validator::make($form_data, $rules);
        if ($validator->fails()) {
            $error = $validator->errors()->toArray();

            if (isset($error['eeduname']['0']) && !empty($error['eeduname']['0'])) {
                echo $error['eeduname']['0'];
                return 'E_COURSE';
            }
            if (isset($error['euniversity']['0']) && !empty($error['euniversity']['0'])) {
                return 'E_UNIVERSITY';
            }
        } else {
            $education_detail = EducationDetail::find(trim($form_data['eduid']));
            $education_detail->name = stripScriptingTagsInline($form_data['euniversity']);
            $education_detail->field_of_study = trim($form_data['eeduname']);
            if (!empty($form_data['startMonth']) && !empty($form_data['startYear'])) {
                $education_detail->start_date = date('Y-m-d', strtotime('01-' . $form_data['startMonth'] . '-' . $form_data['startYear']));
            }else{
                $education_detail->start_date = NULL;
            }
            if (!empty($form_data['endMonth']) && !empty($form_data['endYear'])) {
                $education_detail->end_date = date('Y-m-d', strtotime('01-' . $form_data['endMonth'] . '-' . $form_data['endYear']));
            }else{
                $education_detail->end_date = NULL;
            }
           
            if ($education_detail->save()) {
                return Redirect::To('expert/profile-summary')->with('warning', 'Account updated.');
            }
        }
    }
}
