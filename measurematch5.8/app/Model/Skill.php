<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Skill extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'skills';
    protected $fillable = [
        'name','skill_type' ,'created_at','depricated','is_tool'
    ];/**
 * The attributes that should be hidden for arrays.
 *
 * @var array
 */
    public static function getSkills(){
        return Skill::whereIn('name', ['Google Analytics', 'Tag Management', 'DMPs', 'Python', 'Email Marketing Automation', 'Surveys'])->get()->toArray();
    }

    public function userSkill()
    {
        return $this->hasMany('App\Model\UsersSkill');
    }

    public function jobSkill()
    {
        return $this->hasMany('App\Model\JobsSkill');
    }

    public static function getSimilarSkills($skills, $is_tool = FALSE){
        $query = Skill::where('name', 'iLIKE', trim($skills));
        if($is_tool){
            $query->where('is_tool', 'TRUE');
        }
        $skill = $query->get()->toArray();
        return $skill;
    }
    public static function insertSkillId($all_skills,$created,$skill_type,$is_tool=FALSE){
        $data_to_insert = ['name' => ucwords(trim($all_skills)), 'created_at' => $created, 'skill_type' => $skill_type];
        if($is_tool){
            $data_to_insert['is_tool'] = 'TRUE';
        }
        return Skill::insertGetId($data_to_insert);
    }
    public static function getRandomSkills(){
        return Skill::where('skill_type', null)->inRandomOrder()->take(10)->get();
    }

    public static function searchSkills($skill){
        return Skill::where('name', 'iLIKE', trim($skill));
    }
    public static function insertSkills($skill,$skill_type){
        return Skill::insertGetId(array('name' => ucwords(trim($skill)),'skill_type' => $skill_type));
    }
    public static function updateSkills($skill_id,$data_to_update){
        return Skill::where('id',$skill_id)->update($data_to_update);
    }
    public static function getToolsAndSkills($search,$is_tool=False){
        return Skill::where('name', 'iLIKE', '%' . $search . '%')
            ->where(['depricated'=> false, 'is_tool'=>$is_tool])
            ->where(function($q){
                $q->where('skill_type', '')
                    ->orWhere('skill_type', null);
            })
            ->orderBy('name', 'asc')->pluck('name')->all();
    }

    public function fetchSkillsQuery($skill, $priority)
    {
        $found_alias = $this->getSkillByAlias($skill, $priority);
        $alias_object = $found_alias->get()->first();
        if ($alias_object){
            return $found_alias;
        }

        $minimum_skill_chunk_to_match = minimumMatchingSkillChunk($skill);
        $raw_skill = Skill::selectRaw("skills.id, skills.name, $priority as priority, '$skill' as skill_name")
            ->where('skills.name', 'iLike', $minimum_skill_chunk_to_match.'%');
        $raw_object = $raw_skill->get()->first();
        return $raw_skill;
    }

    public function getExpertSkills($skill_name) {
        return $this->select(
            'id',
            'skills.name',
            'logo_url'
        )
            ->where('name', '!=', '')
            ->where('name', 'ilike', '%'.$skill_name.'%')
            ->orderBy('name', 'asc')
            ->paginate(config('constants.PAGINATION_LIMIT'));
    }
    
    public function skillLogos($skills)
    {
        return Skill::select('id', 'name', 'logo_url')->whereIn(DB::raw('lower(name)'), $skills)->get()->toArray();
    }

    public static function updateAlias($id, $alias){
        $new_alias = $alias;
        $skill = Skill::find($id);
        if ($skill->alias){
            $aliases = explode(',', $skill->alias);
            foreach ($aliases as $single_alias){
                if ($single_alias == $alias){
                    return;
                }
            }
            $add_new_alias = $skill->alias . ',' . $alias;
            $skill->alias = $add_new_alias;
            $skill->save();
            return;
        }
        $skill->alias = $new_alias;
        $skill->save();
    }

    public static function getSkillByAlias($alias, $priority = 1){

        return Skill::selectRaw("skills.id, skills.name, $priority as priority, '$alias' as skill_name")
            ->where('alias', 'iLike', '%'.$alias.'%');
    }
}
