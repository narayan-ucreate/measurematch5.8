<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyReferencesToJobViewersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_viewers', function (Blueprint $table) {
            $table->foreign('expert_id')->references('id')->on('users')->change();
            $table->integer('job_posted_id')->unsigned()->change();
            $table->foreign('job_posted_id')->references('id')->on('post_jobs')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_viewers', function (Blueprint $table) {
            $table->dropForeign(['expert_id']);
            $table->dropForeign(['job_posted_id']);
        });
    }
}
