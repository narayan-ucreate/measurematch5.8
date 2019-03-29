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
use App\Model\EmploymentDetail;
use App\Model\Skill;
use App\Model\UsersSkill;
use App\Model\EducationDetail;
use App\Model\UsersCourse;

class UsersCourseController extends Controller {

    /**
     * Construct Method
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Add Course Method
     * 
     * @param Request $request
     * 
     * @return type
     */
    public function addcourse(Request $request) {
        $id = Auth::user()->id;
        $course_form_data = $request->all();
        $messages = [
            'coursename.required' => 'Please enter course name',
            'institute.required' => 'Please enter institute'];
        
        $validator = Validator::make($course_form_data, [
                'coursename' => 'required',
                'institute' => 'required',
                 ], $messages);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->errors())->withInput();
        } else {
            $course_name = trim($course_form_data['coursename']);
            $user_courses = new UsersCertification;

            $user_courses->user_id = $id;
            $user_courses->name = stripScriptingTagsInline($course_name);
            $user_courses->institute = stripScriptingTagsInline($course_form_data['institute']);
            if (!empty($course_form_data['startMonth']) && !empty($course_form_data['startYear'])) {
                $user_courses->start_date = date('Y-m-d', strtotime('01-' . $course_form_data['startMonth'] . '-' . $course_form_data['startYear']));
            }

            if (!empty($user_courses['institute'])) {
                $user_courses->institute = stripScriptingTagsInline($course_form_data['institute']);
            }
            $user_courses->save();
          
            if ($user_courses->save()) {
                return Redirect::To('expert/profile-summary')->with('warning', 'Account updated.');
        
            } else {
                return Redirect::To('expert/profile-summary')->with('warning', 'Account has not updated.');
            }
        }
    }

    /**
     * Edit Course Method
     * 
     * @param Request $request
     * 
     * @return string
     */
    public function editcourse(Request $request) {
        $edit_course_form = $request->all();
        $rules = array(
            'ecoursename' => 'required',
            'einstitute' => 'required',
        );
        $validator = Validator::make($edit_course_form, $rules);
        if ($validator->fails()) {
            $error = $validator->errors()->toArray();
            if ($error['ecoursename']['0']) {
                echo $error['ecoursename']['0'];
                return 'E_COURSE';
            }
            if ($error['einstitute']['0']) {
                return 'E_INSTITUTE';
            }
        } else {
            $course_detail = UsersCertification::find(trim($edit_course_form['courseid']));
            $course_detail->name = stripScriptingTagsInline($edit_course_form['ecoursename']);
            $course_detail->institute = stripScriptingTagsInline($edit_course_form['einstitute']);

            if (!empty($edit_course_form['etotime'])) {
                $course_detail->end_date = date('Y-m-d', strtotime($edit_course_form['etotime']));
            }
            if (!empty($edit_course_form['startMonth']) && !empty($edit_course_form['startYear'])) {
                $course_detail->start_date = date('Y-m-d', strtotime('01-' . $edit_course_form['startMonth'] . '-' . $edit_course_form['startYear']));
            }else{
                $course_detail->start_date = NULL;
            }
            if ($course_detail->save()) {
                return Redirect::To('expert/profile-summary')->with('warning', 'Account updated.');
            }
        }
    }
}
