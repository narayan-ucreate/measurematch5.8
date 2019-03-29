<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostJobIdToOutboundEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outbound_email_logs', function (Blueprint $table) {
            $table->integer('post_job_id')->nullable();
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
            $table->dropColumn('post_job_id');
        });
    }
}
