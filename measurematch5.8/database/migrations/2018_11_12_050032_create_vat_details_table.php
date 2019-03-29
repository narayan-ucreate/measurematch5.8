<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVatDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_vat_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('object');
            $table->string('country_code');
            $table->string('country_name');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('vat');
            $table->string('eu');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_vat_details');
    }
}
