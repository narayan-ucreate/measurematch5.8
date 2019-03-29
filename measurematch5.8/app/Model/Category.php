<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    protected $table = 'categories';
    protected $fillable = [
        'name', 'created_at'
    ];
    
    public static function getAllCategories() {
        return self::get()->toArray();
    }

}
