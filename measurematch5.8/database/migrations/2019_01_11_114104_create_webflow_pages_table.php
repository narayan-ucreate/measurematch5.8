<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebflowPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webflow_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('webflow_collection_id');
            $table->string('webflow_item_id');
            $table->string('internal_id');
            $table->string('type');
            $table->string('webflow_url');
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
        Schema::dropIfExists('webflow_pages');
    }
}
