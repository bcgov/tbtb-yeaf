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
        if (! Schema::hasTable('yeaf_provinces')) {
            Schema::create('yeaf_provinces', function (Blueprint $table) {
                $table->id();

                $table->string('country_code');
                $table->foreign('country_code')->references('country_code')->on('yeaf_countries');

                $table->string('province_code', 2);
                $table->string('province_name');
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
        Schema::dropIfExists('yeaf_provinces');
    }
};
