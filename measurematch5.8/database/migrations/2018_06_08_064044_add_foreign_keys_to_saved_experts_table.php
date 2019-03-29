<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToSavedExpertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saved_experts', function (Blueprint $table) {
            $table->foreign('expert_id')->references('id')->on('users')->change();
            $table->foreign('buyer_id')->references('id')->on('users')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saved_experts', function (Blueprint $table) {
            $table->dropForeign(['expert_id']);
            $table->dropForeign(['buyer_id']);
        });
    }
}
