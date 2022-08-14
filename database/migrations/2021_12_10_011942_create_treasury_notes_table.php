<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreasuryNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treasury_notes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned();
            $table->bigInteger('treasury_id')->unsigned()->nullable();
            $table->bigInteger('trainees_payment_id')->unsigned()->nullable();
            $table->bigInteger('income_and_expense_id')->unsigned()->nullable();
            $table->bigInteger('instructor_payment_id')->unsigned()->nullable();
            $table->bigInteger('sales_treasury_id')->unsigned()->nullable();
            $table->text('note');
            $table->enum('type', ['in','out'])->default('in');
            $table->double('amount')->default(0);

            $table->foreign('trainees_payment_id')->references('id')->on('trainees_payments')->onDelete('cascade');
            $table->foreign('sales_treasury_id')->references('id')->on('sales_treasuries')->onDelete('cascade');
            $table->foreign('treasury_id')->references('id')->on('treasuries')->onDelete('cascade');
            $table->foreign('instructor_payment_id')->references('id')->on('instructor_payments')->onDelete('cascade');
            $table->foreign('income_and_expense_id')->references('id')->on('income_and_expenses')->onDelete('cascade');
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
        Schema::dropIfExists('treasury_notes');
    }
}
