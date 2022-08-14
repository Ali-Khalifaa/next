<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTeamPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_team_payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('target_employee_id')->unsigned()->nullable();
            $table->bigInteger('employee_id')->unsigned();
            $table->bigInteger('treasury_id')->unsigned()->nullable();

            $table->bigInteger('lead_id')->unsigned();
            $table->string('product_name')->nullable();
            $table->enum('product_type', ['course','diploma','interview','placement_test','selta'])->nullable();
            $table->bigInteger('diploma_track_id')->unsigned()->nullable();
            $table->bigInteger('course_track_id')->unsigned()->nullable();
            $table->enum('type', ['in','out'])->default('in');

            $table->boolean('is_payed')->default(0);
            $table->double('amount')->default(0);

            $table->foreign('target_employee_id')->references('id')->on('target_employees')->onDelete('cascade');
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('course_track_id')->references('id')->on('course_tracks')->onDelete('cascade');
            $table->foreign('diploma_track_id')->references('id')->on('diploma_tracks')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('treasury_id')->references('id')->on('treasuries')->onDelete('cascade');

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
        Schema::dropIfExists('sales_team_payments');
    }
}
