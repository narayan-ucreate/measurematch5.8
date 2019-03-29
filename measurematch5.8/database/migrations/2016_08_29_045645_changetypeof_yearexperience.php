<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangetypeofYearexperience extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('post_jobs', function (Blueprint $table) {
            $table->string('years_experience',5)->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_jobs', function (Blueprint $table) {
           
            $table->integer('years_experience')->change()->nullable();
        });
    }
}
