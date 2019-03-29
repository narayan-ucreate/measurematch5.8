<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReverseChargeMmFeeAndReverseChargeInvoiceToContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->boolean('reverse_charge_invoice')->default(false);
            $table->boolean('reverse_charge_mm_fee')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('reverse_charge_invoice');
            $table->dropColumn('reverse_charge_mm_fee');
        });
    }
}
