<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EducationDetail extends Model {

    /**
     * User Method
     * 
     * @return type
     */
    public function user() {
        return $this->hasOne(User::class);
    }
    public static function deleteEducationDetail($id){       
        return EducationDetail::where('id',$id)->delete();
    }

}
