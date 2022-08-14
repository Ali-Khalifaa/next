<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraineesPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainees_payments', function (Blueprint $table) {
            $table->id();
            $table->double('amount',8,2);
            $table->bigInteger('lead_id')->unsigned();
            $table->bigInteger('seals_man_id')->unsigned()->nullable();
            $table->bigInteger('accountant_id')->unsigned()->nullable();
            $table->bigInteger('treasury_id')->unsigned()->nullable();
            $table->string('product_name')->nullable();
            $table->enum('product_type', ['course','diploma','interview','placement_test','selta'])->nullable();
            $table->enum('type', ['in','out'])->default('in');
            $table->string('code')->nullable();
            $table->bigInteger('course_track_id')->unsigned()->nullable();
            $table->bigInteger('diploma_track_id')->unsigned()->nullable();

            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('seals_man_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('accountant_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('treasury_id')->references('id')->on('treasuries')->onDelete('cascade');
            $table->foreign('course_track_id')->references('id')->on('course_tracks')->onDelete('cascade');
            $table->foreign('diploma_track_id')->references('id')->on('diploma_tracks')->onDelete('cascade');

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
        Schema::dropIfExists('trainees_payments');
    }
}
