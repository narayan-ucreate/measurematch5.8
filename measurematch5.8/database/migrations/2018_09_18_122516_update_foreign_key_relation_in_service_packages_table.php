<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateForeignKeyRelationInServicePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_packages', function (Blueprint $table) {
            $table->dropForeign(['service_packages_category_id']);
            $table->foreign('service_packages_category_id')->references('id')->on('categories')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_packages', function (Blueprint $table) {
            $table->dropForeign(['service_packages_category_id']);
            $table->foreign('service_packages_category_id')->references('id')->on('service_packages_categories')->change();
        });
    }
}
