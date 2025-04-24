<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Auth;

class RegistrationController extends Controller
{
    public function userRegister(UserRegisterRequest $request){

        $data = $request->validated();

        // $password = $request->password;

        $user = User::create($data);
        $user->assignRole('User');

        Auth::loginUsingId($user->id);
        return redirect()->to('/home');

    }//end of function
}
