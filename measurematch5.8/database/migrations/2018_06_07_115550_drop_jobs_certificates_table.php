<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropJobsCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::dropIfExists('jobs_certificates');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('jobs_certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_post_id');
            $table->integer('certificate_id')->nullable();
            $table->timestamps();
            
        });
    }
}
