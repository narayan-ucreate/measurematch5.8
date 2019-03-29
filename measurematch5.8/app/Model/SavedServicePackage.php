<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use App\Model\SavedServicePackage;

class SavedServicePackage extends Model {

    protected $table = 'saved_service_packages';
    protected $fillable = [
        'service_package_id', 'buyer_id'
    ];
    public static function deleteSavedServicePackage($id){
     return self::where('id',$id)->delete();   
    }
    public static function getSavedServicePackage($condition){
     return self::where($condition)->get()->toArray();
    }
    public static function savedPackageList($buyer_id){
        return self::where('buyer_id',$buyer_id)->pluck('service_package_id', 'id')->all();
    }
}
