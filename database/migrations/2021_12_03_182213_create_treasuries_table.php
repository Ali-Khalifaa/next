<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreasuriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treasuries', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->double('expense',20,2)->default(0);
            $table->double('income',20,2)->default(0);
            $table->string('expandedIcon')->default('pi pi-folder-open');
            $table->string('collapsedIcon')->default('pi pi-folder');
            $table->bigInteger('treasury_id')->unsigned()->nullable();
            $table->boolean('active')->default(1);
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
        Schema::dropIfExists('treasuries');
    }
}
