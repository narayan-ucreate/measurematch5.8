<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpiryDateInPromotionalCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotional_coupons', function (Blueprint $table) {
             $table->dateTime('expiry_date')->nullable();
             $table->double('amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotional_coupons', function (Blueprint $table) {
             $table->dropColumn('expiry_date');
             $table->dropColumn('amount');
        });
    }
}
