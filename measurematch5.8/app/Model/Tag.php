<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

    protected $table = 'tags';
    protected $fillable = [
        'name','tag_type','created_at'
    ];
    
    public static function geAlltags() {
        return self::get()->toArray();
    }
 
    public static function serachTagsByName($keyword) {
        return self::where('name', 'iLIKE', '%' . $keyword . '%')->where('tag_type', null)->orderBy('name', 'asc')->pluck('name')->all();
    }
    public static function getSimilarTags($tag) {
        return self::where('name', 'iLIKE', trim($tag))->first();
    }
    public static function getTagsById($id) {
        return self::where('id', $id)->first()->name;
    }

    public function fetchTagQuery($skill, $priority)
    {
        $minimum_skill_chunk_to_match = minimumMatchingSkillChunk($skill);
        return Tag::selectRaw("tags.id, tags.name, $priority as priority, '$skill' as skill_name")
            ->where('tags.name', 'iLike', $minimum_skill_chunk_to_match.'%');
    }
}
