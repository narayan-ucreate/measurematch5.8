<?php

use Illuminate\Database\Seeder;

class SeedVatCountries extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = getVatApiResponse('countries');
        $rates = getVatApiResponse('rates');
        foreach ($countries as $key => $country){
            $countries[$key]['value'] = getCountryVAT($country['country_code'], $rates);
        }

        if (_count($countries)) {
            \DB::table('country_vat_details')->truncate();
            \DB::table('country_vat_details')->insert($countries);
        }

    }
}
