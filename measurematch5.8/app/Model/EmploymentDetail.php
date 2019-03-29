<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EmploymentDetail extends Model {

    protected $table = 'employment_details';

    /**
     * User Method
     * 
     * @return type
     */
    public function user() {
        return $this->hasOne(User::class);
    }
    public static function getEmployementDetailWithId($id,$paging){
        return EmploymentDetail::where('user_id', $id)->skip(0)->take($paging)->get();
    }
    public static function getTotalEmployement(){
        return EmploymentDetail::where('user_id', $id)->get();
    }
    public static function deleteEmployementDetail($id){
        return  EmploymentDetail::where('id',$id)->delete();
    }

}
