<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountTraineesPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_trainees_payments', function (Blueprint $table) {
            $table->id();
            $table->double('total_discount')->default(0);
            $table->double('net_amount')->default(0);
            $table->bigInteger('trainees_payment_id')->unsigned()->nullable();
            $table->foreign('trainees_payment_id')->references('id')->on('trainees_payments')->onDelete('cascade');
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
        Schema::dropIfExists('discount_trainees_payments');
    }
}
