<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyReferencesToCouponAppliedByExpertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_applied_by_experts', function (Blueprint $table) {
            $table->foreign('expert_id')->references('id')->on('users')->change();
            $table->integer('referral_coupon_code_id')->unsigned()->change();
            $table->foreign('referral_coupon_code_id')->references('id')->on('referral_coupon_codes')->change();
            $table->integer('contract_id')->unsigned()->change();
            $table->foreign('contract_id')->references('id')->on('contracts')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupon_applied_by_experts', function (Blueprint $table) {
            $table->dropForeign(['expert_id']);
            $table->dropForeign(['referral_coupon_code_id']);
            $table->dropForeign(['contract_id']);
        });
    }
}
