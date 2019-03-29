<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyReferencesToJobsSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs_skills', function (Blueprint $table) {
            $table->integer('job_post_id')->unsigned()->change();
            $table->foreign('job_post_id')->references('id')->on('post_jobs')->change();
            $table->integer('skill_id')->unsigned()->change();
            $table->foreign('skill_id')->references('id')->on('skills')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs_skills', function (Blueprint $table) {
            $table->dropForeign(['job_post_id']);
            $table->dropForeign(['skill_id']);
        });
    }
}
