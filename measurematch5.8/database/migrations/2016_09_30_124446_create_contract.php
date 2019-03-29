<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContract extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('communications_id')->after('id');
            $table->integer('buyer_id');
            $table->integer('user_id');
            $table->integer('job_post_id');
            $table->integer('status');
            $table->date('job_start_date')->nullable();
            $table->date('job_end_date')->nullable();
            $table->string('rate_variable',20)->nullable();
            $table->string('rate',5)->nullable();
            $table->string('deliverable1')->nullable();
            $table->string('deliverable2')->nullable();
            $table->string('deliverable3')->nullable();
            $table->string('upload_document')->nullable();
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
       Schema::drop('contracts');
    }
}
