<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToUsersLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_languages', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->change();
            $table->integer('language_id')->unsigned()->change();
            $table->foreign('language_id')->references('id')->on('languages')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_languages', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['language_id']);
        });
    }
}
