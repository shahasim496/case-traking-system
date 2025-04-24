<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class User_Profile extends Model
{
    use HasFactory;

    protected $table = 'user_profiles';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','cnic','phone','residential_address','user_id','province_id','district_id',
'tehsil_id','designation_id','picture_attachment_id','designation','allow_caders',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'allow_caders' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function designation()
    // {
    //     return $this->belongsTo(Designation::class);

    // }

    public function image()
    {
        return $this->belongsTo(Attachment::class,'picture_attachment_id');

    }

    public function getPictureAttachmentIdAttribute($value)
    {
        if($value){

            $attachment = Attachment::find($value);
            if(!empty($attachment)){
                $path = $attachment->path;
            }else{
                $path = asset('/images/profile.jpg');
            }
            return $path;
        }else{
            return asset('/images/profile.jpg');
        }
    }
}
