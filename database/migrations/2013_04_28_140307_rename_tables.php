<?php

use Illuminate\Database\Migrations\Migration;
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
        if (Schema::hasTable('yeaf_sessions')) {
            Schema::rename('yeaf_sessions', 'sessions');
            Schema::rename('yeaf_users', 'users');
            Schema::rename('yeaf_roles', 'roles');
            Schema::rename('yeaf_role_user', 'role_user');

            Schema::rename('yeaf_cache', 'cache');
            Schema::rename('yeaf_cache_locks', 'cache_locks');
            Schema::rename('yeaf_failed_jobs', 'failed_jobs');
            Schema::rename('yeaf_personal_access_tokens', 'personal_access_tokens');

 //           Schema::rename('yeaf_grant_twps', 'twp_grants');
            Schema::rename('yeaf_program_history_twps', 'twp_program_histories');
            Schema::rename('yeaf_institution_twps', 'twp_institutions');
            Schema::rename('yeaf_reason_twps', 'twp_reasons');
            Schema::rename('yeaf_payment_twps', 'twp_payments');
            Schema::rename('yeaf_program_twps', 'twp_programs');
            Schema::rename('yeaf_application_twps', 'twp_applications');
            Schema::rename('yeaf_student_twps', 'twp_students');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('sessions')) {
//            Schema::rename('twp_grants', 'yeaf_grant_twps');
            Schema::rename('twp_program_histories', 'yeaf_program_history_twps');
            Schema::rename('twp_institutions', 'yeaf_institution_twps');
            Schema::rename('twp_reasons', 'yeaf_reason_twps');
            Schema::rename('twp_payments', 'yeaf_payment_twps');
            Schema::rename('twp_programs', 'yeaf_program_twps');
            Schema::rename('twp_applications', 'yeaf_application_twps');
            Schema::rename('twp_students', 'yeaf_student_twps');

            Schema::rename('personal_access_tokens', 'yeaf_personal_access_tokens');
            Schema::rename('failed_jobs', 'yeaf_failed_jobs');
            Schema::rename('cache_locks', 'yeaf_cache_locks');
            Schema::rename('cache', 'yeaf_cache');
            Schema::rename('role_user', 'yeaf_role_user');
            Schema::rename('roles', 'yeaf_roles');
            Schema::rename('users', 'yeaf_users');
            Schema::rename('sessions', 'yeaf_sessions');
        }
    }
};
