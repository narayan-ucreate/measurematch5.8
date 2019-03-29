<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveVendorUserIdFromVendorInvitedExpertsTableAndAddServiceHubId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_invited_experts', function (Blueprint $table) {
            $table->dropColumn('vendor_user_id');
            $table->integer('service_hub_id')->unsigned()->nullable();
            $table->foreign('service_hub_id')->references('id')->on('service_hubs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_invited_experts', function (Blueprint $table) {
            $table->uuid('vendor_user_id')->nullable();
            $table->foreign('vendor_user_id')->references('id')->on('users');
            $table->dropColumn('service_hub_id');
        });
    }
}
