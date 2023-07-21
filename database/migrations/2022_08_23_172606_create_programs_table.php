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
        if (! Schema::hasTable('yeaf_programs')) {
            Schema::create('yeaf_programs', function (Blueprint $table) {
                $table->id();
                $table->string('program_code', 1)->unique()->comment('Program type code');
                $table->string('program_description')->nullable();
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
        Schema::dropIfExists('yeaf_programs');
    }
};
