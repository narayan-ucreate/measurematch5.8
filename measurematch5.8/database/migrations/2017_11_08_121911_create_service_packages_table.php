<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('user_id');
            $table->string('name',500);
            $table->text('description');
            $table->integer('service_packages_category_id')->nullable();
            $table->text('buyer_remarks')->nullable();
            $table->integer('price');
            $table->integer('duration');
            $table->boolean('is_approved')->default(FALSE);
            $table->boolean('is_hidden')->default(FALSE);
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
        Schema::drop('service_packages');
    }
}
