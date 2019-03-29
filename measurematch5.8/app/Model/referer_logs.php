<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class referer_logs extends Model
{
    protected $table = 'referer_logs';
    public $timestamps = true;
    protected $fillable = ['referer','ip_address'];
}
