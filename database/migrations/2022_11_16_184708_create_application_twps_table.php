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
        Schema::create('application_twps', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('twp_student_id');
            $table->foreign('twp_student_id')->references('id')->on('student_twps')->onDelete('cascade');

            $table->date('received_date')->nullable();

            $table->string('application_status')->nullable();
            $table->string('twp_status')->nullable();
            $table->text('denial_reason')->nullable();
            $table->text('exception_comments')->nullable();

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
        Schema::dropIfExists('application_twps');
    }
};
