<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIpAddressToRefererLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referer_logs', function (Blueprint $table) {
            $table->string('ip_address')->nullable();
            $table->string('referer')->nullable()->change();
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
             $table->dropColumn('ip_address');
             $table->string('referer')->nullable()->change();
        });
    }
}
