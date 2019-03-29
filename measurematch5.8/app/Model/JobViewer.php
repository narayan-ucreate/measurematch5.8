<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JobViewer extends Model {

    protected $table = 'job_viewers';
    public $timestamps = true;
    protected $fillable = [
        'expert_id','job_posted_id','created_at'
    ];
    
    public static function deleteJobViewer($job_id){
        return self::where('job_posted_id', $job_id)->delete();
    }

}
