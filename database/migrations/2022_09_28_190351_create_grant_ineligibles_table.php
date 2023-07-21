<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        if (Schema::hasTable('yeaf_ineligibles')) {
            Schema::table('yeaf_ineligibles', function (Blueprint $table) {
                // Check if the unique index already exists
                $indexExists = DB::select("SELECT COUNT(*) as count FROM pg_indexes WHERE indexname = 'yeaf_ineligibles_code_unique'");

                // If the index does not exist, create it
                if (! $indexExists) {
                    $table->unique('code_id');
                }
            });
        }

        if (! Schema::hasTable('yeaf_grant_ineligibles')) {
            Schema::create('yeaf_grant_ineligibles', function (Blueprint $table) {
                $table->id();

                $table->bigInteger('grant_id')->nullable();
                $table->foreign('grant_id')->references('grant_id')->on('yeaf_grants');

                $table->string('ineligible_code_id')->nullable();

                $table->string('created_by');
                $table->boolean('cleared_flag')->comment('true - error has been cleared');
                $table->string('ineligible_code_type', 1)->nullable()->comment('D = Denied status reason, P = Pending status reason');

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
        if (Schema::hasTable('yeaf_ineligibles')) {
            Schema::table('yeaf_ineligibles', function (Blueprint $table) {
                $table->dropUnique('code_id');
            });
        }

        Schema::dropIfExists('yeaf_grant_ineligibles');
    }
};
