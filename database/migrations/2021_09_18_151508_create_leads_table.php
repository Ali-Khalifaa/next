<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('mobile');
            $table->bigInteger('country_id')->nullable();
            $table->bigInteger('state_id')->nullable();
            $table->bigInteger('interesting_level_id')->unsigned()->nullable();
            $table->bigInteger('lead_source_id')->unsigned()->nullable();
            $table->string('education');

            $table->boolean('lead_type')->default(0);
            $table->string('company_name')->nullable();
            $table->boolean('add_list')->default(0);
            $table->boolean('attendance_state')->default(0);
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->bigInteger('leads_followup_id')->unsigned()->nullable();

            $table->text('registration_remark')->nullable();
            $table->boolean('add_placement')->default(0);
            $table->boolean('add_interview_sales')->default(0);
            $table->boolean('add_interview')->default(0);
            $table->boolean('add_course_sales')->default(0);
            $table->boolean('add_selta')->default(0);
            $table->boolean('is_client')->default(0);
            $table->boolean('black_list')->default(0);
            $table->boolean('active')->default(1);
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('img')->nullable();


            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('interesting_level_id')->references('id')->on('interesting_levels')->onDelete('cascade');
            $table->foreign('leads_followup_id')->references('id')->on('leads_followups')->onDelete('cascade');
            $table->foreign('lead_source_id')->references('id')->on('lead_sources')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
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
        Schema::dropIfExists('leads');
    }
}
