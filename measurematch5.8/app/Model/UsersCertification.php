<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UsersCertification extends Model {

    /**
     * User Method
     * 
     * @return type
     */
    public function user() {
        return $this->hasOne(User::class);
    }
    public static function deleteCertificateAndCourses($id){
        return UsersCertification::where('id',$id)->delete();
    }

}
