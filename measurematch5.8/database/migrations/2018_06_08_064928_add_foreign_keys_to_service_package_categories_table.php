<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToServicePackageCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_package_categories', function (Blueprint $table) {
            $table->integer('service_package_id')->unsigned()->change();
            $table->foreign('service_package_id')->references('id')->on('service_packages')->change();
            $table->integer('category_id')->unsigned()->change();
            $table->foreign('category_id')->references('id')->on('categories')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_package_categories', function (Blueprint $table) {
            $table->dropForeign(['service_package_id']);
            $table->dropForeign(['category_id']);
        });
    }
}
