<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('user_id');   
            $table->string('current_city',50)->nullable();
            $table->string('postcode',20)->nullable();
            $table->integer('remote_id')->nullable();
            $table->double('daily_rate')->nullable();
            $table->string('freelancer_setup',50)->nullable();
            $table->string('buisness_contact_number',20)->nullable();
            $table->string('buisness_name',20)->nullable();
            $table->string('address')->nullable();
            $table->string('summary')->nullable();
            $table->string('description')->nullable();
            $table->string('twitter_handle')->nullable();
            $table->string('linkedin_handle')->nullable();
            $table->text('website_url')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('profile_picture')->nullable();
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
        Schema::drop('user_profiles');
    }
}
