<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypeOfOrganizationIdInBuyerProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyer_profile', function (Blueprint $table) {
            $table->integer('type_of_organization_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buyer_profile', function (Blueprint $table) {
            $table->dropColumn('type_of_organization_id');
        });
    }
}
