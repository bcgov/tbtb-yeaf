<?php

namespace App\Models\Twp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Application extends Model
{
    use HasFactory;

    public $table = 'twp_applications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['twp_student_id', 'received_date', 'application_status', 'twp_status', 'denial_reason', 'exception_comments',
        'institution_student_number', 'apply_twp', 'apply_lfg', 'confirmation_enrolment', 'sabc_app_number',
        'fao_name', 'fao_email', 'fao_phone', ];

    public function reasons()
    {
        return $this->belongsToMany(
            'App\Models\Twp\Reason', // The model to access to
            'App\Models\Twp\ApplicationReason', // The intermediate table that connects the Application with the Reason.
            'application_id',                 // The column of the intermediate table that connects to this model by its ID.
            'reason_id',              // The column of the intermediate table that connects the Reason by its ID.
            'id',                      // The column that connects this model with the intermediate model table.
            'id'               // The column of the Reason table that ties it to the Application.
        );
    }

    public function student()
    {
        return $this->belongsTo('App\Models\Twp\Student', 'twp_student_id', 'id');
    }

    public function program()
    {
        return $this->hasOne('App\Models\Twp\Program', 'twp_application_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Twp\Payment', 'twp_application_id', 'id');
    }

    public function grants()
    {
        return $this->hasMany('App\Models\Twp\Grant', 'twp_application_id', 'id')->orderByDesc('created_at');
    }

    public function processClean()
    {
        $rows = DB::select('select * from twp_clean');
        //loop thru all records
        foreach ($rows as $row) {
            $name = Str::title($row->Name);
            $arr_name = explode(', ', $name);
            $fname = Str::title($arr_name[1]);
            $lname = Str::title($arr_name[0]);
            $dob = is_null($row->{'Date of Birth'}) ? null : date('Y-m-d', strtotime($row->{'Date of Birth'}));
            $email = is_null($row->{'Email Address'}) ? null : Str::lower($row->{'Email Address'});
            $gender = is_null($row->Gender) ? null : Str::upper($row->Gender);
            $citizen = is_null($row->Citizenship) ? null : Str::title($row->Citizenship);
            $pen = $row->{'PEN No'};
            $resident = ! is_null($row->{'BC resident'}) && Str::upper($row->{'BC resident'}) == 'Y';
            $indigen = is_null($row->Indigeneity) ? null : Str::title($row->Indigeneity);

            //look for the student
            $s = \App\Models\Twp\Student::where('name', $name)->where('birth_date', $dob)->first();

            //if does not exist, then create new student
            if (is_null($s)) {
                $s = new \App\Models\Twp\Student();
                $s->name = $name;
                $s->first_name = $fname;
                $s->last_name = $lname;
                $s->birth_date = $dob;
                $s->email = $email;
                $s->gender = $gender;
                $s->pen = $pen;
                $s->citizenship = $citizen;
                $s->bc_resident = $resident;
                $s->indigeneity = $indigen;
                $s->save();
            }

            if (! is_null($s)) {
                //add the record as an application

                $app = new \App\Models\Twp\Application();
                $app->twp_student_id = $s->id;
                $app->received_date = is_null($row->{'Application Received Date'}) ? null : date('Y-m-d', strtotime($row->{'Application Received Date'}));
                $app->application_status = $row->{'Application Status'};
                $app->twp_status = $row->{'Tuition Waiver Program Status'};
                $app->denial_reason = $row->{'Denial Reason'};
                $app->exception_comments = $row->{'Exception comments'};
                $app->institution_student_number = $row->{'Student No'};
                $app->apply_twp = true;
                $app->apply_lfg = false;
                $app->save();

                if (! is_null($app)) {
                    $p_type = null;
                    $p_len = null;

                    if (! is_null($row->{'Program Length'})) {
                        $p_type = 'year';
                        switch($row->{'Program Length'}) {
                            case 'Varies': break;

                            case '4 level -1/yr': $p_type = 'year';
                            $p_len = 4;
                            break;

                            case '7 weeks': $p_type = 'week';
                            $p_len = 7;
                            break;
                            case '43 weeks': $p_type = 'week';
                            $p_len = 43;
                            break;
                            case '10 weeks': $p_type = 'week';
                            $p_len = 10;
                            break;
                            case '16 weeks': $p_type = 'week';
                            $p_len = 16;
                            break;
                            case '17 weeks': $p_type = 'week';
                            $p_len = 17;
                            break;
                            case '24 weeks': $p_type = 'week';
                            $p_len = 24;
                            break;
                            case '26 weeks': $p_type = 'week';
                            $p_len = 26;
                            break;
                            case '28 weeks': $p_type = 'week';
                            $p_len = 28;
                            break;
                            case '33 weeks': $p_type = 'week';
                            $p_len = 33;
                            break;
                            case '34 weeks': $p_type = 'week';
                            $p_len = 34;
                            break;
                            case '39 weeks': $p_type = 'week';
                            $p_len = 39;
                            break;
                            case '43 weeks': $p_type = 'week';
                            $p_len = 43;
                            break;

                            case '1 month': $p_type = 'month';
                            $p_len = 1;
                            break;
                            case '9 months': $p_type = 'month';
                            $p_len = 9;
                            break;
                            case '18 months': $p_type = 'month';
                            $p_len = 18;
                            break;

                            case '1-3 days': $p_type = 'day';
                            $p_len = 3;
                            break;

                            case '1.3': $p_type = 'year';
                            $p_len = 1;
                            break;
                            case '1.5': $p_type = 'month';
                            $p_len = 18;
                            break;
                            case '2.5': $p_type = 'month';
                            $p_len = 30;
                            break;
                        }
                    }

                    $inst = \App\Models\Twp\Institution::where('name', $row->{'Name of Institution'})->first();

                    //add program
                    $p = new \App\Models\Twp\Program();
                    $p->twp_student_id = $s->id;
                    $p->twp_application_id = $app->id;
                    $p->institution_name = $row->{'Name of Institution'};
                    if (! is_null($inst)) {
                        $p->institution_twp_id = $inst->id;
                    }
                    $p->study_period_start_date = is_null($row->{'Study Period Start Date'}) ? null : date('Y-m-d', strtotime($row->{'Study Period Start Date'}));
                    $p->credential = $row->{'Credential'};
                    $p->program_length = $p_len;
                    $p->program_length_type = $p_type;
                    $p->total_estimated_cost = is_null($row->{'Total Estimated Program Cost'}) ? 0 : str_replace(',', '', $row->{'Total Estimated Program Cost'});
                    $p->student_status = $row->{'Student Status'};
                    $p->comments = $row->{'Comments'};
                    $p->save();

                    //add payments

                    if (! is_null($row->{'01-Oct-17'})) {
                        $m = new \App\Models\Twp\Payment();
                        $m->twp_student_id = $s->id;
                        $m->twp_program_id = $p->id;
                        $m->twp_application_id = $app->id;
                        $m->twp_payment_type_id = 1;
                        $m->payment_date = date('Y-m-d', strtotime('01-Oct-17'));
                        $m->payment_amount = str_replace(',', '', $row->{'01-Oct-17'});
                        $m->save();
                    }
                    if (! is_null($row->{'15-Jan-18'})) {
                        $m = new \App\Models\Twp\Payment();
                        $m->twp_student_id = $s->id;
                        $m->twp_program_id = $p->id;
                        $m->twp_application_id = $app->id;
                        $m->twp_payment_type_id = 1;
                        $m->payment_date = date('Y-m-d', strtotime('15-Jan-18'));
                        $m->payment_amount = str_replace(',', '', $row->{'15-Jan-18'});
                        $m->save();
                    }
                    if (! is_null($row->{'15-Aug-18'})) {
                        $m = new \App\Models\Twp\Payment();
                        $m->twp_student_id = $s->id;
                        $m->twp_program_id = $p->id;
                        $m->twp_application_id = $app->id;
                        $m->twp_payment_type_id = 1;
                        $m->payment_date = date('Y-m-d', strtotime('15-Aug-18'));
                        $m->payment_amount = str_replace(',', '', $row->{'15-Aug-18'});
                        $m->save();
                    }
                    if (! is_null($row->{'31-Dec-18'})) {
                        $m = new \App\Models\Twp\Payment();
                        $m->twp_student_id = $s->id;
                        $m->twp_program_id = $p->id;
                        $m->twp_application_id = $app->id;
                        $m->twp_payment_type_id = 1;
                        $m->payment_date = date('Y-m-d', strtotime('31-Dec-18'));
                        $m->payment_amount = str_replace(',', '', $row->{'31-Dec-18'});
                        $m->save();
                    }
                    if (! is_null($row->{'29-Mar-19'})) {
                        $m = new \App\Models\Twp\Payment();
                        $m->twp_student_id = $s->id;
                        $m->twp_program_id = $p->id;
                        $m->twp_application_id = $app->id;
                        $m->twp_payment_type_id = 1;
                        $m->payment_date = date('Y-m-d', strtotime('29-Mar-19'));
                        $m->payment_amount = str_replace(',', '', $row->{'29-Mar-19'});
                        $m->save();
                    }
                    if (! is_null($row->{'15-Jul-19'})) {
                        $m = new \App\Models\Twp\Payment();
                        $m->twp_student_id = $s->id;
                        $m->twp_program_id = $p->id;
                        $m->twp_application_id = $app->id;
                        $m->twp_payment_type_id = 1;
                        $m->payment_date = date('Y-m-d', strtotime('15-Jul-19'));
                        $m->payment_amount = str_replace(',', '', $row->{'15-Jul-19'});
                        $m->save();
                    }
                    if (! is_null($row->{'29-Nov-19'})) {
                        $m = new \App\Models\Twp\Payment();
                        $m->twp_student_id = $s->id;
                        $m->twp_program_id = $p->id;
                        $m->twp_application_id = $app->id;
                        $m->twp_payment_type_id = 1;
                        $m->payment_date = date('Y-m-d', strtotime('29-Nov-19'));
                        $m->payment_amount = str_replace(',', '', $row->{'29-Nov-19'});
                        $m->save();
                    }
                    if (! is_null($row->{'01-Feb-20'})) {
                        $m = new \App\Models\Twp\Payment();
                        $m->twp_student_id = $s->id;
                        $m->twp_program_id = $p->id;
                        $m->twp_application_id = $app->id;
                        $m->twp_payment_type_id = 1;
                        $m->payment_date = date('Y-m-d', strtotime('01-Feb-20'));
                        $m->payment_amount = str_replace(',', '', $row->{'01-Feb-20'});
                        $m->save();
                    }
                    if (! is_null($row->{'12-Jun-20'})) {
                        $m = new \App\Models\Twp\Payment();
                        $m->twp_student_id = $s->id;
                        $m->twp_program_id = $p->id;
                        $m->twp_application_id = $app->id;
                        $m->twp_payment_type_id = 1;
                        $m->payment_date = date('Y-m-d', strtotime('12-Jun-20'));
                        $m->payment_amount = str_replace(',', '', $row->{'12-Jun-20'});
                        $m->save();
                    }
                    if (! is_null($row->{'30-Oct-20'})) {
                        $m = new \App\Models\Twp\Payment();
                        $m->twp_student_id = $s->id;
                        $m->twp_program_id = $p->id;
                        $m->twp_application_id = $app->id;
                        $m->twp_payment_type_id = 1;
                        $m->payment_date = date('Y-m-d', strtotime('30-Oct-20'));
                        $m->payment_amount = str_replace(',', '', $row->{'30-Oct-20'});
                        $m->save();
                    }
                    if (! is_null($row->{'15-Feb-21'})) {
                        $m = new \App\Models\Twp\Payment();
                        $m->twp_student_id = $s->id;
                        $m->twp_program_id = $p->id;
                        $m->twp_application_id = $app->id;
                        $m->twp_payment_type_id = 1;
                        $m->payment_date = date('Y-m-d', strtotime('15-Feb-21'));
                        $m->payment_amount = str_replace(',', '', $row->{'15-Feb-21'});
                        $m->save();
                    }
                    if (! is_null($row->{'22-Jun-21'})) {
                        $m = new \App\Models\Twp\Payment();
                        $m->twp_student_id = $s->id;
                        $m->twp_program_id = $p->id;
                        $m->twp_application_id = $app->id;
                        $m->twp_payment_type_id = 1;
                        $m->payment_date = date('Y-m-d', strtotime('22-Jun-21'));
                        $m->payment_amount = str_replace(',', '', $row->{'22-Jun-21'});
                        $m->save();
                    }
                    if (! is_null($row->{'19-Oct-21'})) {
                        $m = new \App\Models\Twp\Payment();
                        $m->twp_student_id = $s->id;
                        $m->twp_program_id = $p->id;
                        $m->twp_application_id = $app->id;
                        $m->twp_payment_type_id = 1;
                        $m->payment_date = date('Y-m-d', strtotime('19-Oct-21'));
                        $m->payment_amount = str_replace(',', '', $row->{'19-Oct-21'});
                        $m->save();
                    }
                    if (! is_null($row->{'18-Feb-22'})) {
                        $m = new \App\Models\Twp\Payment();
                        $m->twp_student_id = $s->id;
                        $m->twp_program_id = $p->id;
                        $m->twp_application_id = $app->id;
                        $m->twp_payment_type_id = 1;
                        $m->payment_date = date('Y-m-d', strtotime('18-Feb-22'));
                        $m->payment_amount = str_replace(',', '', $row->{'18-Feb-22'});
                        $m->save();
                    }
                }
            }
        }
    }
}
