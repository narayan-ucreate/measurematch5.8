<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyReferencesToDeliverablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deliverables', function (Blueprint $table) {
            $table->integer('service_package_id')->unsigned()->change();
            $table->foreign('service_package_id')->references('id')->on('service_packages')->change();
            $table->integer('contract_id')->unsigned()->change();
            $table->foreign('contract_id')->references('id')->on('contracts')->change();
            $table->integer('post_job_id')->unsigned()->change();
            $table->foreign('post_job_id')->references('id')->on('post_jobs')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deliverables', function (Blueprint $table) {
            $table->dropForeign(['service_package_id']);
            $table->dropForeign(['contract_id']);
            $table->dropForeign(['post_job_id']);
        });
    }
}
