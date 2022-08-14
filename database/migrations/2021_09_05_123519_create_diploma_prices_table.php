<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiplomaPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diploma_prices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('diploma_id')->unsigned();
            $table->double('price',20,2)->default(0);
            $table->double('certificate_price',20,2)->default(0);
            $table->double('lab_cost',20,2)->default(0);
            $table->double('material_cost',20,2)->default(0);
            $table->double('assignment_cost',20,2)->default(0);
            $table->double('placement_cost',20,2)->default(0);
            $table->double('exam_cost',20,2)->default(0);
            $table->double('interview',20,2)->default(0);
            $table->double('application',20,2)->default(0);
            $table->date('from_date')->nullable();
            $table->date('active_date')->nullable();

            $table->foreign('diploma_id')->references('id')->on('diplomas')->onDelete('cascade');

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
        Schema::dropIfExists('diploma_prices');
    }
}
