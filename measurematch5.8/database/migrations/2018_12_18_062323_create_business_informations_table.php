<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->integer('business_detail_id')->nullable();
            $table->integer('business_address_id')->nullable();
            $table->integer('bank_detail_id')->nullable();
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
        Schema::dropIfExists('business_informations');
    }
}
