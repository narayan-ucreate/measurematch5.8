<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServiceHubAssociatedExpertIdToServiceHubApplicants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_hub_applicants', function (Blueprint $table) {
            $table->integer('service_hub_associated_expert_id')->unsigned()->nullable();
            $table->foreign('service_hub_associated_expert_id')->references('id')->on('service_hub_associated_experts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_hub_applicants', function (Blueprint $table) {
            $table->dropColumn('service_hub_associated_expert_id');
        });
    }
}
