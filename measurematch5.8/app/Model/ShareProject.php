<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShareProject extends Model {

    protected $table = 'share_projects';
    protected $fillable = [
      'created_at'
    ];

}
