<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Models\User_Profile;

class ServiceRequest extends FormRequest
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
                'notification_date' => 'required|date',
                'is_active' => 'nullable',
                'from_date' => 'required|date',
                'to_date' => 'required_without:is_active',
                'post_id' => 'required|integer',
                'specialization' => 'nullable|string|max:200',
                'grade_id' => 'required|integer',
                'province_id' => 'nullable|integer',
                'ministry_id' => 'nullable|integer',
                'division_id' => 'required|integer',
                'department_id' => 'required|integer',
                'district_id' => 'required|integer',
                'city' => 'required|string|max:255',
                'posting_type_id' => 'required|integer',
                'further_detail' => 'nullable|string|max:200',
                'post_list' => 'nullable|string|max:200',
                'orgnization_list' => 'nullable|string|max:200',
                'notification_attachment' => 'nullable',
            ];

        }else{

            return [
                'notification_date' => 'required|date',
                'is_active' => 'nullable',
                'from_date' => 'required|date',
                'to_date' => 'required_without:is_active',
                // 'to_date' => 'required_if:is_active,1|date',
                'post_id' => 'required|integer',
                'specialization' => 'nullable|string|max:200',
                'grade_id' => 'required|integer',
                'province_id' => 'nullable|integer',
                'ministry_id' => 'nullable|integer',
                'division_id' => 'required|integer',
                'department_id' => 'required|integer',
                'district_id' => 'required|integer',
                'city' => 'required|string|max:255',
                'posting_type_id' => 'required|integer',
                'further_detail' => 'nullable|string|max:200',
                'post_list' => 'nullable|string|max:200',
                'orgnization_list' => 'nullable|string|max:200',
                'notification_attachment' => 'nullable',
            ];

        }//end of if

    }
}
