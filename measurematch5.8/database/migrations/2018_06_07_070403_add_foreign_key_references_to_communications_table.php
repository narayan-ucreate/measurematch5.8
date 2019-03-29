<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyReferencesToCommunicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('communications', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->change();
            $table->foreign('buyer_id')->references('id')->on('users')->change();
            $table->integer('job_post_id')->unsigned()->change();
            $table->foreign('job_post_id')->references('id')->on('post_jobs')->change();
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
        Schema::table('communications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['buyer_id']);
            $table->dropForeign(['job_post_id']);
            $table->dropForeign(['service_package_id']);
        });
    }
}
