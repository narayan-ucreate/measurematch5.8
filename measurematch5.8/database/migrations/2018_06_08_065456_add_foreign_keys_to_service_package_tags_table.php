<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToServicePackageTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_package_tags', function (Blueprint $table) {
            $table->integer('service_package_id')->unsigned()->change();
            $table->foreign('service_package_id')->references('id')->on('service_packages')->change();
            $table->integer('tag_id')->unsigned()->change();
            $table->foreign('tag_id')->references('id')->on('tags')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_package_tags', function (Blueprint $table) {
            $table->dropForeign(['service_package_id']);
            $table->dropForeign(['tag_id']);
        });
    }
}
