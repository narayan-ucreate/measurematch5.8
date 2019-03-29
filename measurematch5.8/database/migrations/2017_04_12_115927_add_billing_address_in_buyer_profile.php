<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBillingAddressInBuyerProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyer_profile', function (Blueprint $table) {
            $table->integer('billing_address_same_as_business_address')->default(0);
            $table->string('billing_address_first_address')->nullable();
            $table->string('billing_address_second_address')->nullable();
            $table->string('billing_address_city')->nullable();
            $table->string('billing_address_country')->nullable();
            $table->string('billing_postal_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buyer_profile', function (Blueprint $table) {
            //
        });
    }
}
