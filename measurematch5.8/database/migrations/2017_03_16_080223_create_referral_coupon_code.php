<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralCouponCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_coupon_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('expert_id');
            $table->integer('referral_experts_id');
            $table->string('coupon_code');
            $table->integer('coupon_code_used_status')->default(0);
            $table->integer('coupon_code_amount')->default(20);
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
        Schema::drop('referral_coupon_codes');
    }
}
