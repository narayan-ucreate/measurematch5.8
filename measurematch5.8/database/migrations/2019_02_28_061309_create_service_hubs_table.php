<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceHubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_hubs', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('user_id');
            $table->string('name', 200);
            $table->string('logo');
            $table->string('sales_email');
            $table->string('service_website');
            $table->text('description');
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
        Schema::dropIfExists('service_hubs');
    }
}
