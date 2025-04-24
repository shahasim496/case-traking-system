<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id'=>$this->id,
            'full_name'=>$this->name,
            'email'=>$this->email,
            'email_verified_at'=>$this->email_verified_at,
            'cnic'=>$this->profile->cnic ?? null,
            'phone'=>$this->profile->phone ?? null,
            'residential_address'=>$this->profile->residential_address ?? null,
            'province_id'=>$this->profile->province_id ?? null,
            'district_id'=>$this->profile->district_id ?? null,
            'tehsil_id'=>$this->profile->tehsil_id ?? null,
            'designation_id'=>$this->profile->designation_id ?? null,
            'image'=>$this->profile->picture_attachment_id ?? null,
            'role' => $this->roles[0]->name,
            'applied_noc'=>$this->applications->where('application_status_id',2)->count(),
            'approved_noc'=>$this->applications->where('application_status_id',1)->count(),
        ];

    }

    public function with($r){
        return [
            'meta' => [
                'message' => 'Api Hit Success',
                'status'=>200,
                'errors'=>null,

            ]

        ];
    }
}
