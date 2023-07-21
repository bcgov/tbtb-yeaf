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
        if (! Schema::hasTable('yeaf_students')) {
            Schema::create('yeaf_students', function (Blueprint $table) {
                $table->id();
                $table->string('student_id')->unique();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('sin', 11);
                $table->date('birth_date')->nullable();
                $table->string('address');
                $table->string('city');
                $table->string('province', 2)->nullable();
                $table->string('postal_code', 7)->nullable();
                $table->string('country')->default('CAN');
                $table->string('tele', 20)->nullable();
                $table->string('email')->nullable();
                $table->string('gender', 1)->nullable();
                $table->double('life', null, 2)->default(0)->nullable();
                $table->double('overaward_amount', null, 2)->default(0)->nullable();
                $table->boolean('overaward_flag')->default(false);
                $table->double('overaward_deducted_amount', null, 2)->default(0)->nullable();
                $table->boolean('investigate')->default(false);
                $table->string('pen', 9)->nullable();
                $table->boolean('pd')->default(false);
                $table->string('institution_student_number', 12)->nullable();
                $table->timestamps();
            });
            DB::update('alter sequence yeaf_students_id_seq restart with 43;');
            DB::update("alter table yeaf_students alter column student_id set default nextval('yeaf_students_id_seq'::regclass)-1;");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yeaf_students');
    }
};
