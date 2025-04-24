<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TrainingRequest extends FormRequest
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
                'exception' => 'required|in:Yes,No',
                'institution' => 'required|string|max:255',
                'course' => 'required|string|max:255',
                'score' => 'required|string|max:255',
                'training_type_id' => 'required|integer',
                'training_attachment' => 'nullable',
            ];

        }else{

            return [
                'from_date' => 'required|date',
                'to_date' => 'required|date',
                'exception' => 'required|in:Yes,No',
                'institution' => 'required|string|max:255',
                'course' => 'required|string|max:255',
                'score' => 'required|string|max:255',
                'training_type_id' => 'required|integer',
                'training_attachment' => 'nullable',
            ];

        }

    }
}
