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
        if (Schema::hasTable('twp_programs')) {
            if (! Schema::hasColumn('twp_programs', 'institution_twp_id')) {
                Schema::table('twp_programs', function (Blueprint $table) {
                    $table->bigInteger('institution_twp_id')->nullable();
                    $table->string('credential_type')->nullable();
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
        if (Schema::hasTable('twp_programs')) {
            if (Schema::hasColumn('twp_programs', 'institution_twp_id')) {
                Schema::table('twp_programs', function (Blueprint $table) {
                    $table->dropColumn('credential_type');
                    $table->dropColumn('institution_twp_id');
                });
            }
        }
    }
};
