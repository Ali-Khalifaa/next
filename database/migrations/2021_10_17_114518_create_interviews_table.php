<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('interview_type_id')->unsigned()->nullable();
            $table->bigInteger('lead_id')->unsigned();
            $table->bigInteger('diploma_id')->unsigned();
            $table->bigInteger('instructor_id')->unsigned();
            $table->string('link')->nullable();
            $table->boolean('online')->default(1);
            $table->dateTime('date_interview');
            $table->boolean('selta')->default(0);

            $table->foreign('interview_type_id')->references('id')->on('interview_types')->onDelete('cascade');
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('diploma_id')->references('id')->on('diplomas')->onDelete('cascade');
            $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade');

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
        Schema::dropIfExists('interviews');
    }
}
