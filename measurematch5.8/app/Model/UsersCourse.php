<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UsersCourse extends Model {

    /**
     * User Method
     * 
     * @return type
     */
    public function user() {
        return $this->hasOne(User::class);
    }

}
