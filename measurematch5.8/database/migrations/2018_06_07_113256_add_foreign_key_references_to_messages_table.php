<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyReferencesToMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->integer('communications_id')->unsigned()->change();
            $table->foreign('communications_id')->references('id')->on('communications')->change();
            $table->foreign('sender_id')->references('id')->on('users')->change();
            $table->foreign('receiver_id')->references('id')->on('users')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['communications_id']);
            $table->dropForeign(['sender_id']);
            $table->dropForeign(['receiver_id']);
        });
    }
}
