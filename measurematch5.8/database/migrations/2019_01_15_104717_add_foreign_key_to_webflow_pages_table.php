<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToWebflowPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('webflow_pages', function (Blueprint $table) {
            $table->dropColumn('internal_id');
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('webflow_pages', function (Blueprint $table) {
            $table->string('internal_id');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
