<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealIndividualPlacementTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_individual_placement_tests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('diploma_id')->unsigned();
            $table->double('placement_cost',8,2)->default(0);
            $table->double('amount',8,2)->default(0);
            $table->bigInteger('employee_id')->unsigned();
            $table->bigInteger('lead_id')->unsigned();
            $table->boolean('is_payed')->default(0);
            $table->text('note')->nullable();
            $table->bigInteger('company_id')->unsigned()->nullable();


            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('diploma_id')->references('id')->on('diplomas')->onDelete('cascade');
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
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
        Schema::dropIfExists('deal_individual_placement_tests');
    }
}
