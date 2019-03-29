<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSkillIdType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('post_jobs', function (Blueprint $table) {
            $table->text('skill_id')->change()->nullable();
            $table->text('certificate_id')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_jobs', function (Blueprint $table) {
            $table->integer('skill_id')->change()->nullable();
            $table->integer('certificate_id')->change()->nullable();
           
        });
    }
}
