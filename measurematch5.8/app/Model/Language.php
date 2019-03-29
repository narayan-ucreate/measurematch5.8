<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Language extends Model {

    protected $table = 'languages';

    public static function getLanguageWithName($language_name) {
        return Language::where('name', 'iLIKE', trim($language_name))->get()->toArray();
    }
    
    public static function getAllLanguages() {
        return self::get()->toArray();
    }

}
