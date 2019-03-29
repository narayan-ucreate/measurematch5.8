<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHideCompanyNameAndChangeUploadDocumentToJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_jobs', function (Blueprint $table) {
            $table->boolean('hide_company_name')->default(FALSE);
            $table->text('upload_document')->nullable()->change();
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
            $table->dropColumn('hide_company_name');
            $table->string('upload_document')->nullable()->change();
        });
    }
}
