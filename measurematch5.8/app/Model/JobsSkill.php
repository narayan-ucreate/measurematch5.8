<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB; 

class JobsSkill extends Model {

    protected $table = 'jobs_skills';
    public $timestamps = true;
    protected $fillable = [
        'skill_id', 'job_post_id', 'created_at'
    ];

    /**
     * Skill Method
     * 
     * @return type
     */
    public function skill() {
        return $this->belongsTo('App\Model\Skill', 'skill_id');
    }
    public static function getSkillJob($skill_id,$job_id){
        return JobsSkill::where('skill_id', $skill_id)->where('job_post_id', $job_id);
    }
    public static function deleteSkillJob($job_id){
        return JobsSkill::where('job_post_id', $job_id)->delete();
    }
    public static function getskillsWithJobPostId($job_post_id){
        return JobsSkill::where('job_post_id', $job_post_id)->with('skill')->get()->toArray();
    }
    public static function skillsExist($skill_id,$job_id){
        return JobsSkill::where('skill_id', $skill_id)->where('job_post_id', $job_id)->exists();
    }
    public static function getProjectToolsByProjectId($project_id){
        return DB::table('jobs_skills')->join('skills', 'jobs_skills.skill_id', '=', 'skills.id')
               ->where('jobs_skills.job_post_id', $project_id)
               ->where('skills.is_tool', true)->get();
    }
    public static function getProjectSkillsByProjectId($project_id){
        $query= DB::table('jobs_skills')->join('skills', 'jobs_skills.skill_id', '=', 'skills.id')
               ->where('jobs_skills.job_post_id', $project_id)
               ->where('skills.is_tool', false)->get();
        return $query; 
    }
    
    public function getProjectSkillsAndToolsByProjectId($project_id){
        $query= $this->join('skills', 'jobs_skills.skill_id', '=', 'skills.id')
               ->where('jobs_skills.job_post_id', $project_id)
               ->orderBy('skills.is_tool','desc')->get();
        return $query; 
    }
    
    
    public static function deleteJobSkillById($skill_id){
        return JobsSkill::where('skill_id', $skill_id)->delete();
    }
}
