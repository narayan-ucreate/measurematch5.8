<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyReferencesToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('post_job_id')->unsigned()->change();
            $table->foreign('post_job_id')->references('id')->on('post_jobs')->change();
            $table->integer('contract_id')->unsigned()->change();
            $table->foreign('contract_id')->references('id')->on('contracts')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['post_job_id']);
            $table->dropForeign(['contract_id']);
        });
    }
}
