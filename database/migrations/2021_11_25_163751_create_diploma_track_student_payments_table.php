<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiplomaTrackStudentPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diploma_track_student_payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('diploma_track_student_id')->unsigned();
            $table->date('payment_date');
            $table->double('amount',8,2)->default(0);
            $table->text('comment')->nullable();
            $table->boolean('checkIs_paid')->default(0);
            $table->double('all_paid',8,2)->default(0);
            $table->double('payment_additional_amount',8,2)->default(0);
            $table->double('payment_additional_discount',8,2)->default(0);
            $table->bigInteger('employee_id')->unsigned()->nullable();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('diploma_track_student_id')->references('id')->on('diploma_track_students')->onDelete('cascade');
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
        Schema::dropIfExists('diploma_track_student_payments');
    }
}
