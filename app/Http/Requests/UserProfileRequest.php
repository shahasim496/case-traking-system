<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Models\User_Profile;

class UserProfileRequest extends FormRequest
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
                'phone' => 'required|string|max:255',
                'role' => 'required|string',
                'cnic' => 'required|string|max:255|unique:user_profiles',
                // 'province_id' => 'required|integer',
                // 'tehsil_id' => 'required|integer',
                // 'district_id' => 'required|integer',
                'allow_caders' => 'required_if:role,Cadre',
                'designation_id' => 'nullable|string',
                'designation' => 'nullable|string',
                'picture_attachment' => 'nullable',
                'residential_address' => 'nullable|string',
            ];

        }else{

            $profile_id = User_Profile::where('user_id',$this->id)->value('id');

            return [
                'phone' => 'required|string|max:255',
                'role' => 'required|string',
                'cnic' => 'required|string|max:255|unique:user_profiles,cnic,'.$profile_id,
                // 'province_id' => 'required|integer',
                // 'tehsil_id' => 'required|integer',
                // 'district_id' => 'required|integer',
                'allow_caders' => 'required_if:role,Cadre',
                'designation_id' => 'nullable|string',
                'designation' => 'nullable|string',
                'picture_attachment' => 'nullable',
                'residential_address' => 'nullable|string',
            ];

        }//end of if

    }
}
