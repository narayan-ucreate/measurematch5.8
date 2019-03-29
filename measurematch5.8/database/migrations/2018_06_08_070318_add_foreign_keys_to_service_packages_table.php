<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToServicePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_packages', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->change();
            $table->integer('service_packages_category_id')->unsigned()->change();
            $table->foreign('service_packages_category_id')->references('id')->on('service_package_categories')->change();
            $table->integer('service_package_type_id')->unsigned()->change();
            $table->foreign('service_package_type_id')->references('id')->on('service_package_types')->change();
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
            $table->dropForeign(['user_id']);
            $table->dropForeign(['service_packages_category_id']);
            $table->dropForeign(['service_package_type_id']);
        });
    }
}
