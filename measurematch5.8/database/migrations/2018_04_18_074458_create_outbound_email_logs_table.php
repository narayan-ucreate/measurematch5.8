<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutboundEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outbound_email_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('email_client_message_id');
            $table->boolean('is_seen')->default(FALSE);
            $table->string('user_email');
            $table->uuid('user_id');
            $table->string('template_name');
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
        Schema::drop('outbound_email_logs');
    }
}
