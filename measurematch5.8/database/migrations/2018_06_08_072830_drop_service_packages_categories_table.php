<?php

use Illuminate\Database\Migrations\Migration;

class DropServicePackagesCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('service_packages_categories');
    }
    public function down()
    {
        Schema::create('service_packages_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_package_id');
            $table->integer('category_id');
            $table->timestamps(); 
        });
    }
}
