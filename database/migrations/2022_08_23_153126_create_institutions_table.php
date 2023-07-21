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
        if (! Schema::hasTable('yeaf_institutions')) {
            Schema::create('yeaf_institutions', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('institution_id')->unique();
                $table->string('name')->nullable();
                $table->string('address');
                $table->string('city');
                $table->string('province', 3)->nullable();
                $table->string('state', 3)->nullable();
                $table->string('postal_code', 7)->nullable();
                $table->string('zip_code', 6)->nullable();
                $table->string('country', 7)->nullable();
                $table->string('tele', 16)->nullable();
                $table->string('fax', 16)->nullable();
                $table->timestamps();
            });
            DB::update("alter table yeaf_institutions alter column institution_id set default nextval('yeaf_institutions_id_seq'::regclass)-1;");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yeaf_institutions');
    }
};
