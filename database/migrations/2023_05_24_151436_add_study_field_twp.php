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
        Schema::table('twp_programs', function (Blueprint $table) {
            $table->string('study_field')->default('Unselected');
        });
        Schema::table('twp_program_histories', function (Blueprint $table) {
            $table->string('study_field')->default('Unselected');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('twp_program_histories', function (Blueprint $table) {
            $table->dropColumn('study_field');
        });
        Schema::table('twp_programs', function (Blueprint $table) {
            $table->dropColumn('study_field');
        });
    }
};
