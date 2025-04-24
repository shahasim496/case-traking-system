<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ContactRequest extends FormRequest
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
            'email' => 'required|string|email|max:255',
            'landline_number' => 'required|string',
            'mobile_number' => 'required|string',
            'present_address' => 'required|string',
            'temporary_address' => 'required|string',
        ];

    }
}
