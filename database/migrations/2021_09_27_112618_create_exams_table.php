<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('course_id')->unsigned()->nullable();
            $table->bigInteger('diploma_id')->unsigned()->nullable();
            $table->bigInteger('exam_type_id')->unsigned();
            $table->string('name');
            $table->date('date_exam');
            $table->integer('exam_degree');
            $table->integer('exam_time');
            $table->enum('type', ['placement_test','quiz','final_exam'])->default('placement_test');

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('diploma_id')->references('id')->on('diplomas')->onDelete('cascade');
            $table->foreign('exam_type_id')->references('id')->on('exam_types')->onDelete('cascade');
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
        Schema::dropIfExists('exams');
    }
}
