<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAcceptedContractIdToPostJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_jobs', function (Blueprint $table) {
            $table->integer('accepted_contract_id')->nullable();
            $table->boolean('accepted_contract_complete_status')->default(FALSE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_jobs', function (Blueprint $table) {
            $table->dropColumn('accepted_contract_id');
            $table->dropColumn('accepted_contract_complete_status');
        });
    }
}
