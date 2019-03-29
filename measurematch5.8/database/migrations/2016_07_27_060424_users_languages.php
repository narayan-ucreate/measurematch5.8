<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersLanguages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_languages', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('user_id');
            $table->integer('language_id')->nullable();
            $table->string('name')->nullable();
             $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users_languages');
    }
}
