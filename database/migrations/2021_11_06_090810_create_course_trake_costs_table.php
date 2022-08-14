<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTrakeCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_track_costs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('course_track_id')->unsigned();
            $table->double('price',20,2)->default(0);
            $table->double('certificate_price',20,2)->default(0);
            $table->double('lab_cost',20,2)->default(0);
            $table->double('material_cost',20,2)->default(0);
            $table->double('assignment_cost',20,2)->default(0);
            $table->double('placement_cost',20,2)->default(0);
            $table->double('exam_cost',20,2)->default(0);
            $table->double('interview',20,2)->default(0);
            $table->double('application',20,2)->default(0);

            $table->foreign('course_track_id')->references('id')->on('course_tracks')->onDelete('cascade');

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
        Schema::dropIfExists('course_trake_costs');
    }
}
