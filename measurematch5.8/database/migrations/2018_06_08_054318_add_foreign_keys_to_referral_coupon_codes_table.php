<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToReferralCouponCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral_coupon_codes', function (Blueprint $table) {
            $table->foreign('expert_id')->references('id')->on('users')->change();
            $table->integer('referral_experts_id')->unsigned()->change();
            $table->foreign('referral_experts_id')->references('id')->on('referral_experts')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referral_coupon_codes', function (Blueprint $table) {
            $table->dropForeign(['expert_id']);
            $table->dropForeign(['referral_experts_id']);
        });
    }
}
