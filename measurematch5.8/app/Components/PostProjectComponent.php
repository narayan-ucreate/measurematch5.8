<?php

namespace App\Components;

use Auth;
use Exception;
use Validator;
use App\Model\JobsSkill;
use App\Model\Deliverable;
use App\Model\Skill;
use Carbon\Carbon;

Class PostProjectComponent {    
    public static function saveSkills($post_form_data, $project_id, $is_edit){
        $skills = [];
        $tools = [];
        if (!empty($post_form_data['skills'])) {
            $skills = explode(',', $post_form_data['skills']);
            if($is_edit){
                $skills =  self::removeDeletedSkills($skills, $project_id, FALSE);
            }
        }else{
            $skills = self::removeDeletedSkills([], $project_id, FALSE);
        }
        if (_count($skills)) {
           self::addSkillsAndTools($skills, $project_id);
        }
         
        if (!empty($post_form_data['tools'])) {
            $tools = explode(',', $post_form_data['tools']);
            if($is_edit){
                $tools =  self::removeDeletedSkills($tools, $project_id, TRUE);
            }
        }else{
            $tools = self::removeDeletedSkills([], $project_id, TRUE);
        }
        if (_count($tools)) {
            self::addSkillsAndTools($tools, $project_id, TRUE);
        }
        return true;
    }
    private static function removeDeletedSkills($skills, $project_id, $is_tool){
        if($is_tool){
            $already_existing_skills = JobsSkill::getProjectToolsByProjectId($project_id);
        } else {
            $already_existing_skills = JobsSkill::getProjectSkillsByProjectId($project_id);
        }
        
        foreach($already_existing_skills as $old_skills){
            if(!in_array(trim(strtolower($old_skills->name)), $skills)){
               JobsSkill::deleteJobSkillById($old_skills->id);
            }else{
                unset($skills[array_search ($old_skills->name, $skills)]);
            }
        }
        return $skills;
    }
    private static function addSkillsAndTools($all_skills, $job_id, $is_tool = FALSE) {
        foreach ($all_skills as $i => $skill) {
            $check_skill = Skill::getSimilarSkills($skill, $is_tool);
            if (_count($check_skill) && isset($check_skill[0]['id'])) {
                $skill_id = $check_skill[0]['id'];
                if (!JobsSkill::getSkillJob($skill_id, $job_id)->exists()) {
                    self::addJobSkillToDatabase($skill_id, $job_id);
                }
            } else {
                if (trim($skill) == '') {
                    unset($skill);
                } else {
                    if (!empty(trim($skill))) {
                        $skill_id = Skill::insertSkillId(trim($skill), Carbon::now(), 'job', $is_tool);
                        self::addJobSkillToDatabase($skill_id, $job_id);
                    }
                }
            }
        }
    }

    private static function addJobSkillToDatabase($skill_id, $job_id){
        $data = ['skill_id' => $skill_id,
                'job_post_id' => $job_id];
        JobsSkill::create($data);
    }
    
    public static function saveDeliverables($project_id, $deliverables,$type,$is_edit) {
        if($is_edit){
             Deliverable::deleteDeliverableByProjectId($project_id);
       }
        if (_count($deliverables) && !empty(array_filter($deliverables))) {
            foreach ($deliverables as $deliverable) {
                if ($deliverable) {
                    $deliverable_data = ['post_job_id' => $project_id, 'type' => $type, 'deliverable' => stripScriptingTagsInline($deliverable)];
                    $save_deliverable = new Deliverable($deliverable_data);
                    $save_deliverable->save();
                }
            }
        }
    }
}
