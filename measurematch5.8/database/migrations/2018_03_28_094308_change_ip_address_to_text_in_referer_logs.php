<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIpAddressToTextInRefererLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referer_logs', function (Blueprint $table) {
            $table->text('ip_address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referer_logs', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->change();
        });
    }
}
