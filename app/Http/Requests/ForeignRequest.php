<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ForeignRequest extends FormRequest
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
            'durationfrom' => 'required|date',
            'durationto' => 'required|date',
            'funded_by' => 'required|in:Govt Paid,Personal Trip',
            'detail' => 'nullable|string|max:255',
            'country_id' => 'required|integer',
        ];

        }else{

            return [
            'durationfrom' => 'required|date',
            'durationto' => 'required|date',
            'funded_by' => 'required|in:Govt Paid,Personal Trip',
            'detail' => 'nullable|string|max:255',
            'country_id' => 'required|integer',
        ];

        }

    }
}
