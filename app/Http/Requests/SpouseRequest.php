<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SpouseRequest extends FormRequest
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

        return [
            'name' => 'required|string',
            'government_servies' => 'required|in:Yes,No',
            'primary_country_id' => 'required|integer',
            'permanent_residence_country_id' => 'required|integer',
            'other_country_id' => 'required|integer',
        ];

    }
}
