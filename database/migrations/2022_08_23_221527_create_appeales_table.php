<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        if (! Schema::hasTable('yeaf_appeals')) {
            Schema::create('yeaf_appeals', function (Blueprint $table) {
                $table->id();

                $table->integer('appeal_id')->unique();

                $table->string('student_id');
                $table->foreign('student_id')->references('student_id')->on('yeaf_students');

                $table->bigInteger('grant_id')->nullable()->comment('If application number is filled in, then relate this appeal to a particular application');
                $table->foreign('grant_id')->references('grant_id')->on('yeaf_grants');

                $table->integer('program_year_id')->nullable();
                $table->foreign('program_year_id')->references('program_year_id')->on('yeaf_program_years');

                $table->string('adjudicated_by_user_id')->nullable()->comment('Person who decided the appeal');

                $table->string('updated_by_user_id')->nullable()->comment('Logonid of person changing data.');
                $table->foreign('updated_by_user_id')->references('user_id')->on('users');

                $table->string('appeal_code', 3);
                $table->date('appeal_date')->nullable()->comment('Original Date of the appeal.');

                $table->string('status_code', 2)->nullable();
                $table->date('status_affective_date')->nullable()->comment('Date Appeal status became effective.');

                $table->string('other_appeal_explain')->nullable()->comment('If Appeal code of "other" selected, then enter the explanation here');

                $table->timestamps();
            });
            DB::update("alter table yeaf_appeals alter column appeal_id set default nextval('yeaf_appeals_id_seq'::regclass)-1;");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yeaf_appeals');
    }
};
