<?php

namespace App\Http\Controllers;

use DB;

use Auth;

use Excel;
use App\Models\User;

use App\Models\Grade;
use App\Models\Tehsil;
use App\Models\District;
use App\Models\Province;
use App\Models\Department;
use App\Models\Application;
use App\Models\Designation;

use App\Models\OfficerUser;
use App\Models\Subdivision;
use Illuminate\Support\Str;
use App\Models\User_Profile;
use Illuminate\Http\Request;
use App\Models\Group_Service;
use App\Models\PoliceStation;
use App\Exports\OfficerExport;
use App\Models\Forget_Password;
use App\Models\Officer_Profile;
use App\Models\AdministrativeUnit;
use Spatie\Permission\Models\Role;

use App\Notifications\UserNotification;
use App\Http\Requests\GenOfficerRequest;
use App\Http\Requests\UpdateUserRequest;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\OfficerUserRequest;
use App\Http\Requests\UserProfileRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserProfileRequest;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
     

    } //end of function

    public function index(Request $request) {
        $users = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'SuperAdmin');
        })->orderBy('id', 'DESC')->paginate(10);
        return view('users.index', compact('users'));
    }




    public function banned(Request $request, $id)
    {

        $data = $request->all();
        $user = User::find($id);
        $masg = null;

        if ($user) {

            $user->is_blocked = $user->is_blocked == 0 ? 1 : 0;
            if ($user->is_blocked == 1) {
                $masg = 'User successfully banned.';
            } else {
                $masg = 'User successfully unbanned.';
            }

            $user->save();
            return redirect()->route('users')->with('success', $masg);
        } else {
            return redirect()->back()->with('User not found.');
        }
    } //end of function

    public function create(Request $request)
    {

        $data = $request->all();
        $departments = Department::all();

        // Fetch all designations
        $designations = Designation::all();



        $roles = Role::where('name', '!=', 'SuperAdmin')->get();



        return view('users.add_user', compact('departments', 'designations', 'roles'));
    } //end of function




    public function store(Request $request)
    {
        try {

        $validatedData =  $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'cnic' => 'required|string|max:15|unique:users,cnic',
            'phone' => 'required|string|max:12|regex:/^[0-9]+$/', // Only numbers, max 12 digits
            'department_id' => 'required|integer',
            'designation_id' => 'required|integer',
            'roles' => 'required|array', // Validate roles as an array
            'roles.*' => 'string|exists:roles,name', // Validate each role
        ]);

        DB::beginTransaction();

            // Generate a unique password using UUID
            $password = (string) Str::uuid();





   
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $password,
                'cnic' => $request->cnic,
                'phone' => $request->phone,
                'department_id' => $request->department_id,
                'designation_id' => $request->designation_id,

            ]);

            // Assign multiple roles to the user
            $user->assignRole($request->roles);

            // Send email to the created user
            \Mail::to($user->email)->send(new \App\Mail\UserCreated($user, $password));

            DB::commit();

            return redirect()->route('users')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function updatePassword(UpdatePasswordRequest $request, $id)
    {

        $user = User::find($id);
        DB::beginTransaction();

        try {

            if ($request->has('password')) {
                $user->password = $request->password;
            }
            $user->save();

            DB::commit();
            return redirect()->route('user.edit', $id)->with('success', 'User password updated.');
        } catch (\Exception $e) {
            DB::rollback();
            return  redirect()->back()->with('error', $e->getMessage());
        } //end of try

    } //end of function

    public function sendCode(Request $request)
    {

        $data = $request->all();
        $passwordEmail = Auth::user()->email;

        $user = User::where('email', $passwordEmail)->first();

        if (!empty($user)) {

            $mail_data['subject'] = "Reset Password!";
            $mail_data['to_email'] = $user->email;
            $code = rand(1000, 9999);
            $mail_data['code'] = $code;

            session()->forget('passwordCode');
            session()->forget('passwordEmail');

            $forgetPassword = Forget_Password::where('user_id', $user->id)->first();

            if (empty($forgetPassword)) {

                $forgetPassword = Forget_Password::create([
                    'verification_code' => $code,
                    'user_id' => $user->id,
                ]);
            } else {
                $forgetPassword->verification_code  = $code;
                $forgetPassword->save();
            }

            session(['passwordEmail' => $passwordEmail, 'passwordCode' => $code]);

            $res = $this->send_mail($mail_data, 'emails.password_reset');
            return redirect()->route('user.verifyCode')->with('success', 'Code send to your email.');
        } else {
            return redirect()->back()->with('User not found.');
        }
    } //end of function

    public function verifyCode(Request $request)
    {

        $data = $request->all();
        return view('users.verify_code');
    } //end of function

    public function verifyUserCode(Request $request)
    {

        $data = $request->all();

        $validated = $request->validate([
            'code1' => 'required',
            'code2' => 'required',
            'code3' => 'required',
            'code4' => 'required',
        ]);

        $code = $request->code1 . '' . $request->code2 . '' . $request->code3 . '' . $request->code4;
        $passwordCode = $request->session()->get('passwordCode', '');
        $passwordEmail = Auth::user()->email;
        $email = $passwordEmail;

        $forgetPassword = Forget_Password::where('verification_code', $code)->first();
        // $user = User::where('email',$passwordEmail)->first();

        // if(!empty($user) && $passwordCode == $request->code){
        if (!empty($forgetPassword)) {
            return view('users.reset_password', compact(['email']));
        } else {
            return redirect()->back()->with('User not found.');
        }
    } //end of function

    public function resetPassword(Request $request)
    {

        $data = $request->all();

        $validated = $request->validate([
            'password' => 'required|confirmed'
        ]);

        $password = $request->password;
        $user = User::where('email', $request->email)->first();

        if (!empty($user)) {

            // $user->password = \Hash::make($password);
            $user->password = $password;
            $user->save();

            $forgetPassword = Forget_Password::where('user_id', $user->id)->first();
            if (!empty($forgetPassword)) {
                $forgetPassword->delete();
            }

            return redirect('/home')->with('Password changed successfully.');
        } else {
            return redirect()->back()->with('User not found.');
        }
    } //end of function

    public function resendCode(Request $request)
    {

        $data = $request->all();
        $passwordEmail = Auth::user()->email;
        $user = User::where('email', $passwordEmail)->first();

        if (!empty($user)) {

            $mail_data['subject'] = "Resend Password Code!";
            $mail_data['to_email'] = $user->email;
            $code = rand(1000, 9999);
            $mail_data['code'] = $code;

            session()->forget('passwordCode');
            session()->forget('passwordEmail');

            session(['passwordEmail' => $passwordEmail, 'passwordCode' => $code]);

            $forgetPassword = Forget_Password::where('user_id', $user->id)->first();

            if (empty($forgetPassword)) {

                $forgetPassword = Forget_Password::create([
                    'verification_code' => $code,
                    'user_id' => $user->id,
                ]);
            } else {
                $forgetPassword->verification_code  = $code;
                $forgetPassword->save();
            }

            $res = $this->send_mail($mail_data, 'emails.password_reset');
            return redirect()->back()->with('success', 'Code send to your email.');
        } else {
            return redirect()->back()->with('User not found.');
        }
    } //end of function

  

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::all();
        $designations = Designation::all();

        $roles = Role::where('name', '!=', 'SuperAdmin')->get();
        $userRoles = $user->roles->pluck('name')->toArray();

        // Debug the user data


        return view('users.edit_user', compact(
            'user',
            'departments',
            'designations',
            'roles',
            'userRoles'
        ));
    } //end of function

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'cnic' => 'required|string|max:15|unique:users,cnic,' . $id,
            'phone' => 'required|string|max:15',
            'department_id' => 'required',
            'designation_id' => 'required',

            'roles' => 'required|array', // Validate roles as an array
            'roles.*' => 'string|exists:roles,name', // Validate each role
        ]);

        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);

            $password = (string) Str::uuid();
            // Update user details
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $password,
                'cnic' => $request->cnic,
                'phone' => $request->phone,
                'department_id' => $request->department_id,
                'designation_id' => $request->designation_id,

            ]);

           

            // Sync roles for the user
            $user->syncRoles($request->roles);

            // Send email to the updated user
            \Mail::to($user->email)->send(new \App\Mail\UserUpdated($user, $request->password));

            DB::commit();

            return redirect()->route('users')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return redirect()->route('users')->with('error', 'User not found.');
        }

        // Delete the user
        $user->delete();

        return redirect()->route('users')->with('success', 'User permanently deleted successfully.');
    } //end of function

    public function markNotification(Request $request)
    {

        $data = $request->all();

        try {

            $user_id = 0;
            $notification_id = isset($data['notification_id']) ? $data['notification_id'] : 0;

            if (Auth::check()) {
                $user_id = Auth::user()->id;
            }

            $user = User::find($user_id);

            if ($notification_id === 0) {
                $user->unreadNotifications->markAsRead();
            } else {
                $notification = $user->notifications()->find($notification_id);

                if ($notification) {
                    $notification->markAsRead();
                }
            }
            return ['status' => 'success', 'count' => $user->unreadNotifications->count()];
            return response()->json(['data' => ['count' => $user->unreadNotifications->count()], 'meta' => ['message' => 'Successfully mark the notification as read.', 'status' => 200, 'errors' => null]]);
        } catch (\Exception $e) {
            return response()->json(['data' => null, 'meta' => ['message' => $e->getMessage(), 'status' => 402, 'errors' => null]]);
        } //end of try

    } //end of function

    public function assignPermissions()
    {


        $modules = array(
            'Officer'
        );


        $subModules = array(
            'Personal Information',
            'Qualification',
            'Foreign Visits',
            'Mandatory Training',
            'Training',
            'Spouse Info',
            'Promotion',
            'Contact Information',
            'Achievements',
            'Service/Posting History',
        );

        $permissions = array(
            'Read',
            'Write',
            'Edit',
            'Delete',
        );
    } //end of function

    public function changePassword(Request $request)
    {

        $data = $request->all();
        return view('users.change_password');
    } //end of function

    public function savePassword(Request $request)
    {

        $data = $request->all();

        $validatedData = $request->validate([
            'current_password' => 'required|current_password:web',
            'password' => 'required|min:8',
            'confirm_password' => 'same:password',
        ], $data);

        User::find(auth()->user()->id)
            ->update(['password' => $request->password]);

        if (Auth::User()->hasRole('Officer')) {
            OfficerUser::where('user_id', auth()->user()->id)
                ->update(['pass_code' => $request->password]);
        }

        return redirect()->route('user.changePassword')->with('success', 'Password changed successfully.');
    } //end of function
}
