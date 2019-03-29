<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Deliverable extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'deliverables';
    protected $fillable = ['service_package_id','type', 'deliverable','contract_id','post_job_id', 'title', 'rate', 'rate_unit', 'quantity'];

 
    public function servicePackage() {
        return $this->belongsTo('App\Model\ServicePackage', 'service_package_id', 'id');
    }
   
    public static function findByCondtion($where){
        return Deliverable:: where($where)->get();
    }
    public static function getDeliverablesByServicePackage($service_package_id, $type='service_package') {
        return self::where(['service_package_id' => $service_package_id,'type'=>$type])->get();
    }
    public static function deleteDeliverables($service_package_id,$type='service_package') {
        return self::where(['service_package_id' => $service_package_id,'type'=>$type])->delete();
    }
    public static function deleteContractDeliverables($contract_id,$type='contract') {
        return self::where(['contract_id' => $contract_id,'type'=>$type])->delete();
    }
    public static function deleteDeliverableByProjectId($project_id){
        return self::where('post_job_id', $project_id)->delete();
    }
    
    public function getProjectDeliverables($post_job_id) {
        return $this->wherePostJobId($post_job_id)->get(['deliverable']);
    }

    public function deleteDeliverablesByContractId($contract_id) {
        $this->where('contract_id',$contract_id)->delete();
    }

}
