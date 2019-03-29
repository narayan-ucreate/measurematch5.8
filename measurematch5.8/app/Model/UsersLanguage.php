<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UsersLanguage extends Model {

    protected $fillable = [
        'language_id', 'user_id',
    ];

    /**
     * User Method
     * 
     * @return type
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Language Method
     * 
     * @return type
     */
    public function language() {
        return $this->belongsTo('App\Model\Language', 'language_id');
    }
    public static function getUsersLanguage($id,$pagination){
        return UsersLanguage::where('user_id', $id)->with('language')->skip(0)->take($pagination)->get();
    }
    public static function getUsersLanguagewithLanguageId($existed_language_id,$user_id){
        return UsersLanguage::where('language_id', '=', $existed_language_id)->where('user_id', '=', $user_id)->get()->toArray();
    }
    public static function getUsersLanguagewithLanguageProficiency($existed_language_id,$proficiency_language ,$user_id){
        return UsersLanguage::where('language_id', '=', $existed_language_id)->where('language_proficiency', '=', $proficiency_language)->where('user_id', '=', $user_id)->get()->toArray();
    }
}
