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
        Schema::table('twp_grants', function (Blueprint $table) {
            $table->bigInteger('twp_application_id')->default(5424);
            $table->foreign('twp_application_id')->references('id')->on('twp_applications')->onDelete('cascade');
        });

        Schema::table('twp_programs', function (Blueprint $table) {
            $table->bigInteger('twp_application_id')->default(5424);
            $table->foreign('twp_application_id')->references('id')->on('twp_applications')->onDelete('cascade');
        });
        Schema::table('twp_program_histories', function (Blueprint $table) {
            $table->bigInteger('twp_application_id')->default(5424);
            $table->foreign('twp_application_id')->references('id')->on('twp_applications')->onDelete('cascade');
        });
        Schema::table('twp_payments', function (Blueprint $table) {
            $table->bigInteger('twp_application_id')->default(5424);
            $table->foreign('twp_application_id')->references('id')->on('twp_applications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('twp_payments', function (Blueprint $table) {
            $table->dropForeign(['twp_application_id']);
            $table->dropColumn('twp_application_id');
        });
        Schema::table('twp_program_histories', function (Blueprint $table) {
            $table->dropForeign(['twp_application_id']);
            $table->dropColumn('twp_application_id');
        });
        Schema::table('twp_programs', function (Blueprint $table) {
            $table->dropForeign(['twp_application_id']);
            $table->dropColumn('twp_application_id');
        });
        Schema::table('twp_grants', function (Blueprint $table) {
            $table->dropForeign(['twp_application_id']);
            $table->dropColumn('twp_application_id');
        });
    }
};
