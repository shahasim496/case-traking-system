<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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

         // Get mail configuration with fallback to config values
         $fromEmail = env('MAIL_FROM_ADDRESS', config('mail.from.address'));
         $fromName = env('MAIL_FROM_NAME', config('mail.from.name', 'Court System'));

         // Validate required email configuration
         if (empty($fromEmail) || $fromEmail === 'hello@example.com') {
             $errorMsg = 'Mail configuration error: MAIL_FROM_ADDRESS is not set or is using default value. Please configure your .env file with proper mail settings.';
             Log::error($errorMsg);
             return ['success' => false, 'error' => $errorMsg];
         }

         // Validate required data
         if (empty($data['to_email']) || empty($data['subject'])) {
             $errorMsg = 'Mail data error: Missing required fields (to_email or subject)';
             Log::error($errorMsg, ['data' => $data]);
             return ['success' => false, 'error' => $errorMsg];
         }

         // Validate email address format
         if (!filter_var($data['to_email'], FILTER_VALIDATE_EMAIL)) {
             $errorMsg = 'Invalid email address: ' . $data['to_email'];
             Log::error($errorMsg);
             return ['success' => false, 'error' => $errorMsg];
         }

         $data['from_email'] = $fromEmail;
         $data['from_name'] = $fromName;

         try {
             Mail::send($view, array('data'=>$data), function($message) use ($data, $fromEmail, $fromName) {
                 $message->from($fromEmail, $fromName);
                 $message->to($data['to_email']);
                 $message->subject($data['subject']);
             });

             if (Mail::failures()) {
                 $failures = Mail::failures();
                 $errorMsg = 'Failed to send email to: ' . implode(', ', $failures);
                 Log::error($errorMsg, [
                     'to_email' => $data['to_email'],
                     'subject' => $data['subject'],
                     'failures' => $failures
                 ]);
                 return ['success' => false, 'error' => $errorMsg, 'failures' => $failures];
             } else {
                 Log::info('Email sent successfully', [
                     'to_email' => $data['to_email'],
                     'subject' => $data['subject']
                 ]);
                 return ['success' => true, 'message' => 'Email sent successfully'];
             }
         } catch (\Exception $e) {
             $errorMsg = 'Exception while sending email: ' . $e->getMessage();
             Log::error($errorMsg, [
                 'to_email' => $data['to_email'] ?? 'unknown',
                 'subject' => $data['subject'] ?? 'unknown',
                 'exception' => $e->getTraceAsString()
             ]);
             return ['success' => false, 'error' => $errorMsg, 'exception' => $e->getMessage()];
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
