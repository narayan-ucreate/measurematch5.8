<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CountryVatDetails extends Model {

    protected $table = 'country_vat_details';
    protected $fillable = ['object', 'country_code', 'country_name',
        'latitude', 'longitude', 'vat', 'eu', 'value'
    ];

    public function getAllCountryVatDetails() {
        return self::orderBy('country_name', 'asc')->get();
    }

    public function getCountryByCode($code){
        return CountryVatDetails::where('country_code', $code)->get()->first();
    }

    public function getCodeByCountry($country){
        return CountryVatDetails::where('country_name', $country)->get()->first();
    }

    public function getCountryNameArray(){
        return CountryVatDetails::select('country_name')->get()->toArray();
    }
}
