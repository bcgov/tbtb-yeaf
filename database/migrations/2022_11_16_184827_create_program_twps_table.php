<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('twp_programs')) {
            Schema::create('twp_programs', function (Blueprint $table) {
                $table->id();

                $table->bigInteger('twp_student_id');
                $table->foreign('twp_student_id')->references('id')->on('twp_students')->onDelete('cascade');

                $table->bigInteger('yeaf_institution_id')->nullable();
                $table->integer('yeaf_program_year_id')->nullable();
                $table->date('yeaf_study_start_date')->nullable()->comment('ssd');
                $table->date('yeaf_study_end_date')->nullable()->comment('sed');

                $table->string('institution_name')->nullable();
                $table->date('study_period_start_date')->nullable();
                $table->string('credential')->nullable();
                $table->integer('program_length')->nullable();
                $table->string('program_length_type')->nullable();
                $table->double('total_estimated_cost', null, 2)->default(0)->nullable();
                $table->string('student_status')->nullable();
                $table->text('comments')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('twp_programs');
    }
};
