<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropStripesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('stripes');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('stripes', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('user_id');
            $table->string('card_id');
            $table->json('strip_response');
            $table->string('vat_number')->nullable();
            $table->timestamps();
        });
    }
}
