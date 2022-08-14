<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTrackStudentPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_track_student_payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('course_track_student_id')->unsigned();
            $table->date('payment_date');
            $table->double('amount',20,2)->default(0);
            $table->text('comment')->nullable();
            $table->boolean('checkIs_paid')->default(0);
            $table->double('all_paid',20,2)->default(0);
            $table->double('payment_additional_amount',20,2)->default(0);
            $table->double('payment_additional_discount',20,2)->default(0);
            $table->bigInteger('employee_id')->unsigned()->nullable();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('course_track_student_id')->references('id')->on('course_track_students')->onDelete('cascade');
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
        Schema::dropIfExists('course_track_student_payments');
    }
}
