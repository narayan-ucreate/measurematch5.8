<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class ServicePackageType extends Model
{
    protected $table = 'service_package_types';
    protected $fillable = [
        'name','added_by'
    ];
    
    public function servicePackage(){
        return $this->hasMany('App\Model\ServicePackage', 'service_package_type_id');
    }
    
    public static function listTypes($condition){
        return self::where($condition)->orderBy('name', 'ASC')->pluck('name')->all();
    }
    
    public static function listNameId($condition, $query_options = []){
        $query = self::orderBy('name')->where($condition);
        if(_count($query_options) && array_key_exists('related_model', $query_options)){
           $result = $query->has('servicePackage');
        }
        $result = $query->pluck('name', 'id')->all();
        return $result;
    }
    
    public static function getSimilarTypes($type) {
        return self::where('name', 'iLIKE', trim($type))->first();
    }
    
    public static function updateWhereIn($field, $matching, $update_data){
        return self::whereIn($field, $matching)->update($update_data);
    }
    
    public static function fetchWithServicePackageCount($condition_array){
        return self::select(DB::raw('service_package_types.name, service_package_types.id, COUNT(service_package_type_id) AS count'))
                ->leftJoin('service_packages', function($join)
                         {
                             $join->on('service_packages.service_package_type_id', '=', 'service_package_types.id');
                             $join->where('service_packages.is_approved', '=', 'TRUE');
                             $join->where('service_packages.is_hidden', '=', 'FALSE');
                             $join->whereNull('service_packages.deleted_at');
                         })
                    ->where($condition_array)
                    ->groupBy('service_package_types.id')
                    ->orderBy('service_package_types.name', 'ASC')
                    ->get()
                    ->toArray();
    }
}
