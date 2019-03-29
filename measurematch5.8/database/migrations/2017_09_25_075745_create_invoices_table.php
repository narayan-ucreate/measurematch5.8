<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_job_id');
            $table->integer('contract_id');
            $table->double('amount');
            $table->double('application_fee')->nullable();
            $table->double('remaining_unpaid_amount')->default(0);
            $table->dateTime('due_date')->nullable();
            $table->boolean('is_paid')->default(FALSE);
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
        Schema::drop('invoices');
    }
}
