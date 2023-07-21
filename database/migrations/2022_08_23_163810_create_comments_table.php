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
        if (! Schema::hasTable('yeaf_comments')) {
            Schema::create('yeaf_comments', function (Blueprint $table) {
                $table->id();

                $table->string('student_id');
                $table->foreign('student_id')->references('student_id')->on('yeaf_students')->onDelete('cascade');

                $table->string('user_id');
                $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

                $table->longText('comment_text')->nullable();
                $table->softDeletes();
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
        Schema::dropIfExists('yeaf_comments');
    }
};
