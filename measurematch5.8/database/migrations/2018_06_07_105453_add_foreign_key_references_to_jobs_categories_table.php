<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyReferencesToJobsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs_categories', function (Blueprint $table) {
            $table->integer('job_post_id')->unsigned()->change();
            $table->foreign('job_post_id')->references('id')->on('post_jobs')->change();
            $table->integer('category_id')->unsigned()->change();
            $table->foreign('category_id')->references('id')->on('categories')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs_categories', function (Blueprint $table) {
            $table->dropForeign(['job_post_id']);
            $table->dropForeign(['category_id']);
        });
    }
}
