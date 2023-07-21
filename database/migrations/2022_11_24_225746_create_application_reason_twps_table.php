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
        if (! Schema::hasTable('twp_application_reasons')) {
            Schema::create('twp_application_reasons', function (Blueprint $table) {
                $table->bigInteger('application_id');
                $table->foreign('application_id')->references('id')->on('twp_applications');
                $table->bigInteger('reason_id');
                $table->foreign('reason_id')->references('id')->on('twp_reasons');
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
        Schema::dropIfExists('twp_application_reasons');
    }
};
