<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraineesAttendanceCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainees_attendance_courses', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('course_track_schedule_id')->unsigned()->nullable();
            $table->bigInteger('course_track_student_id')->unsigned();
            $table->boolean('attendance')->default(0);

            $table->foreign('course_track_student_id')->references('id')->on('course_track_students')->onDelete('cascade');
            $table->foreign('course_track_schedule_id')->references('id')->on('course_track_schedules')->onDelete('cascade');

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
        Schema::dropIfExists('trainees_attendance_courses');
    }
}
