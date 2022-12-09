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
        Schema::table('program_twps', function (Blueprint $table) {
            $table->bigInteger('institution_twp_id')->nullable();
            $table->string('credential_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('program_twps', function (Blueprint $table) {
            $table->dropColumn('credential_type');
            $table->dropColumn('institution_twp_id');
        });
    }
};
