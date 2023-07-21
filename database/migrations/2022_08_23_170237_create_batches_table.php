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
        if (! Schema::hasTable('yeaf_batches')) {
            Schema::create('yeaf_batches', function (Blueprint $table) {
                $table->id();
                $table->string('batch_number')->unique();
                $table->date('batch_date')->nullable()->comment('Date the batch closed');
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
        Schema::dropIfExists('yeaf_batches');
    }
};
