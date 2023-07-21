<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramYearStoreRequest extends FormRequest
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
            'year_start.required' => 'Year Start field is required.',
            'year_start.unique' => 'The provided Year Start is already in-use.',
            'year_end.required' => 'Year End field is required.',
            'year_end.unique' => 'The provided Year End is already in-use.',
            'grant_amount.*' => 'Grant Amount field is required.',
            'max_years_allowed.*' => 'Active field is invalid.',
            'min_age.*' => 'Type field is required.',
            'max_age.*' => 'Description field is required.',
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
            'year_start' => 'required|numeric|unique:yeaf_program_years,year_start',
            'year_end' => 'required|numeric|unique:yeaf_program_years,year_end',
            'grant_amount' => 'required|numeric',
            'max_years_allowed' => 'required|numeric',
            'min_age' => 'required|numeric',
            'max_age' => 'required|numeric',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (isset($this->year_start)) {
            $this->merge(['year_start' => preg_replace('/\D/', '', $this->year_start)]);
        }
        if (isset($this->year_end)) {
            $this->merge(['year_end' => preg_replace('/\D/', '', $this->year_end)]);
        }
        if (isset($this->max_years_allowed)) {
            $this->merge(['max_years_allowed' => preg_replace('/\D/', '', $this->max_years_allowed)]);
        }
        if (isset($this->min_age)) {
            $this->merge(['min_age' => preg_replace('/\D/', '', $this->min_age)]);
        }
        if (isset($this->max_age)) {
            $this->merge(['max_age' => preg_replace('/\D/', '', $this->max_age)]);
        }
    }
}
