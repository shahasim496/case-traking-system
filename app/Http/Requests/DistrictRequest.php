<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DistrictRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        if($this->method() == 'POST'){
            return [
                'name' => 'required|string|max:50|unique:districts',
                'province_id' => 'required',
            ];

        }else{
            return [
                'name' => 'required|string|max:50|unique:districts,id,'.$this->id,
                'province_id' => 'required',
            ];
        }

    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */

    public function messages()
    {
        return [
            'name.required' => 'The name is required',
            'name.unique' => 'The name has already been taken',
        ];
    }
}
