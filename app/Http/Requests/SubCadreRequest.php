<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubCadreRequest extends FormRequest
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
                'name' => 'required|string|max:50|unique:sub_cadres',
                'group_service_id' => 'required',

            ];

        }else{
            return [
                'name' => 'required|string|max:50|unique:sub_cadres,id,'.$this->id,
                'group_service_id' => 'required',
            ];
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'The name is required',
            'name.unique' => 'The name has already been taken',
            'group_service_id.required' => 'The Main Cadre is required',

        ];
    }
}
