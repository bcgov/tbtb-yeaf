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
        if (! Schema::hasTable('twp_grants')) {
            Schema::create('twp_grants', function (Blueprint $table) {
                $table->id();

                $table->bigInteger('twp_student_id');
                $table->foreign('twp_student_id')->references('id')->on('twp_students')->onDelete('cascade');

                $table->date('received_date');
                $table->string('grant_status')->nullable();
                $table->text('grant_comments')->nullable();
                $table->double('grant_amount', null, 2)->default(0)->nullable();

                $table->string('created_by');
                $table->string('updated_by');

                $table->string('extra_1')->nullable();
                $table->string('extra_2')->nullable();

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
        Schema::dropIfExists('twp_grants');
    }
};
