<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Draft extends Model {

    protected $table = 'drafts';
    public $timestamps = true;
    protected $fillable = [
        'job_post_id', 'created_at'
    ];

    /**
     * Post Method
     * 
     * @return type
     */
    public function post() {
        return $this->belongsTo('App\Model\PostJob', 'job_post_id');
    }

}
