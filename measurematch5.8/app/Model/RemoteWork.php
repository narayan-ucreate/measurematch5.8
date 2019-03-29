<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RemoteWork extends Model {

    protected $table = 'remote_works';
     public static function fetchRemoteInformation($remote_id){
         return RemoteWork::where('id', $remote_id)->first()->toArray();
     }
     public static function getRemoteWorks() {
        return self::get()->toArray();
     }

}
