<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiplomaTrackStudentDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diploma_track_student_discounts', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('diploma_track_student_id')->unsigned();
            $table->bigInteger('discount_id')->unsigned();

            $table->foreign('diploma_track_student_id')->references('id')->on('diploma_track_students')->onDelete('cascade');
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');

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
        Schema::dropIfExists('diploma_track_student_discounts');
    }
}
