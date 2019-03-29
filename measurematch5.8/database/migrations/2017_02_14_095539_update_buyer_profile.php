<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBuyerProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyer_profile', function (Blueprint $table) {
            $table->string('no_of_employ_in_organization')->nullable();
            $table->string('type_of_organization')->nullable();
            $table->string('what_brings_you_to_measurematch')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buyer_profile', function (Blueprint $table) {
            //
        });
    }
}
