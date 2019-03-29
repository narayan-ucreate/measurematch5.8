<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeCommunicationTypeIdEnumUsersCommunications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_communications', function (Blueprint $table) {
            $table->dropColumn('communication_type_id');
            $table->enum('email_subscription',array('1','0'))->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_communications', function (Blueprint $table) {
            //
        });
    }
}
