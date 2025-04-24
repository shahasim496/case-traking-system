<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ForeignTrainingRequest extends FormRequest
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
                'from_date' => 'required|date',
                'to_date' => 'required|date',
                'scholarship_name' => 'required_if:financing_type_id,1,4|string|nullable|max:200',
                'training_type' => 'required|in:Foreign,Local',
                'institution' => 'required|string|max:255',
                'course' => 'required|string|max:255',
                'detail' => 'nullable|string|max:255',
                'country_id' => 'required|integer',
                'financing_type_id' => 'required|integer',
                'training_attachment' => 'nullable',
            ];

        }else{

            return [
                'from_date' => 'required|date',
                'to_date' => 'required|date',
                'scholarship_name' => 'required_if:financing_type_id,1,4|string|nullable|max:200',
                'training_type' => 'required|in:Foreign,Local',
                'institution' => 'required|string|max:255',
                'course' => 'required|string|max:255',
                'detail' => 'nullable|string|max:255',
                'country_id' => 'required|integer',
                'financing_type_id' => 'required|integer',
                'training_attachment' => 'nullable',
            ];

        }

    }
}
