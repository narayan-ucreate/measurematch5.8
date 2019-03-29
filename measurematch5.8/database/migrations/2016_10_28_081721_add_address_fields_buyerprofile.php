<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressFieldsBuyerprofile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyer_profile', function ($table) {
            $table->string('first_address')->nullable();
            $table->string('second_address')->nullable();
            $table->string('city')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buyer_profile', function ($table) {
            $table->dropColumn('first_address');
            $table->dropColumn('second_address');
            $table->dropColumn('city');
        });
    }
}
