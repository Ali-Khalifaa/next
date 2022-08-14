<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferringTreasuriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transferring_treasuries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned();
            $table->bigInteger('from_treasury_id')->unsigned()->nullable();
            $table->bigInteger('to_treasury_id')->unsigned()->nullable();
            $table->double('amount')->default(0);

            $table->foreign('from_treasury_id')->references('id')->on('treasuries')->onDelete('cascade');
            $table->foreign('to_treasury_id')->references('id')->on('treasuries')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');

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
        Schema::dropIfExists('transferring_treasuries');
    }
}
