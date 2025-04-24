<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
            'phone' => 'required|string|max:255',
            'cnic' => 'required|string|max:255|unique:user_profiles,user_id,' . $this->id,
            'province_id' => 'required|integer',
            'tehsil_id' => 'required|integer',
            'district_id' => 'required|integer',
            'designation_id' => 'nullable|string',
            'picture_attachment' => 'nullable',
            'residential_address' => 'nullable|string',
        ];
    }
}
