<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertusertypevaluesChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('user_types')->insert([
                'id' => 1,
                'name' => 'Seller'

            ]
        );
        DB::table('user_types')->insert([
                'id' => 2,
                'name' => 'Buyer'
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
