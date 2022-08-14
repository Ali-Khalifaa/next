<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('prefix');
            $table->string('mobile');
            $table->string('phone');
            $table->string('website');
            $table->text('address');

            $table->boolean('add_list')->default(0);
            $table->boolean('add_placement')->default(0);
            $table->boolean('is_client')->default(0);

            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->bigInteger('company_followup_id')->unsigned()->nullable();

            $table->foreign('company_followup_id')->references('id')->on('company_followups')->onDelete('cascade');
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
        Schema::dropIfExists('companies');
    }
}
