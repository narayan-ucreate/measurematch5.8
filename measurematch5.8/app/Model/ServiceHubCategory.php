<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceHubCategory extends Model
{
    protected $table = 'service_hub_categories';
    public $timestamps = true;
    protected $fillable = [
        'name', 'service_hub_id'
    ];




}
