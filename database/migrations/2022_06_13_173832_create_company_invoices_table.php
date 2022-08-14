<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('type');
            $table->double('amount',20,2);
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('seals_man_id')->unsigned();
            $table->bigInteger('accountant_id')->unsigned();
            $table->bigInteger('treasury_id')->unsigned()->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('seals_man_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('accountant_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('treasury_id')->references('id')->on('treasuries')->onDelete('cascade');

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
        Schema::dropIfExists('company_invoices');
    }
}
