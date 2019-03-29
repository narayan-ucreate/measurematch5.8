<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PostmarkInbound extends Model
{
    protected $table = 'postmark_inbound';
    
    static function insertData($insert_data){
        self::insert($insert_data);
    }
}
