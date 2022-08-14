<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_questions', function (Blueprint $table) {
            $table->id();
            $table->text('main_question');
            $table->string('photo')->nullable();
            $table->text('link')->nullable();
            $table->text('article')->nullable();

            $table->bigInteger('question_type_id')->unsigned()->nullable();
            $table->bigInteger('exam_id')->unsigned();
            $table->bigInteger('part_id')->unsigned();

            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('part_id')->references('id')->on('parts')->onDelete('cascade');
            $table->foreign('question_type_id')->references('id')->on('question_types')->onDelete('cascade');

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
        Schema::dropIfExists('main_questions');
    }
}
