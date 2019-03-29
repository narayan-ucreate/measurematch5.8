<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionalCouponUsageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotional_coupon_usage_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('promotional_coupon_id');
            $table->integer('job_post_id');
            $table->integer('contract_id');
            $table->uuid('user_id');
            $table->integer('user_type_id');
            $table->boolean('is_redeemed')->default(FALSE);
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
        Schema::drop('promotional_coupon_usage_details');
    }
}
