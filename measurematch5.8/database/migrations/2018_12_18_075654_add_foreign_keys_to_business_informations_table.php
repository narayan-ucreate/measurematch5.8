<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToBusinessInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_informations', function (Blueprint $table) {
            $table->integer('business_detail_id')->nullable()->unsigned()->change();
            $table->integer('business_address_id')->nullable()->unsigned()->change();
            $table->integer('bank_detail_id')->nullable()->unsigned()->change();
            $table->foreign('business_detail_id')->references('id')->on('business_details')->change();
            $table->foreign('business_address_id')->references('id')->on('business_addresses')->change();
            $table->foreign('bank_detail_id')->references('id')->on('bank_details')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_informations', function (Blueprint $table) {
            $table->dropForeign(['business_detail_id']);
            $table->dropForeign(['business_address_id']);
            $table->dropForeign(['bank_detail_id']);
        });
    }
}
