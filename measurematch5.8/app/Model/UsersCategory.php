<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UsersCategory extends Model {

    protected $table = 'users_categories';
    public $timestamps = true;
    protected $fillable = [
        'category_id', 'user_id', 'created_at'
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
