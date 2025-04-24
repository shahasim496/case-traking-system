<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Models\User_Profile;

class OfficerRequest extends FormRequest
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
                'p_no' => 'required|alpha_dash|max:100',
                'serial_no' => 'nullable|alpha|max:100',
                'seniority_code' => 'nullable|string|max:50',
                'seniority' => 'nullable|integer',
                'batch_no' => 'required|alpha_dash|max:100',
                'prefix' => 'nullable|in:MR,MS',
                'first_name' => 'required|string|max:50',
                'middle_name' => 'nullable|string|max:50',
                'last_name' => 'nullable|string|max:50',
                'guardian' => 'required|string|max:100',
                'cnic' => 'required|alpha_dash|max:20|unique:officer_profiles',
                'email' => 'nullable|string|max:50|unique:officer_profiles',
                'phone' => 'nullable|string|max:20',
                'birth_place' => 'required|integer',
                'passport_no' => 'required|alpha_dash|max:100',
                'ntn_no' => 'required|alpha_dash|max:100',
                'gender_id' => 'required|integer',
                // 'language' => 'required|string',
                'cader_id' => 'required|integer',
                'group_service_id' => 'required|integer',
                'joiningdate' => 'required|date',
                'dmgjoiningdate' => 'required|date',
                'religion_id' => 'required|integer',
                'date_of_birth' => 'required|date',
                'appointment_grade_id' => 'required|integer',
                'material_status_id' => 'required|integer',
                'domicile_district_id' => 'required|integer',
                'domicile_province_id' => 'required|integer',
                'foreign_training' => 'nullable|in:Yes,No',
                'other_nationality' => 'nullable|integer',
                'employee_status_id' => 'required|integer',
                'permanent_residence' => 'required|integer',
                'current_grade_id' => 'required|integer',
                'other_language_id' => 'required',
                'mother_language_id' => 'required|integer',
                'subcadre_id' => 'required|integer',
                'date_of_retirement' => 'required|date'
            ];

        }else{

            return [
                'p_no' => 'required|alpha_dash|max:100',
                'serial_no' => 'nullable|alpha_dash|max:100',
                'seniority_code' => 'nullable|string|max:50',
                'seniority' => 'nullable|integer',
                'batch_no' => 'required|alpha_dash|max:100',
                'prefix' => 'nullable|in:MR,MS',
                'first_name' => 'required|string|max:50',
                'middle_name' => 'nullable|string|max:50',
                'last_name' => 'nullable|string|max:50',
                'guardian' => 'required|string|max:100',
                'cnic' => 'required|string|max:20|unique:officer_profiles,cnic,'.$this->id,
                'email' => 'nullable|string|max:50|unique:officer_profiles,email,'.$this->id,
                'phone' => 'nullable|string|max:20',
                'birth_place' => 'required|integer',
                'passport_no' => 'required|alpha_dash|max:100',
                'ntn_no' => 'required|alpha_dash|max:100',
                'gender_id' => 'required|integer',
                // 'language' => 'required|string',
                'cader_id' => 'required|integer',
                'group_service_id' => 'required|integer',
                'joiningdate' => 'required|date',
                'dmgjoiningdate' => 'required|date',
                'religion_id' => 'required|integer',
                'date_of_birth' => 'required|date',
                'appointment_grade_id' => 'required|integer',
                'material_status_id' => 'required|integer',
                'domicile_district_id' => 'required|integer',
                'domicile_province_id' => 'required|integer',
                'foreign_training' => 'nullable|in:Yes,No',
                'other_nationality' => 'nullable|integer',
                'employee_status_id' => 'required|integer',
                'permanent_residence' => 'required|integer',
                'current_grade_id' => 'required|integer',
                'other_language_id' => 'required',
                'mother_language_id' => 'required|integer',
                'subcadre_id' => 'required|integer',
                'date_of_retirement' => 'required|date'
            ];

        }//end of if

    }
}
