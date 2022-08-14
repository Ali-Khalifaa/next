<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicDiscountDiplomasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_discount_diplomas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('diploma_track_id')->unsigned();
            $table->bigInteger('discount_id')->unsigned();
            $table->date('from_date');
            $table->date('to_date');
            $table->double('discount_percent',8,2)->default(0);
            $table->double('price_after_discount',8,2)->default(0);

            $table->foreign('diploma_track_id')->references('id')->on('diploma_tracks')->onDelete('cascade');
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
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
        Schema::dropIfExists('public_discount_diplomas');
    }
}
