<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToUsersSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_skills', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->change();
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
        Schema::table('users_skills', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['skill_id']);
        });
    }
}
