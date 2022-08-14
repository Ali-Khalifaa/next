<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstructorAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructor_attendances', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('attendance_time');
            $table->bigInteger('course_track_schedule_id')->unsigned()->nullable();
            $table->bigInteger('diploma_track_schedule_id')->unsigned()->nullable();
            $table->bigInteger('instructor_id')->unsigned();


            $table->foreign('course_track_schedule_id')->references('id')->on('course_track_schedules')->onDelete('cascade');
            $table->foreign('diploma_track_schedule_id')->references('id')->on('diploma_track_schedules')->onDelete('cascade');
            $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade');

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
        Schema::dropIfExists('instructor_attendances');
    }
}
