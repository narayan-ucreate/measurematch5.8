<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServicepackageIdToPromotionalCouponUsageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotional_coupon_usage_details', function (Blueprint $table) {
            $table->integer('service_package_id')->nullable()->default(NULL);
            $table->string('type')->default('project');
            $table->integer('job_post_id')->nullable()->change();
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
            $table->dropColumn('service_packages_id');
            $table->dropColumn('type');
            $table->integer('job_post_id')->change();
        });
    }
}
