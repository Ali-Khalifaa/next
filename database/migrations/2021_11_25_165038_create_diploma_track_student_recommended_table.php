<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiplomaTrackStudentRecommendedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diploma_track_student_recommended', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('diploma_track_student_id')->unsigned();
            $table->bigInteger('month_id')->unsigned();
            $table->time('from');
            $table->time('to');

            $table->foreign('month_id')->references('id')->on('months')->onDelete('cascade');

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
        Schema::dropIfExists('diploma_track_student_recommended');
    }
}
