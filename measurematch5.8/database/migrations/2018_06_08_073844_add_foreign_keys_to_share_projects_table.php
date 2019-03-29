<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToShareProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('share_projects', function (Blueprint $table) {
            $table->foreign('expert_id')->references('id')->on('users')->change();
            $table->integer('shared_project_id')->unsigned()->change();
            $table->foreign('shared_project_id')->references('id')->on('post_jobs')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('share_projects', function (Blueprint $table) {
            $table->dropForeign(['expert_id']);
            $table->dropForeign(['shared_project_id']);
        });
    }
}
