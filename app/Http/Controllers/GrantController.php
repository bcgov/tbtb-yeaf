<?php

namespace App\Http\Controllers;

use App\Http\Requests\GrantEditRequest;
use App\Models\Appeal;
use App\Models\Grant;
use App\Models\GrantIneligible;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use phpDocumentor\Reflection\Types\Boolean;
use Response;

class GrantController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $grants = new Grant();
        $grants = $this->paginateGrants($grants);

        return Inertia::render('Grants', ['status' => true, 'results' => $grants]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Grant  $grant
     * @return \Illuminate\Http\Response
     */
    public function show(Grant $grant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Grant  $grant
     * @return \Illuminate\Http\Response
     */
    public function edit(Grant $grant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Grant  $grant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grant $grant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Grant  $grant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grant $grant)
    {
        //
    }
/*
Call ClearFlags
Call CheckAge(False, True)
' check how many years the person has received money
   Call CheckMaxYrs(False, True)
   Call datewatch(False, True)
   Call CheckProgramYr(False, True)
   Call SetStatus
   Call DisplayBasedOnStatus
'--> turn this off to see if it will stop denial reasons from disappearing when new
'--> Me.Refresh
 ' Me.sfrmAppIneligible.Requery
*/

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Grant  $grant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function evaluateApp(GrantEditRequest $request, Grant $grant)
    {
//        dd($request->all());

        Grant::update($request->validated());

        $this->clearFlags($request, $grant);

        list($msg, $app_ineligible, $appeal_status) = $this->checkAge(true, false, $grant);
        if($msg != '' || $app_ineligible == true){
            return Response::json(['status' => true, "msg" => $msg, "app_ineligible" => $app_ineligible, "appeal_status" => $appeal_status, "grant" => $grant]);
        }

        list($msg, $app_ineligible) = $this->checkMaxYears(true, false, $grant, $msg, $app_ineligible);
        if($msg != '' || $app_ineligible == true){
            return Response::json(['status' => true, "msg" => $msg, "app_ineligible" => $app_ineligible, "appeal_status" => $appeal_status, "grant" => $grant]);
        }

        list($msg, $app_ineligible) = $this->datewatch(true, false, $grant, $msg, $app_ineligible);
        if($msg != '' || $app_ineligible == true){
            return Response::json(['status' => true, "msg" => $msg, "app_ineligible" => $app_ineligible, "appeal_status" => $appeal_status, "grant" => $grant]);
        }
        list($msg, $app_ineligible) = $this->checkProgramYear(true, false, $grant, $msg, $app_ineligible);



//        list($msg, $app_ineligible, $appeal_status) = $this->checkAge(false, true, $grant);
//        list($msg, $app_ineligible) = $this->checkMaxYears(false, true, $grant, $msg, $app_ineligible);
//        list($msg, $app_ineligible) = $this->datewatch(false, true, $grant, $msg, $app_ineligible);
//        list($msg, $app_ineligible) = $this->checkProgramYear(false, true, $grant, $msg, $app_ineligible);
//        $this->setStatus($grant);

//        return Redirect::route('students.show', [$grant->student()->id]);
        $grant = Grant::find($grant->id);


        return Response::json(['status' => true, "msg" => $msg, "app_ineligible" => $app_ineligible, "appeal_status" => $appeal_status, "grant" => $grant]);

    }


    private function setStatus(Grant $grant)
    {
        $record = null;
        if(!is_null($grant)){
//            $record = DB::select(DB::raw("select yeaf_grants.grant_id,
//       SUM(CASE WHEN ygi.ineligible_code_type = 'P' THEN 1 ELSE 0 END) AS PendingCnt,
//       SUM(CASE WHEN ygi.ineligible_code_type = 'D' THEN 1 ELSE 0 END) AS DeniedCnt
//from yeaf_grants
//    join yeaf_grant_ineligibles ygi on yeaf_grants.grant_id = ygi.grant_id
//WHERE ygi.cleared_flag=false AND yeaf_grants.grant_id=$grant->grant_id
//group by yeaf_grants.grant_id
//order by yeaf_grants.grant_id asc ;"));

            $record = Grant::select('grant_id')->withCount(['grantIneligibles as PendingCnt' => function($query) {$query->where('ineligible_code_type', 'P');}],'ineligible_code_type')->withCount(['grantIneligibles as DeniedCnt' => function($query) {$query->where('ineligible_code_type', 'D');}],'id')->where('grant_id', $grant->grant_id)->groupBy('grant_id')->orderBy('grant_id', 'ASC')->first();
        }

        if(!is_null($record)){
            $record = $record[0];
            if($record->DeniedCnt > 0){
                $status = 'D';
            }elseif ($record->PendingCnt > 0){
                $status = 'P';
            }
        }else{
            $status = 'P';
        }

        if($status != '' && $status != $grant->status_code){
            $grant->status_code = $status;
            $grant->status_date = date('Y-m-d', strtotime('now'));
            $grant->save();
        }

        return null;
    }

    private function checkProgramYear($messageFlag, $createIneligibleFlag, Grant $grant, $msg="", $app_ineligible=false)
    {
        //if existing app start date is greater than this end date or existing app end date is less than this start date,
        //then this app is okay, otherwise it overlaps an existing app
        $same_program_year = Grant::where('student_id', $grant->student_id)
            ->where('total_yeaf_award', '>', 0)
            ->where('grant_id', '!=', $grant->grant_id)
            ->where('program_year_id', $grant->py->program_year_id)
            ->get();
        if($same_program_year->count() > 0){
            if($messageFlag){
                $msg = "There are other applications for this person with an award for the same program year.";
            }
            if($createIneligibleFlag){
                $this->addIneligibleReason($grant, "11");
                $app_ineligible = true;
            }
        }


        return [$msg, $app_ineligible];

    }

    private function datewatch($messageFlag, $createIneligibleFlag, Grant $grant, $msg="", $app_ineligible=false)
    {
        $date1 = '';
        $date2 = '';
        $dateprogram = 0;
        if(!is_null($grant->study_start_date) && !is_null($grant->study_end_date)){
            $date1 = new Carbon($grant->study_start_date);
            $date2 = new carbon($grant->study_end_date);

            $dateprogram = $date2->diffInDays($date1);
            if($dateprogram < 81){
                if($messageFlag){
                    $msg = "The end date is shorter than 12 weeks. <br/><br/>This application does not meet the course length criteria.<br/><br/>Program Length Error";
                }
                if($createIneligibleFlag){
                    $this->addIneligibleReason($grant, "05");
                    $app_ineligible = true;
                }
            }

            //if existing app start date is greater than this end date or existing app end date is less than this start date
            //then this app is okay, otherwise it overlaps an existing app
            $same_program_period = Grant::where('student_id', $grant->student_id)
                ->where('study_start_date', '<=', date('Y-m-d', strtotime($grant->study_end_date)))
                ->where('study_end_date', '>=', date('Y-m-d', strtotime($grant->study_start_date)))
//                ->whereNot(DB::raw('("study_start_date" > ' . date('Y-m-d', strtotime($grant->study_end_date)) . ' OR "study_end_date" < ' . date('Y-m-d', strtotime($grant->study_start_date)) . ')'))
                ->where('grant_id', '!=', $grant->grant_id)
                ->where('total_yeaf_award', '>', 0)
                ->get();
            if($same_program_period->count() > 0){
                if($messageFlag){
                    $msg = "There are other applications with overlapping program periods.";
                }
                if($createIneligibleFlag){
                    $this->addIneligibleReason($grant, "10");
                    $app_ineligible = true;
                }
            }
        }

        return [$msg, $app_ineligible];

    }

    private function checkMaxYears($messageFlag, $createIneligibleFlag, Grant $grant, $msg="", $app_ineligible=false)
    {

        /*SELECT tblApplication.ProgYearID
        FROM tblStudent INNER JOIN tblApplication ON tblStudent.studentID = tblApplication.studentID
        WHERE (((tblApplication.total_YEAF_award)>0) AND ((tblApplication.grantID)<>[forms]![frmstudent]![sfrmApplication]![GrantId])
        AND ((tblApplication.studentID)=[forms]![frmStudent]![studentID]))
        GROUP BY tblApplication.ProgYearID;
        */
/*
 * select yeaf_grants.program_year_id from yeaf_students
 * inner join yeaf_grants on yeaf_students.student_id = yeaf_grants.student_id
 * where yeaf_grants.total_yeaf_award > 0 AND yeaf_grants.grant_id <> 511 AND yeaf_grants.student_id='5421'
group by yeaf_grants.program_year_id;*/
        $results = DB::select( DB::raw("select yeaf_grants.program_year_id from yeaf_students
    inner join yeaf_grants on yeaf_students.student_id = yeaf_grants.student_id
where yeaf_grants.total_yeaf_award > 0 AND yeaf_grants.grant_id <> $grant->id AND yeaf_grants.student_id='" . $grant->student->student_id . "'
group by yeaf_grants.program_year_id"));
        if(!is_null($results) && sizeof($results) >= $grant->py->max_years_allowed){
            if($messageFlag){
                $msg = "This student has received funding for " . sizeof($results) . ".";
            }
            if($createIneligibleFlag){
                $this->addIneligibleReason($grant, "09");
                $app_ineligible = true;
            }
        }

        return [$msg, $app_ineligible];
    }

    private function clearFlags(Request $request, Grant $grant)
    {
        $grantIneligibles = GrantIneligible::where('grant_id', $grant->id)->where('created_by', 'ilike', 'SYS:%')->get();
        foreach ($grantIneligibles as $g){
            $g->cleared_flag = true;
            $g->save();
        }
    }

    private function checkAge($messageFlag, $createIneligibleFlag, Grant $grant)
    {
        $age_within_range = null;
        $appeal_status = null;
        $app_ineligible = false;
        $age = $this->ageCalc($grant);
        $msg = "";

        //now check to see if there is an age related appeal
        if($age > $grant->py->max_age){
            $age_within_range = "Over the age of " . $grant->py->max_age;
            $appeal_status = Appeal::where('student_id', $grant->student->student_id)
                ->where('appeal_code', 'OA')->orderBy('appeal_date', 'desc')->first();

        }else if($age < $grant->py->min_age){
            $age_within_range = "Under the age of " . $grant->py->min_age;
            $appeal_status = Appeal::where('student_id', $grant->student->student_id)
                ->where('appeal_code', 'UA')->orderBy('appeal_date', 'desc')->first();
        }

        if(!is_null($appeal_status)){
            $appeal_status = $appeal_status->status_code;
        }

        if($createIneligibleFlag && !is_null($age_within_range) && !is_null($appeal_status) && $appeal_status != "A"){
            $this->addIneligibleReason($grant, "02");
            $app_ineligible = true;
        }


        if(!is_null($age_within_range)){
            if(is_null($appeal_status)){
                $msg = "Student is " . $age_within_range . " and there is no appeal entered.";
            }else{
                $msg = "Student is " . $age_within_range . " and there is an age appeal with status of ";
                $msg .= match ($appeal_status) {
                    "A" => "Approved",
                    "P" => "Pending",
                    "D" => "Denied",
                };
            }
        }
        return [$msg, $app_ineligible, $appeal_status];
    }

    private function ageCalc(Grant $grant){
        $yDate = date('Y', strtotime('now'));
        $mDate = date('mmdd', strtotime('now'));
        if(!is_null($grant->study_start_date)){
            $yDate = date('Y', strtotime($grant->study_start_date));
            $mDate = date('mmdd', strtotime($grant->study_start_date));
        }
        $birth_date_y = date('Y', strtotime($grant->student->birth_date));
        $birth_date_m = date('mmdd', strtotime($grant->student->birth_date));

        return (intval($yDate) - intval($birth_date_y)) + ( intval($mDate) < intval($birth_date_m) );

    }

    private function addIneligibleReason(Grant $grant, $ineligible_code_id)
    {
        $grant_ineligibles = GrantIneligible::where('grant_id', $grant->grant_id)->where('ineligible_code_id', $ineligible_code_id)->get();
        if($grant_ineligibles->count() > 0){
            foreach ($grant_ineligibles as $grant_ineligible){
                $grant_ineligible->cleared_flag = false;
                $grant_ineligible->save();
            }
        }else{
            $this->createGrantIneligible($grant, "02", "D");
        }
    }

    private function createGrantIneligible(Grant $grant, $ineligible_code_id, $ineligible_code_type)
    {
        $grant_ineligible = new GrantIneligible();
        $grant_ineligible->grant_id = $grant->id;
        $grant_ineligible->ineligible_code_id = $ineligible_code_id;
        $grant_ineligible->created_by = "SYS:" . Auth::user()->user_id;
        $grant_ineligible->cleared_flag = false;
        $grant_ineligible->ineligible_code_type = $ineligible_code_type;
        $grant_ineligible->save();
    }
}
