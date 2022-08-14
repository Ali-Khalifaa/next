<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview_files', function (Blueprint $table) {
            $table->id();
            $table->string('img');
            $table->bigInteger('interview_id')->unsigned();
            $table->bigInteger('interview_result_id')->unsigned();

            $table->foreign('interview_id')->references('id')->on('interviews')->onDelete('cascade');
            $table->foreign('interview_result_id')->references('id')->on('interview_results')->onDelete('cascade');

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
        Schema::dropIfExists('interview_files');
    }
}
