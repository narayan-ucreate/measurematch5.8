<?php

namespace App\Http\Controllers;

use Postmark\PostmarkClient;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Skill;
use Carbon\Carbon;
use Auth;
use DB;
use App\Model\UsersSkill;
use App\Model\User;
use App\Model\UserProfile;
use App\Model\UsersLanguage;
use App\Model\UsersCertification;
use App\Model\EducationDetail;
use App\Model\EmploymentDetail;
use App\Model\TypeOfOrganization;
use Redirect;

class UsersSkillController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['except' => ['updateSkills', 'skillsAutocomplete', 'toolsAutocomplete']]);
    }
    public function saveskills(Request $request) {
        $user_id = Auth::user()->id;
        $input = $request->all();
       
        if($input['is_add_tool_form']==1){
             $add_skill=$input['add_tool'];
             $manually_added_skills=$input['add_tools_display'];
             $is_tool=True;
        }else{
            $add_skill=$input['add_skill'];
            $manually_added_skills=$input['add_skills_display'];
            $is_tool=False;
        }
        if (empty($add_skill)) {
            $add_skill= $manually_added_skills;
        }
        $skill_and_tool_exists = 0;
        if (_count(getExpertTools($user_id)) >= 3 && _count(getExpertSkills($user_id)) >= 3) {
            $skill_and_tool_exists = 1;
        }
        if (!empty($add_skill)) {
            $skills = rtrim($add_skill, ", \t\n");
            $exlode_skills = explode(',', $skills);

            if (!empty($skills)) {
                $skill_count =sizeof($exlode_skills);
                for ($i = 0; $i < $skill_count; $i++) {
                    $check_skill = Skill::searchSkills(trim($exlode_skills[$i]))->get()->toArray();
                    if (!empty($check_skill)) {
                        $skill_id = $check_skill[0]['id'];
                        if (UsersSkill::getUserSkillsWithSkillIdAndUserId($skill_id, $user_id)) {
                            $final_skill_id = "";
                        } else {
                            $skill_insert = UsersSkill::create([ 'skill_id' => $skill_id, 'user_id' => $user_id]);
                            $final_skill_id[$i] = $skill_id;
                        }
                    } else {
                        if (trim($exlode_skills[$i]) == '') {
                            unset($exlode_skills[$i]);
                        } else {
                            $skill = Skill::create(['name' => trim($exlode_skills[$i]),'skill_type' => 'user','depricated' => false,'is_tool' => $is_tool]);
                            $skill_insert = UsersSkill::create([ 'skill_id' => $skill->id, 'user_id' => $user_id]);
                            $final_skill_id[$i] = $skill->id;
                        }
                    }
                }
            }

            $basic_profile_completeness = UserProfile::profileDetail(Auth::user()->id);
            if (empty($basic_profile_completeness['expert_profile_review_email_to_admin_date']) || $basic_profile_completeness['expert_profile_review_email_to_admin_date'] == NULL) {
                sendEmailToAdminIfBasicProfileIsCompleted();
            }
            if ($skill_and_tool_exists == 0 && $basic_profile_completeness['completed_required_seven_experts_fields'] == config('constants.COMPLETED') && Auth::user()->admin_approval_status == 1) {
                return Redirect::To('expert/projects-search')->with('success', 'Now that your profile has been completed, search for a Project.');
            }
        }
        return Redirect::To('expert/profile-skills')->with('warning', 'New Skills has been added.');
    }

    public function deleteskill(Request $request) {
        $user_id = Auth::user()->id;
        $input = $request->all();
        $id = $input['id'];
        if (!is_numeric($id)) {
            return '0';
        }
        if (!empty($id)) {
            UsersSkill::deleteSkills($user_id, $id);
        }
        return '1';
    }
    
    public function scriptToUpdateSkills(Request $request) {
        if (getenv('ENVIRONMENT') != 'Production') {
            $import_skills = file_get_contents(url('expert_skills_data.csv'));
            if (!empty($import_skills)) {
                $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $import_skills));
                foreach ($data as $skill) {
                    $check_skill = Skill::searchSkills(trim($skill[0]))->get()->toArray();
                    if (empty($check_skill)) {
                        $type = (isset($skill[5])) ? $skill[5] : null;
                        $skill_id = Skill::insertSkills(trim($skill[0]), $type);
                        print_r($skill_id);
                    }
                }
            }
        }
    }

    public function scriptToAddNewSkills() {
        $file_name = 'new_skills.csv';
        try{
            $import_skills = file_get_contents(url($file_name));
        } catch (\Exception $e){
            echo $file_name . ' not found';
        }
        if (!empty($import_skills)) {
            $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $import_skills));
            $count_before_update = Skill::all()->count();
            $new_count = 0;
            foreach ($data as $skill) {
                if (isset($skill[0])){
                    $name = $skill[0];
                    if (Skill::searchSkills($name)->count() === 0){
                        $new_skill = new Skill;
                        $new_skill->name = $name;
                        $new_skill->depricated = false;
                        $new_skill->is_tool = true;
                        if ($new_skill->save()){
                            $new_count++;
                        }
                    }
                }
            }
            $doubles = sizeof($data) - $new_count;
            print_r('Number of skills before update: ' . $count_before_update);
            print_r('<br>');
            print_r('Input list size: ' . sizeof($data));
            print_r('<br>');
            print_r('Number of skills already in the database: ' . $doubles);
            print_r('<br>');
            print_r('Total skills added to the database: ' . $new_count);
        }
    }

    public function skillsNamesCorrectionScript(Request $request) {
        $all_skills = Skill::get();
        if (!empty($all_skills)) {
            foreach ($all_skills as $skill) {
               $multiple_skills = Skill::getSimilarSkills($skill->name);
                if(_count($multiple_skills)>1){ 
                    foreach ($multiple_skills as $value) {
                    echo"<br/>multiple: <pre>"; print_r($value['id']);
                    }  
                }else{
                  $update_skill= Skill::updateSkills($skill->id,['name'=> ucwords(trim($skill->name))]);
                   echo"<pre>"; print_r($skill->id)."<br/>";
                }
            }
        }
    }
    public function skillsIsToolStatusUpdationScript(Request $request) {
       $all_skills = Skill::get();
       $import_skills = file_get_contents(url('expert_skills_data.csv'));
       if (!empty($import_skills)) {
           $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $import_skills));
           foreach ($data as $skill) {
               $check_skill = Skill::getSimilarSkills(trim($skill[0]));
               if (!empty($check_skill)) {
                   if (_count($check_skill) > 1) {
                       foreach ($check_skill as $value) {
                           $update_skill = Skill::updateSkills($value['id'], ['is_tool' => (($skill[1] == 1) ? TRUE : FALSE)]);
                           echo"<br/>multiple: <pre>";
                           print_r($value['id']);
                       }
                   } else {
                       if(array_key_exists(1, $skill)){
                           $update_skill = Skill::updateSkills($check_skill[0]['id'], ['is_tool' => (($skill[1] == 1) ? TRUE : FALSE)]);
                           echo"<pre>";
                            print_r($check_skill[0]['id']) . "<br/>";
                       }
                   }
               }
           }
       }
   }
    
    public function skillsAutocomplete(Request $request) {
        return Skill::getToolsAndSkills($request['term'], false);
    }
    public function toolsAutocomplete(Request $request) {
        return Skill::getToolsAndSkills($request['term'],True);
    }

}
