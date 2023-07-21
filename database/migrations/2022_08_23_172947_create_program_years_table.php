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
        if (! Schema::hasTable('yeaf_program_years')) {
            Schema::create('yeaf_program_years', function (Blueprint $table) {
                $table->id();
                $table->integer('program_year_id')->unique();
                $table->string('year_start', 4)->nullable();
                $table->string('year_end', 4)->nullable();
                $table->double('grant_amount', null, 2)->default(0);

                $table->integer('max_years_allowed')->default(4)->comment('Maximum number of years a student is allowed to be in the program');
                $table->integer('min_age')->comment('Minimum age required to apply');
                $table->integer('max_age')->comment('Maximum age required to apply');

                $table->timestamps();
            });

            DB::update("alter table yeaf_program_years alter column program_year_id set default nextval('yeaf_program_years_id_seq'::regclass)-1;");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yeaf_program_years');
    }
};
