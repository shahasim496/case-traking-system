<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupServiceRequest extends FormRequest
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
                'name' => 'required|string|max:50|unique:group_services',
            ];

        }else{
            return [
                'name' => 'required|string|max:50|unique:group_services,id,'.$this->id,
            ];
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'The name is required',
            'name.unique' => 'The name has already been taken',
        ];
    }
}
