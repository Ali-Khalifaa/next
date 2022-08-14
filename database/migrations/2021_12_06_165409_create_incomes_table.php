<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('expandedIcon')->default('pi pi-folder-open');
            $table->string('collapsedIcon')->default('pi pi-folder');
            $table->bigInteger('income_id')->unsigned()->nullable();
            $table->boolean('active')->default(1);
            $table->foreign('income_id')->references('id')->on('incomes')->onDelete('cascade');
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
        Schema::dropIfExists('incomes');
    }
}
