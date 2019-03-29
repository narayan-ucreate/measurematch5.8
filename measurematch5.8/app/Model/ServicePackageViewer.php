<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServicePackageViewer extends Model {

    protected $table = 'service_package_viewers';
    public $timestamps = true;
    protected $fillable = [
        'user_id','service_package_id'
    ];
        
    public static function getCount($service_package_id) {
        return self::where('service_package_id', '=', $service_package_id)->count();
    }
   public static function getServicePackageViewersStatus($user_id,$service_package_id) {
        return self::where('user_id', '=', $user_id)->where('service_package_id', '=', $service_package_id)->count();
    }

}
