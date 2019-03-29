<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InvalidEmailDomain extends Model
{
    protected $table = 'invalid_email_domains';
    public $timestamps = true;
    protected $fillable = [
        'email'
    ];
    
    public static function fetchDomains($condition, $type){
        return self::where($condition)->$type();
    }
}
