<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponAppliedByExpertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_applied_by_experts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('referral_coupon_code_id');
            $table->integer('contract_id');
            $table->uuid('expert_id');
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
        Schema::drop('coupon_applied_by_experts');
    }
}
