<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StaffEditRequest extends FormRequest
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
            'tele.*' => 'Telephone number field is not valid.',
            'access_type.*' => 'Access Type field is not valid.',
            'disabled.*' => 'Status field is not valid.',
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
            'disabled' => 'required|boolean',
            'access_type' => 'required|in:A,U',
            'tele' => 'max:15|string|nullable',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {

        if (isset($this->tele)) {
            $this->merge(['tele' => preg_replace('/\D/', '', $this->tele)]);
        }

        if (! isset($this->disabled)) {
            $this->merge(['disabled' => false]);
        }else{
            $this->merge(['disabled' => true]);
        }

    }
}
