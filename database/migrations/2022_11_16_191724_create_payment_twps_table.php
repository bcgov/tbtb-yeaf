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
        if (! Schema::hasTable('twp_payments')) {
            Schema::create('twp_payments', function (Blueprint $table) {
                $table->id();

                $table->bigInteger('twp_student_id');
                $table->foreign('twp_student_id')->references('id')->on('twp_students')->onDelete('cascade');

                $table->bigInteger('twp_program_id');
                $table->foreign('twp_program_id')->references('id')->on('twp_programs')->onDelete('cascade');

                $table->date('payment_date');
                $table->double('payment_amount', null, 2)->default(0)->nullable();

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
        Schema::dropIfExists('twp_payments');
    }
};
