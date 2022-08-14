<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('vendor_id')->unsigned();
            $table->boolean('allow_reservation_without_schedule')->default(0);
            $table->text('course_period_description')->nullable();
            $table->double('hour_count',8,2);
            $table->text('course_prerequisites')->nullable();
            $table->text('course_overview')->nullable();
            $table->text('course_outlines')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('small_image')->nullable();
            $table->boolean('active')->default(1);
            $table->string('course_code');

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
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
        Schema::dropIfExists('courses');
    }
}
