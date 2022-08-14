<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTreasuriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_treasuries', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('target_employee_id')->unsigned()->nullable();
            $table->bigInteger('employee_id')->unsigned();
            $table->bigInteger('sales_man_id')->unsigned();
            $table->bigInteger('treasury_id')->unsigned()->nullable();
            $table->double('amount')->default(0);


            $table->foreign('treasury_id')->references('id')->on('treasuries')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('sales_man_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('target_employee_id')->references('id')->on('target_employees')->onDelete('cascade');
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
        Schema::dropIfExists('sales_treasuries');
    }
}
