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
        if (! Schema::hasTable('twp_students')) {
            Schema::create('twp_students', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('yeaf_student_id')->nullable();
                $table->string('sin', 11)->nullable();
                $table->string('name')->nullable();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->date('birth_date')->nullable();
                $table->string('email')->nullable();
                $table->string('gender', 1)->nullable();
                $table->string('pen', 9)->nullable();
                $table->string('institution_student_number', 12)->nullable();
                $table->string('citizenship')->nullable();
                $table->boolean('bc_resident')->default(false);
                $table->string('indigeneity')->nullable();

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
        Schema::dropIfExists('twp_students');
    }
};
