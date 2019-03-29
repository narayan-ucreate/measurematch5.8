<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TypeOfOrganization extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'type_of_organizations';
    protected $fillable = [
        'name', 'created_at'
    ];/**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public static function listAll(){
        return TypeOfOrganization::where('depricated', False)->orderBy('name', 'asc')->pluck('name', 'id')->all();
    }
    
    public static function getTypeOfOrganizationByName($type_of_organization){
        return TypeOfOrganization::where('name', 'ILIKE', $type_of_organization)->where('depricated', False)->orderBy('name', 'asc')->select('id')->get();
    }
    
    public static function getLikeTypeOfOrganization($condition_array){
        $result = TypeOfOrganization::where('depricated', False);
        foreach ($condition_array as $key => $value) {
            $result->where($key, 'ilike', '%'.$value.'%');
        }
        $response = $result->orderBy('name', 'asc')->pluck('name', 'id')->all();
        return $response;
    }
    public static function getTypeOfOrganizationNameByID($id){
        return TypeOfOrganization::where('id',$id)->where('depricated', False)->pluck('name')[0];
    }
}
