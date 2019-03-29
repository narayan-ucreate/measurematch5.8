<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmploymentDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('employment_details', function (Blueprint $table) {
            $table->increments('id');
           $table->uuid('user_id');
            $table->string('company_name')->nullable();
            $table->string('company_type')->nullable();
            $table->integer('start_month')->nullable();
            $table->integer('start_year')->nullable();
            $table->integer('end_month')->nullable();
            $table->integer('end_year')->nullable();
            $table->text('position_title')->nullable();
            $table->text('summary')->nullable();
            $table->boolean('is_current',false);
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
        Schema::drop('employment_details');
    }
    
}
