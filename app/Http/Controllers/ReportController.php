<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Maintenance', ['status' => true, 'page' => 'reports']);
    }

    public function students(Request $request)
    {
        return $this->jsonFileDownload('students', \App\Models\Student::select('student_id', 'first_name', 'last_name', 'sin', 'birth_date',
            'address', 'city', 'province', 'postal_code', 'country', 'tele', 'email', 'gender', 'life',
            'overaward_amount', 'overaward_flag', 'overaward_deducted_amount', 'investigate', 'pen', 'pd',
            'institution_student_number')->get());
    }

    public function grants(Request $request)
    {
        return $this->jsonFileDownload('grants', \App\Models\Grant::select('grant_id', 'institution_id', 'student_id',
            'program_year_id', 'program_code', 'checque_batch_number', 'officier_user_id', 'creator_user_id',
            'update_user_id', 'application_number', 'age', 'eligible_need', 'total_award', 'unmet_need',
            'total_bcsl_award', 'total_yeaf_award', 'total_yeaf_award_remit', 'overaward', 'overaward_calc',
            'overaward_deducted_amount', 'reason_for_ineligibility', 'program_name', 'program_other_description',
            'status_code', 'date_issued_month', 'date_issued_year', 'application_type', 'letter_text',
            'custom_pending_reason', 'custom_denied_reason', 'study_period_completion', 'confirmation_bcsl_remission',
            'reassess', 'overaward_cleared', 'withdrawal', 'study_start_date', 'study_end_date',
            'bcsl_remission', 'letter_date', 'cheque_issue_date', 'withdrawal_date', 'status_date', 'last_letter_produced_date',
            'application_receive_date', 'last_evaluation_date')->get());
    }

    public function studentsWithGrants(Request $request)
    {
        return $this->jsonFileDownload('students_with_grants', \App\Models\Student::with('grants.grantIneligibles', 'comments', 'grants.appeals', 'grants.py')->get());
    }

    public function staff(Request $request)
    {
        return $this->jsonFileDownload('staff', \App\Models\User::select('user_id', 'first_name', 'last_name', 'disabled',
            'access_type', 'tele', 'email')->get());
    }

    public function grantIneligibles(Request $request)
    {
        return $this->jsonFileDownload('grantIneligibles', \App\Models\GrantIneligible::select('code_id', 'description', 'active_flag',
            'code_type', 'paragraph_text')->get());
    }

    public function comments(Request $request)
    {
        return $this->jsonFileDownload('comments', \App\Models\Comment::select('student_id', 'user_id', 'comment_text')->get());
    }

    public function appeals(Request $request)
    {
        return $this->jsonFileDownload('appeals', \App\Models\Appeal::select('appeal_id', 'student_id', 'grant_id', 'adjudicated_by_user_id',
            'updated_by_user_id', 'appeal_code', 'appeal_date', 'status_code', 'status_affective_date',
            'other_appeal_explain')->get());
    }

    public function institutions(Request $request)
    {
        return $this->jsonFileDownload('institutions', \App\Models\Institution::select('institution_id', 'name', 'address', 'city',
            'province', 'state', 'postal_code', 'zip_code', 'country', 'tele', 'fax')->get());
    }

    public function py(Request $request)
    {
        return $this->jsonFileDownload('program_years', \App\Models\ProgramYear::select('program_year_id', 'year_start', 'year_end',
            'grant_amount', 'max_years_allowed', 'min_age', 'max_age')->get());
    }

    private function jsonFileDownload($name, $data)
    {
        header('Content-type: application/json');
        header('Content-Disposition: attachment; filename='.$name.'.json');

        return $data;
    }
}
