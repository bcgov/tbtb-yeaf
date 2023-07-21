<?php

namespace App\Http\Requests\Twp;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentStoreRequest extends FormRequest
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
            'twp_student_id.*' => 'Student ID field is not valid.',
            'twp_application_id.*' => 'Application ID field is not valid.',
            'twp_program_id.*' => 'Program ID field is not valid.',
            'payment_date.*' => 'Payment Date field is not valid.',
            'payment_amount.*' => 'Payment Amount field is not valid.',
            'twp_payment_type_id.*' => 'Payment Type field is not valid.',
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
            'twp_student_id' => 'required',
            'twp_application_id' => 'required|numeric',
            'twp_payment_type_id' => 'required|numeric',
            'twp_program_id' => 'nullable',
            'payment_date' => 'required|date_format:Y-m-d',
            'payment_amount' => 'required|numeric',
            'created_by' => 'required',
            'updated_by' => 'required',

        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'created_by' => Str::upper(Auth::user()->user_id),
            'updated_by' => Str::upper(Auth::user()->user_id),
        ]);
    }
}
