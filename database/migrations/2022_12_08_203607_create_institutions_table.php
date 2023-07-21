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
        if (! Schema::hasTable('twp_institutions')) {
            Schema::create('twp_institutions', function (Blueprint $table) {
                $table->id();

                $table->string('name');
                $table->string('contact_name')->nullable();
                $table->string('contact_email')->nullable();
                $table->boolean('active_flag')->default(false);

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
        Schema::dropIfExists('twp_institutions');
    }
};
