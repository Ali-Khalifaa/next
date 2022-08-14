<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTrackSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_track_schedules', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('course_track_id')->unsigned();
            $table->bigInteger('lab_id')->unsigned()->nullable();
            $table->bigInteger('course_id')->unsigned();
            $table->bigInteger('instructor_id')->unsigned()->nullable();
            $table->bigInteger('day_id')->unsigned();
            $table->date('date');
            $table->boolean('active')->default(1);

            $table->time('start_time');
            $table->time('end_time');

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');
            $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
            $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade');
            $table->foreign('course_track_id')->references('id')->on('course_tracks')->onDelete('cascade');


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
        Schema::dropIfExists('course_track_schedules');
    }
}
