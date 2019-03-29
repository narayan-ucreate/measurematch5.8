<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyerProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_profile', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id',50);
            $table->string('first_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_url')->nullable();
            $table->string('parent_company')->nullable();
            $table->string('office_loacation')->nullable();
            $table->string('address')->nullable();
            $table->string('bio')->nullable();
            $table->integer('phone_number')->nullable();
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
        Schema::drop('buyer_profile');
    }
}
