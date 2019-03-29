<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsInUserprofile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('user_profiles', function ($table) {
            $table->string('describe')->nullable();
            $table->string('category_id')->nullable();
            $table->string('rate_variable')->nullable();
            $table->string('currency')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('user_profiles', function ($table) {
            $table->dropColumn('describe');
            $table->dropColumn('category_id');
            $table->dropColumn('rate_variable');
            $table->dropColumn('currency');
        });
    }
}
