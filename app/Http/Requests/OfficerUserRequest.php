<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User_Profile;

class OfficerUserRequest extends FormRequest
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
                'phone' => 'nullable|string|max:25',
                'cnic' => 'required|string|max:255|unique:user_profiles',
                'group_service_id' => 'required|integer',
                'grade_id' => 'required|integer',
                'officer_id' => 'required|integer',
                'name' => 'required|string',
                'cnic' => 'required|string',
                'email' => 'required|string|email|max:25|unique:users',
                'password' => 'required|string|confirmed',
                'designation' => 'nullable|string',
            ];

        }else{

            $profile_id = User_Profile::where('user_id',$this->id)->value('id');

            return [
                'phone' => 'nullable|string|max:25',
                'cnic' => 'required|string|max:255|unique:user_profiles,cnic,'.$profile_id,
                'group_service_id' => 'required|integer',
                'grade_id' => 'required|integer',
                'officer_id' => 'required|integer',
                'name' => 'required|string',
                'email' => 'required|string|email|max:255|unique:users,email,' . $this->id,
                'password' => 'nullable|string|confirmed',
                'designation' => 'nullable|string',
            ];

        }

    }
}
