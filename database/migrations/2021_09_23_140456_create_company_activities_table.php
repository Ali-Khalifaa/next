<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_activities', function (Blueprint $table) {
            $table->id();
            $table->dateTime('follow_up');
            $table->text('notes')->nullable();
            $table->bigInteger('company_followup_id')->unsigned()->nullable();
            $table->bigInteger('company_followup_reason_id')->unsigned()->nullable();
            $table->string('file')->nullable();
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('employee_id')->unsigned();


            $table->foreign('company_followup_id')->references('id')->on('company_followups')->onDelete('cascade');
            $table->foreign('company_followup_reason_id')->references('id')->on('company_followup_reasons')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
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
        Schema::dropIfExists('company_activities');
    }
}
