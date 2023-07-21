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
        if (! Schema::hasTable('twp_reasons')) {
            Schema::create('twp_reasons', function (Blueprint $table) {
                $table->id();
                $table->string('reason_status')->nullable()->comment('Approved, Denied, In Progress, Approval on Appeal, Withdrawn');
                $table->string('title')->nullable();
                $table->longText('letter_body')->nullable();
                $table->boolean('active_flag')->default(true);
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
        Schema::dropIfExists('twp_reasons');
    }
};
