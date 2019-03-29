<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionalCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotional_coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coupon_code');
            $table->integer('number_of_times_redeemed');
            $table->boolean('is_active')->default(FALSE);
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
         Schema::drop('promotional_coupons');
    }
}
