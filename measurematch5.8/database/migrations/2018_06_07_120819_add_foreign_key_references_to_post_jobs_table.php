<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyReferencesToPostJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_jobs', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->change();
            $table->foreign('company_id')->references('id')->on('buyer_profile')->change();
            $table->foreign('user_id')->references('id')->on('users')->change();
            $table->integer('remote_id')->unsigned()->change();
            $table->foreign('remote_id')->references('id')->on('remote_works')->change();
            $table->integer('accepted_contract_id')->unsigned()->change();
            $table->foreign('accepted_contract_id')->references('id')->on('contracts')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_jobs', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['remote_id']);
            $table->dropForeign(['accepted_contract_id']);
        });
    }
}
