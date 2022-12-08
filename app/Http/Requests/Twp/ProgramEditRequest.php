<?php

namespace App\Http\Requests\Twp;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ProgramEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.*' => 'Program ID field is not valid.',
            'study_period_start_date.*' => 'Study Period Start Date field is not valid.',
            'student_status.*' => 'Student Status field is not valid.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'twp_student_id' => 'required',
            'institution_name' => 'required',
            'credential' => 'nullable',
            'study_period_start_date' => 'present|date_format:Y-m-d|nullable',
            'program_length' => 'present|numeric|nullable',
            'program_length_type' => 'present|in:day,week,month,year|nullable',
            'total_estimated_cost' => 'present|numeric|nullable',
            'student_status' => 'present|in:APPROVED,DENIED,IN PROGRESS,APPROVED ON APPEAL,WITHDRAWN|nullable',
            'comments' => 'nullable',
            'contact_name' => 'nullable',
            'contact_email' => 'nullable',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (isset($this->contact_name)) {
            $this->merge(['contact_name' => Str::title($this->contact_name)]);
        }
        if (isset($this->contact_email)) {
            $this->merge(['contact_email' => Str::lower($this->contact_email)]);
        }
    }
}
