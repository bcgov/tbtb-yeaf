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
        Schema::table('ineligibles', function (Blueprint $table) {
            $table->unique('code_id');
        });

        Schema::create('grant_ineligibles', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('grant_id')->nullable();
            $table->foreign('grant_id')->references('grant_id')->on('grants');


            $table->string('ineligible_code_id')->nullable();
            $table->foreign('ineligible_code_id')->references('code_id')->on('ineligibles');

            $table->string('created_by');
            $table->boolean('cleared_flag')->comment('true - error has been cleared');
            $table->string('ineligible_code_type', 1)->nullable()->comment('D = Denied status reason, P = Pending status reason');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ineligibles', function (Blueprint $table) {
            $table->dropUnique('code_id');
        });

        Schema::dropIfExists('grant_ineligibles');
    }
};
