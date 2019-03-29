<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('post_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();
            $table->text('job_title')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('skill_id')->nullable();
            $table->integer('certificate_id')->nullable();
            $table->integer('years_experience')->nullable();
            $table->text('description')->nullable();
            $table->date('job_start_date')->nullable();
            $table->date('job_end_date')->nullable();
            $table->string('rate_variable',20)->nullable();
            $table->string('rate',5)->nullable();
            $table->string('payment_method',20)->nullable();
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
        Schema::drop('post_jobs');
    }
}
