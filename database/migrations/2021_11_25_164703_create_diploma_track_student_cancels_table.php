<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiplomaTrackStudentCancelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diploma_track_student_cancels', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('diploma_track_student_id')->unsigned();
            $table->double('cancellation_fee',8,2)->default(0);
            $table->date('cancellation_date');
            $table->date('refund_date');
            $table->text('cancellation_note');
            $table->boolean('is_refund')->default(0);
            $table->foreign('diploma_track_student_id')->references('id')->on('diploma_track_students')->onDelete('cascade');
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
        Schema::dropIfExists('diploma_track_student_cancels');
    }
}
