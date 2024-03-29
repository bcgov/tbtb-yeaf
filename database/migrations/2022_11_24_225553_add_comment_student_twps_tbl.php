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
        if (Schema::hasTable('twp_students')) {
            if (! Schema::hasColumn('twp_students', 'comment')) {
                Schema::table('twp_students', function (Blueprint $table) {
                    $table->text('comment')->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('twp_students')) {
            if (Schema::hasColumn('twp_students', 'comment')) {
                Schema::table('twp_students', function (Blueprint $table) {
                    $table->dropColumn('comment');
                });
            }
        }
    }
};
