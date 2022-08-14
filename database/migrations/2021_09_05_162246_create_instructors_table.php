<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructors', function (Blueprint $table) {

            $table->id();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('mobile');
            $table->string('address');
            $table->string('phone');
            $table->string('cv')->nullable();
            $table->string('img')->nullable();
            $table->double('hour_price',8,2);
            $table->date('birth_date');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->boolean('has_account')->default(0);
            $table->boolean('active')->default(1);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');


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
        Schema::dropIfExists('instructors');
    }
}
