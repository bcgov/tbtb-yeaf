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
        if (! Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('idir_user_guid')->nullable();

                $table->dropUnique('users_email_unique');
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
        if (! Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('email');
                $table->dropColumn('idir_user_guid');
            });
        }
    }
};
