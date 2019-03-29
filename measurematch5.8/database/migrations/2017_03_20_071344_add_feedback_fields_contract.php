<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFeedbackFieldsContract extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('contracts', function (Blueprint $table) {
           $table->string('buyer_feedback_status')->nullable()->default(0);
           $table->double('expert_rating')->nullable();
           $table->string('feedback_comment')->nullable();

              
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
