<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToServiceHubAssociatedExpertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_hub_associated_experts', function(Blueprint $table) {
            $table->tinyInteger('status')->default(1)->comment = "0= pending, 1= approve, 2=decline";
            $table->boolean('is_applicant')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_hub_associated_experts', function(Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('is_applicant');
        });
    }
}
