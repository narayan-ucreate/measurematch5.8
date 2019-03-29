<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BusinessAddress extends Model
{
    protected $table = 'business_addresses';
    protected $fillable = ['first_address','second_address','city',
        'state','postal_code','country'];
    public function businessAddress() {
        return $this->hasOne('App\Model\BusinessInformation', 'user_id');
    }

}
