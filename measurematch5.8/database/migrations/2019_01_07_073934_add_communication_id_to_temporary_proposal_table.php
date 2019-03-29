<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommunicationIdToTemporaryProposalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('temporary_proposal', function (Blueprint $table) {
            $table->integer('communication_id')->nullable();
            $table->dropColumn('post_job_id');
            $table->dropColumn('expert_id');
            $table->dropColumn('project_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('temporary_proposal', function (Blueprint $table) {
            $table->dropColumn('communication_id');
            $table->integer('post_job_id');
            $table->uuid('expert_id');
            $table->string('project_type');
        });
    }

}
