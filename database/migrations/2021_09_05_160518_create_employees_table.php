<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('address');
            $table->bigInteger('department_id')->unsigned()->nullable();
            $table->double('salary',8,2)->nullable();
            $table->string('National_ID');
            $table->string('mobile');
            $table->date('birth_date');
            $table->date('hiring_date');
            $table->bigInteger('job_id')->unsigned()->nullable();

//            $table->string('cv');
            $table->string('img');
            $table->string('graduation_year')->nullable();
            $table->string('eduction')->nullable();

            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->boolean('has_account')->default(0);
            $table->boolean('active')->default(1);
            $table->boolean('admin')->default(0);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');


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
        Schema::dropIfExists('employees');
    }
}
