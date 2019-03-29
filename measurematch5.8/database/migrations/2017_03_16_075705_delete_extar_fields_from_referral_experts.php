<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteExtarFieldsFromReferralExperts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral_experts', function (Blueprint $table) {
            $table->dropColumn('referral_coupon_code_amount','referral_coupon_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referral_experts', function (Blueprint $table) {
            //
        });
    }
}
