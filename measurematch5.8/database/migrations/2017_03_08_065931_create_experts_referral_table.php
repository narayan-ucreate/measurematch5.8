<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpertsReferralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('referral_experts', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('expert_id');
            $table->string('referral_expert_name');
            $table->string('referral_expert_email');
            $table->string('referral_coupon_code')->nullable();
            $table->string('referral_coupon_code_amount')->nullable();
            $table->integer('referral_status')->default(0);
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
        Schema::drop('referral_experts');
    }
}
