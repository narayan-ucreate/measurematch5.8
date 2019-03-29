<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVatMmFeeMmFeeVatSubtotalToContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->smallInteger('vat')->nullable();
            $table->Integer('vat_value')->nullable();
            $table->Integer('sub_total')->nullable();
            $table->Integer('mm_fee')->nullable();
            $table->Integer('mm_fee_vat')->nullable();
            $table->Integer('expert_amount')->nullable();
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
            $table->dropColumn('vat');
            $table->dropColumn('vat_value');
            $table->dropColumn('sub_total');
            $table->dropColumn('mm_fee');
            $table->dropColumn('mm_fee_vat');
            $table->dropColumn('expert_amount');
        });
    }
}
