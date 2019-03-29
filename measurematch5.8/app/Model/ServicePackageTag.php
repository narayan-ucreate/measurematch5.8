<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServicePackageTag extends Model {

    protected $table = 'service_package_tags';
    protected $fillable = [
        'service_package_id','tag_id'
    ];
    
    public function servicePackage() {
    return $this->belongsTo('App\Model\ServicePackage','service_package_id');    
    }
    public function Tags() {
    return $this->belongsTo('App\Model\Tag','tag_id');    
    }
    
    public static function getServicePackageTags($service_package_id) {
        return self::where('service_package_id',$service_package_id)->get();
    }
    public static function deleteServicePackageTag($service_package_id){
        return self::where('service_package_id',$service_package_id)->delete();
    }

}
