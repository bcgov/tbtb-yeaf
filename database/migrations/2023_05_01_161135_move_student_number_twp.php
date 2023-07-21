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
        Schema::table('twp_applications', function (Blueprint $table) {
            $table->string('institution_student_number', 12)->nullable();
            $table->boolean('apply_twp')->default(true);
            $table->boolean('apply_lfg')->default(false)->comment('apply for learning future grant');
            $table->boolean('confirmation_enrolment')->default(false);
            $table->string('sabc_app_number', 10)->nullable();
            $table->string('fao_name')->nullable();
            $table->string('fao_email')->nullable();
            $table->string('fao_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('twp_applications', function (Blueprint $table) {
            $table->dropColumn('fao_phone');
            $table->dropColumn('fao_email');
            $table->dropColumn('fao_name');
            $table->dropColumn('sabc_app_number');
            $table->dropColumn('confirmation_enrolment');
            $table->dropColumn('apply_lfg');
            $table->dropColumn('apply_twp');
            $table->dropColumn('institution_student_number');
        });
    }
};
