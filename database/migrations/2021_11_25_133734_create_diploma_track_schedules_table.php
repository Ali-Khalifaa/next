<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiplomaTrackSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diploma_track_schedules', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('diploma_track_id')->unsigned();
            $table->bigInteger('course_id')->unsigned()->nullable();
            $table->bigInteger('lab_id')->unsigned()->nullable();
            $table->bigInteger('diploma_id')->unsigned()->nullable();
            $table->bigInteger('instructor_id')->unsigned();
            $table->bigInteger('day_id')->unsigned();
            $table->date('date');
            $table->boolean('active')->default(1);

            $table->time('start_time');
            $table->time('end_time');

            $table->foreign('diploma_id')->references('id')->on('diplomas')->onDelete('cascade');
            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');
            $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
            $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade');
            $table->foreign('diploma_track_id')->references('id')->on('diploma_tracks')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

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
        Schema::dropIfExists('diploma_track_schedules');
    }
}
