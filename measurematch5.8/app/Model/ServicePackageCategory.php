<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServicePackageCategory extends Model {

    protected $table = 'service_package_categories';
    protected $fillable = [
        'service_package_id','category_id'
    ];
    
    public function servicePackage() {
    return $this->belongsTo('App\Model\ServicePackage','service_package_id');    
    }
    
    public function categories() {
    return $this->belongsTo('App\Model\Category','category_id');    
    }
    
    public static function getServicePackageCategories($service_package_id) {
        return self::where('service_package_id',$service_package_id)->get();
    }

}
