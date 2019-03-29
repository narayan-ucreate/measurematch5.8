<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRateTypeQuantityAndPriceToDeliverables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deliverables', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->integer('rate')->nullable();
            $table->integer('rate_unit')->nullable();
            $table->integer('quantity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deliverables', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('rate');
            $table->dropColumn('rate_unit');
            $table->dropColumn('quantity');
        });
    }
}
