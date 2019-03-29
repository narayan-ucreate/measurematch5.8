<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ColumnnullableUsersCertifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_certifications', function ($table) {
    $table->dropColumn(['start_date', 'end_date']);

});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('users_certifications', function ($table) {
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });
    }
}
