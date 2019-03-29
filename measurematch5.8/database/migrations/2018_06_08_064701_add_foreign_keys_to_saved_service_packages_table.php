<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToSavedServicePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saved_service_packages', function (Blueprint $table) {
            $table->foreign('buyer_id')->references('id')->on('users')->change();
            $table->integer('service_package_id')->unsigned()->change();
            $table->foreign('service_package_id')->references('id')->on('service_packages')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saved_service_packages', function (Blueprint $table) {
            $table->dropForeign(['buyer_id']);
            $table->dropForeign(['service_package_id']);
        });
    }
}
