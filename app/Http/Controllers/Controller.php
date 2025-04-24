<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Mail;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Notifications\AppNotification;

use App\Models\Attachment;
use App\Models\Application;
use App\Models\Subcategory;
use App\Models\User_Application_Status;
use App\Models\User;

use Carbon\Carbon;
use Validator;
use Auth;
// use Mail;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function send_mail($data,$view){

         $data['from_email'] = env('MAIL_FROM_ADDRESS');

         try{

             Mail::send($view,array('data'=>$data), function($message) use ($data){
             $message->from($data['from_email']);
             $message->to($data['to_email']);
             $message->subject($data['subject']);

             });

             if (Mail::failures()) {
                 return 0;
             }else{
                 return 1;
             }
         } catch (\Exception $e) {

            return $e->getMessage();
             return 0;
         }

    }//end of send_mail

    public function unique_code($limit)
    {
      return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }//end of function

    public function save_notification($sender_id,$title,$description,$action,$action_id){

        $admins = User::whereHas(
            'roles', function($q){
                $q->where('name', 'Administrator');
            }
        )->get();

        foreach ($admins as $key => $u) {

            $user = User::find($u->id);
            $user->notify(new AppNotification($sender_id,$user->id,$title,$description,$action,
            $action_id));

        }//end of foreach

    }//end of function

    public function save_attachment($request,$field,$type,$folder){

        $attachment_id =0;
        if ($request->hasFile($field)) {

            $cover = $request->$field;
            $extension = uniqid() . "." . $cover->getClientOriginalName();
            $path="/".$folder."/" . $extension;
            Storage::disk('public')->put($path, File::get($cover));

            $attachment = new Attachment();
            $attachment->attachment_type_id = $type == 'Image' ? 1 : 2;

            $attachment->path = $path;
            $attachment->save();

            $attachment_id = $attachment->id;
        }

        return $attachment_id;

    }//end of function

    public function save_multiple_attachment($file,$type,$folder){

        $attachment_id =0;
        $cover = $file;
        $extension = uniqid() . "." . $cover->getClientOriginalName();
        $path="/".$folder."/" . $extension;
        Storage::disk('public')->put($path, File::get($cover));

        $attachment = new Attachment();
        $attachment->attachment_type_id = $type == 'Image' ? 1 : 2;

        $attachment->path = $path;
        $attachment->save();

        $attachment_id = $attachment->id;

        return $attachment_id;

    }//end of function

    public function update_attachment($request,$attachment_id,$field,$type,$folder){

        if ($request->hasFile($field)) {

            $cover = $request->$field;
            $extension = time() . "." . $cover->getClientOriginalName();
            $path="/".$folder."/" . $extension;
            Storage::disk('public')->put($path, File::get($cover));

            $attachment = Attachment::where('id',$attachment_id)->first();

            if(empty($attachment)){
                $attachment->path = $path;
                $attachment->save();
            }
        }

        return $attachment_id;

    }//end of function

    public function getSubCategory($sub_id){

        $subcategory = Subcategory::find($sub_id);
        return $subcategory;

    }//end of function

    public function getUserCanWrite($application){

        if (Auth::User()->hasRole('Moderator')) {

            $subcategory = Subcategory::find($application->subcategory_id);
            $write_id = $subcategory->sub_permission('write')->first()->id ?? '';

            if(Auth::user()->hasPermissionTo($write_id) == 1){
                return true;
            }

            return false;
        }else{
            return true;
        }

    }//end of function

    public function getModeratorSubcate(){

        $subcategories = array();
        if (Auth::User()->hasRole('Moderator')) {

            $permissions = Auth::user()->getAllPermissions('subcategory_id')
            ->unique('subcategory_id');

            foreach ($permissions as $key => $permission) {
                if($permission->subcategory_id){
                    $subcategories[] = $permission->subcategory_id;
                }

            }

        }

        return $subcategories;

    }//end of function
}
