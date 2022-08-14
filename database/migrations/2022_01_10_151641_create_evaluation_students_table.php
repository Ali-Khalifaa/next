<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('evaluation_question_id')->unsigned();
            $table->bigInteger('lead_id')->unsigned();
            $table->integer('answer');
            $table->bigInteger('evaluation_id')->unsigned();
            $table->bigInteger('course_track_id')->unsigned()->nullable();
            $table->bigInteger('diploma_track_id')->unsigned()->nullable();
            $table->bigInteger('instructor_id')->unsigned()->nullable();
            $table->bigInteger('lab_id')->unsigned()->nullable();


            $table->foreign('course_track_id')->references('id')->on('course_tracks')->onDelete('cascade');
            $table->foreign('diploma_track_id')->references('id')->on('diploma_tracks')->onDelete('cascade');
            $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade');
            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');

            $table->foreign('evaluation_id')->references('id')->on('evaluations')->onDelete('cascade');
            $table->foreign('evaluation_question_id')->references('id')->on('evaluation_questions')->onDelete('cascade');
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
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
        Schema::dropIfExists('evaluation_students');
    }
}
