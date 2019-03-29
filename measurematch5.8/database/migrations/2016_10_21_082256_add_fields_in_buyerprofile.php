<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsInBuyerprofile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyer_profile', function ($table) {
            $table->string('country')->nullable();
            $table->string('post_code')->nullable();
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
            $table->dropColumn('country');
            $table->dropColumn('post_code');
        });
    }
}
