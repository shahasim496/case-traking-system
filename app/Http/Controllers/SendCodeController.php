<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Forget_Password;
use Auth;
use DB;

class SendCodeController extends Controller
{


    public function index(Request $request){

        $data = $request->all();

        $validated = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $user = User::where('email',$request->email)->first();

        if(!empty($user)){

            $mail_data['subject'] = "Reset Password!";
            $mail_data['to_email'] = $user->email;
            $code=rand(1000,9999);
            $mail_data['code'] = $code;

            session()->forget('passwordCode');
            session()->forget('passwordEmail');

            $forgetPassword = Forget_Password::where('user_id',$user->id)->first();

            if(empty($forgetPassword)){

                $forgetPassword = Forget_Password::create([
                    'verification_code'=>$code,
                    'user_id'=>$user->id,
                ]);

            }else{
                $forgetPassword->verification_code  = $code;
                $forgetPassword->save();
            }

            session(['passwordEmail' => $data['email'], 'passwordCode' => $code]);

            $res = $this->send_mail($mail_data,'emails.password_reset');
            return redirect()->route('verifycode')->with('success', 'Code send to your email.');

        }else{
            return redirect()->back()->with('error', "Something went wrong please try agian.");
        }

    }//end of function

    public function verifycode(Request $request){

        $data = $request->all();
        return view('auth.passwords.verify_code');

    }//end of function

    public function verifyUserCode(Request $request){

        $data = $request->all();

        $validated = $request->validate([
            'code1' => 'required',
            'code2' => 'required',
            'code3' => 'required',
            'code4' => 'required',
        ]);

        $code = $request->code1.''.$request->code2.''.$request->code3.''.$request->code4;
        $passwordCode = $request->session()->get('passwordCode', '');
        $passwordEmail = $request->session()->get('passwordEmail', '');
        $email = $passwordEmail;

        $forgetPassword = Forget_Password::where('verification_code',$code)->first();
        // $user = User::where('email',$passwordEmail)->first();

        // if(!empty($user) && $passwordCode == $request->code){
        if(!empty($forgetPassword)){
            return view('auth.passwords.reset_password',compact(['email']));
        }else{
            return redirect()->back()->with('User not found.');
        }

    }//end of function

    public function resetPassword(Request $request){

        $data = $request->all();

        $validated = $request->validate([
            'password' => 'required|confirmed'
        ]);

        $password = $request->password;
        $user = User::where('email', $request->email)->first();

        if(!empty($user)){

            // $user->password = \Hash::make($password);
            $user->password = $password;
            $user->save();

            $forgetPassword = Forget_Password::where('user_id',$user->id)->first();
            if(!empty($forgetPassword)){
                $forgetPassword->delete();
            }

            return redirect('/')->with('Password changed successfully.');

        }else{
            return redirect()->back()->with('User not found.');
        }

    }//end of function

    public function resendCode(Request $request){

        $data = $request->all();

        $passwordEmail = $request->session()->get('passwordEmail', '');
        $user = User::where('email',$passwordEmail)->first();

        if(!empty($user)){

            $mail_data['subject'] = "Resend Password Code!";
            $mail_data['to_email'] = $user->email;
            $code=rand(1000,9999);
            $mail_data['code'] = $code;

            session()->forget('passwordCode');
            session()->forget('passwordEmail');

            session(['passwordEmail' => $passwordEmail, 'passwordCode' => $code]);

            $forgetPassword = Forget_Password::where('user_id',$user->id)->first();

            if(empty($forgetPassword)){

                $forgetPassword = Forget_Password::create([
                    'verification_code'=>$code,
                    'user_id'=>$user->id,
                ]);

            }else{
                $forgetPassword->verification_code  = $code;
                $forgetPassword->save();
            }

            $res = $this->send_mail($mail_data,'emails.password_reset');
            return redirect()->back()->with('success', 'Code send to your email.');

        }else{
            return redirect()->back()->with('User not found.');
        }

    }//end of function
}
