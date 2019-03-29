<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyReferencesToOutboundEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outbound_email_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->change();
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
        Schema::table('outbound_email_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['post_job_id']);
        });
    }
}
