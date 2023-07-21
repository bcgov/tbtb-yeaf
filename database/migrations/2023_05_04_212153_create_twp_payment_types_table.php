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
        Schema::create('twp_payment_types', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->boolean('active_flag')->default(true);
            $table->timestamps();
        });

        // seed the table
        DB::table('twp_payment_types')->insert([
            ['title' => 'Tuition and Fees',
                'active_flag' => true, ],
            ['title' => 'Grant',
                'active_flag' => true, ],
        ]);

        Schema::table('twp_payments', function (Blueprint $table) {
            $table->bigInteger('twp_payment_type_id')->default(1);

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('twp_payments', function (Blueprint $table) {
            $table->dropColumn('updated_by');
            $table->dropColumn('created_by');
            $table->dropColumn('twp_payment_type_id');
        });
        Schema::dropIfExists('twp_payment_types');
    }
};
