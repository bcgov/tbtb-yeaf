<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GrantEditRequest extends FormRequest
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
            'grant_id.*' => 'Grant ID number field is not valid.',
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
            'grant_id' => 'required',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $form = json_decode($this->frm);
        foreach ($form as $item => $value) {
            $this->merge([$item => $value]);
        }
        $this->merge(['frm' => null]);

//
//
//        if (isset($this->tele)) {
//            $this->merge(['tele' => preg_replace('/\D/', '', $this->tele)]);
//        }
//
//        $this->merge(['disabled' => $this->toBoolean($this->disabled)]);
    }

    /**
     * Convert to boolean
     *
     * @param $booleable
     * @return bool
     */
    private function toBoolean($booleable)
    {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}
