<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToPromotionalCouponUsageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotional_coupon_usage_details', function (Blueprint $table) {
            $table->integer('promotional_coupon_id')->unsigned()->change();
            $table->foreign('promotional_coupon_id')->references('id')->on('promotional_coupons')->change();
            $table->integer('job_post_id')->unsigned()->change();
            $table->foreign('job_post_id')->references('id')->on('post_jobs')->change();
            $table->integer('contract_id')->unsigned()->change();
            $table->foreign('contract_id')->references('id')->on('contracts')->change();
            $table->foreign('user_id')->references('id')->on('users')->change();
            $table->integer('user_type_id')->unsigned()->change();
            $table->foreign('user_type_id')->references('id')->on('user_types')->change();
            $table->integer('service_package_id')->unsigned()->change();
            $table->foreign('service_package_id')->references('id')->on('service_packages')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotional_coupon_usage_details', function (Blueprint $table) {
            $table->dropForeign(['promotional_coupon_id']);
            $table->dropForeign(['job_post_id']);
            $table->dropForeign(['contract_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['user_type_id']);
            $table->dropForeign(['service_package_id']);
        });
    }
}
