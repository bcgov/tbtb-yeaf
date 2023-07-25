<?php

namespace App\Http\Controllers\Yeaf;

use App\Models\Yeaf\Appeal;
use App\Models\Yeaf\Batch;
use App\Models\Yeaf\Comment;
use App\Models\Yeaf\Grant;
use App\Models\Yeaf\GrantIneligible;
use App\Models\Yeaf\Ineligible;
use App\Models\Yeaf\Institution;
use App\Models\Yeaf\ProgramYear;
use App\Models\Yeaf\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Response;

class ReportController extends Controller
{
    public function switchOn(Request $request)
    {
        // Set traffic light to true
        $traffic_light = true;

        // Store traffic light value in cache for 60 seconds
        Cache::put('traffic_light', $traffic_light, 60);

        return Response::json([
            'status' => 'success',
            'message' => 'Traffic light set to true',
        ]);
    }

    public function reportsStudents(Request $request)
    {
        return response()->json(Student::select('student_id', 'first_name', 'last_name', 'sin', 'birth_date',
            'address', 'city', 'province', 'postal_code', 'country', 'tele', 'email', 'gender', 'life',
            'overaward_amount', 'overaward_flag', 'overaward_deducted_amount', 'investigate', 'pen', 'pd',
            'institution_student_number')->get(), 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $type): \Illuminate\Http\JsonResponse
    {
        if (Cache::has('traffic_light') && Cache::get('traffic_light') == true) {
            // Traffic light is set and true in the cache
            switch ($type) {
                case 'students': return $this->students($request);
                case 'institutions': return $this->institutions($request);
                case 'grants': return $this->grants($request);
                case 'staff': return $this->staff($request);
                case 'ineligibles': return $this->ineligibles($request);
                case 'grantIneligibles': return $this->grantIneligibles($request);
                case 'comments': return $this->comments($request);
                case 'appeals': return $this->appeals($request);
                case 'programYears': return $this->programYears($request);
                case 'batches': return $this->batches($request);
                case 'studentsWithGrants': return $this->studentsWithGrants($request);
            }
        }

        // Traffic light is not set or is false in the cache
        return response()->json([
            'status' => 'fail',
            'message' => 'Access denied.',
        ]);
    }

    public function students(Request $request)
    {
        return response()->json(Student::select('student_id', 'first_name', 'last_name', 'sin', 'birth_date',
            'address', 'city', 'province', 'postal_code', 'country', 'tele', 'email', 'gender', 'life',
            'overaward_amount', 'overaward_flag', 'overaward_deducted_amount', 'investigate', 'pen', 'pd',
            'institution_student_number')->get(), 200);
    }

    public function grants(Request $request)
    {
        return response()->json(Grant::select('grant_id', 'institution_id', 'student_id',
            'program_year_id', 'program_code', 'cheque_batch_number', 'officer_user_id', 'creator_user_id',
            'update_user_id', 'application_number', 'age', 'eligible_need', 'total_award', 'unmet_need',
            'total_bcsl_award', 'total_yeaf_award', 'total_yeaf_award_remit', 'overaward', 'overaward_calc',
            'overaward_deducted_amount', 'reason_for_ineligibility', 'program_name', 'program_other_description',
            'status_code', 'date_issued_month', 'date_issued_year', 'application_type', 'letter_text',
            'custom_pending_reason', 'custom_denial_reason', 'study_period_completion', 'confirmation_bcsl_remission',
            'reassess', 'overaward_cleared', 'withdrawal', 'study_start_date', 'study_end_date',
            'bcsl_remission', 'letter_date', 'cheque_issue_date', 'withdrawal_date', 'status_date', 'last_letter_produced_date',
            'application_receive_date', 'last_evaluation_date')->get());
    }

    public function studentsWithGrants(Request $request)
    {
        return response()->json(Student::with('grants.grantIneligibles', 'comments', 'grants.appeals', 'grants.py')->get());
    }

    public function staff(Request $request)
    {
        $users = \App\Models\User::select('user_id', 'first_name', 'last_name', 'disabled',
            'access_type', 'tele', 'email')->whereHas('roles', function ($q) {
                $q->whereIn('name', ['YEAF Admin', 'YEAF User']);
            })->get();

        return response()->json($users);
    }

    public function ineligibles(Request $request)
    {
        return response()->json(Ineligible::select('code_id', 'description', 'active_flag',
            'code_type', 'paragraph_text')->get());
    }

    public function grantIneligibles(Request $request)
    {
        return response()->json(GrantIneligible::select('grant_id', 'ineligible_code_id',
            'created_by', 'cleared_flag', 'ineligible_code_type')->get());
    }

    public function comments(Request $request)
    {
        return response()->json(Comment::select('student_id', 'user_id', 'comment_text')->get());
    }

    public function appeals(Request $request)
    {
        return response()->json(Appeal::select('appeal_id', 'student_id', 'grant_id', 'adjudicated_by_user_id',
            'updated_by_user_id', 'appeal_code', 'appeal_date', 'status_code', 'status_affective_date',
            'other_appeal_explain')->get());
    }

    public function institutions(Request $request)
    {
        return response()->json(Institution::select('institution_id', 'name', 'address', 'city',
            'province', 'state', 'postal_code', 'zip_code', 'country', 'tele', 'fax')->get());
    }

    public function programYears(Request $request)
    {
        return response()->json(ProgramYear::select('program_year_id', 'year_start', 'year_end',
            'grant_amount', 'max_years_allowed', 'min_age', 'max_age')->get());
    }

    public function batches(Request $request)
    {
        return response()->json(Batch::select('batch_number', 'batch_date')->get());
    }

    private function jsonFileDownload($name, $data)
    {
        header('Content-type: application/json');
        header('Content-Disposition: attachment; filename='.$name.'.json');

        return $data;
    }
}
