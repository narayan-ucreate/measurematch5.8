<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepricatedInTypeOfOrganizations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('type_of_organizations', function (Blueprint $table) {
            $table->boolean('depricated')->default(False);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('type_of_organizations', function (Blueprint $table) {
            $table->dropColumn('depricated');
        });
    }
}
