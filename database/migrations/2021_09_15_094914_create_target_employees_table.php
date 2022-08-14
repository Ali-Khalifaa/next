<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('target_employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sales_target_id')->unsigned();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->bigInteger('comission_management_id')->unsigned();
            $table->double('target_amount',20,2);
            $table->double('target_percentage',8,2);
            $table->double('achievement',8,2)->default(0);
            $table->boolean('corporation')->default(0);

            $table->foreign('sales_target_id')->references('id')->on('sales_targets')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('comission_management_id')->references('id')->on('comission_management')->onDelete('cascade');

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
        Schema::dropIfExists('target_employees');
    }
}
