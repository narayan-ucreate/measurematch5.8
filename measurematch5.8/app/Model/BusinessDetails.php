<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BusinessDetails extends Model
{
    protected $table = 'business_details';

    public function businessDetails() {
        return $this->hasOne('App\Model\BusinessInformation', 'user_id');
    }

}
