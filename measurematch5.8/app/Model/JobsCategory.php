<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JobsCategory extends Model {

    protected $table = 'jobs_categories';
    public $timestamps = true;
    protected $fillable = [
        'category_id', 'job_post_id', 'created_at'
    ];

    /**
     * Category Method
     * 
     * @return type
     */
    public function category() {
        return $this->belongsTo('App\Model\Category', 'category_id');
    }

}
