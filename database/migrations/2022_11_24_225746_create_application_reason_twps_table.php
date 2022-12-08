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
        Schema::create('application_reason_twps', function (Blueprint $table) {
            $table->bigInteger('application_id');
            $table->foreign('application_id')->references('id')->on('application_twps');
            $table->bigInteger('reason_id');
            $table->foreign('reason_id')->references('id')->on('reason_twps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_reason_twps');
    }
};
