<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceHubRejectedApplicantDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_hub_rejected_applicant_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_hub_applicant_id')->unsigned();
            $table->foreign('service_hub_applicant_id')->references('id')->on('service_hub_applicants');
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_hub_rejected_applicant_details');
    }
}
