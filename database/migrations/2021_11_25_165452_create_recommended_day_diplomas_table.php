<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecommendedDayDiplomasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recommended_day_diplomas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('day_id')->unsigned();
            $table->bigInteger('diploma_track_student_recommended_id')->unsigned();
            $table->string('day')->nullable();

            $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
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
        Schema::dropIfExists('recommended_day_diplomas');
    }
}
