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
use Yajra\DataTables\Facades\DataTables;
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
        // $this->middleware(['permission:read_user'])->only(['index']);
        // $this->middleware(['permission:create_user'])->only(['create','store']);

    } //end of function

    public function index(Request $request)
    {

        $data = $request->all();

        // $permissions = Auth::user()->getPermissionsViaRoles();

        if (Auth::User()->hasRole('SuperAdmin')) {
            $applications = Application::orderBy('id', 'DESC')->get(); //orderBy('created_at','DESC')->
        } else {
            $applications = Application::orderBy('id', 'DESC')->where('from_user_id', Auth::user()->id)->get();
        }


        return Datatables::of($applications)
            ->addIndexColumn()
            ->addColumn('title', function ($row) {
                return $row->title;
            })->addColumn('category', function ($row) {
                return "category";
            })->addColumn('status', function ($row) {
                return "status";
            })->addColumn('submittion_date', function ($row) {
                return date("d-m-Y", strtotime($row->submittion_date));
            })->addColumn('action', function ($row) {

                $actionBtn = '<a href="' . route('users', $row->id) .
                    '" class="btn-sm btn-outline-warning"><i class="feather icon-eye"></i></a> <a href="' . route('users', $row->id) .
                    '" class="btn-sm btn-outline-info"><i class="feather icon-edit"></i></a> <a href="' . route('users', $row->id) .
                    '" onClick="return deleteR(' . $row->id . ');" id="delete_' . $row->id .
                    '" class="btn-sm btn-outline-danger"><i class="feather icon-trash-2"></i></a>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    } //end of function

    public function all_users(Request $request)
    {

        $data = $request->all();
        return view('users.view_users');
    } //end of function

    public function getUsers(Request $request)
    {
        $data = $request->all();

        // Fetch all users
        $users = User::orderBy('id', 'DESC')->get();

        return Datatables::of($users)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('cnic', function ($row) {
                return $row->cnic ?? '-';
            })
            ->addColumn('designation', function ($row) {
                return $row->designation ?? '-';
            })
            ->addColumn('email', function ($row) {
                return $row->email;
            })
            ->addColumn('phone', function ($row) {
                return $row->phone ?? '-';
            })
            ->addColumn('is_block', function ($row) {
                $btnText = $row->is_blocked == 0 ? 'Unbanned' : 'Banned';
                return $btnText;
            })
            ->addColumn('action', function ($row) {
                $btnText = $row->is_blocked == 0 ? 'Banned' : 'Unbanned';
                $actionBtn = '';

                // Ban/Unban button - only show if user has ban permission
                if (auth()->user()->can('ban user')) {
                    $actionBtn .= '<a href="' . route('user.banned', $row->id) .
                        '" class="btn profile_icon" data-toggle="tooltip" title="' . $btnText . '">
                        <i class="fa fa-user" aria-hidden="true"></i></a>';
                }

                // Edit button - only show if user has edit permission
                if (auth()->user()->can('edit user')) {
                    $actionBtn .= '<a href="' . route('user.edit', $row->id) .
                        '" class="btn edit_icon" data-toggle="tooltip" title="Edit">
                        <i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                // Delete button - only show if user has delete permission
                if (auth()->user()->can('delete user')) {
                    $actionBtn .= '<a href="' . route('user.delete', $row->id) .
                        '" onClick="return deleteR(' . $row->id . ');" id="delete_' . $row->id .
                        '" class="btn delete_icon" data-toggle="tooltip" title="Delete">
                        <i class="fa fa-trash-o" aria-hidden="true"></i></a>';
                }

                return $actionBtn;
            })
            ->rawColumns(['action', 'is_block'])
            ->make(true);
    } //end of function

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
            return redirect()->route('user.all')->with('success', $masg);
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
        $validatedData =  $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'cnic' => 'required|string|max:15|unique:users,cnic',
            'phone' => 'required|string|max:15',
            'department_id' => 'required|integer',
            'designation_id' => 'required|integer',
           
            'roles' => 'required|array', // Validate roles as an array
            'roles.*' => 'string|exists:roles,name', // Validate each role
        ]);

        DB::beginTransaction();

        

        try {
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'cnic' => $request->cnic,
                'phone' => $request->phone,
                'department_id' => $request->department_id,
                'designation_id' => $request->designation_id,
               
            ]);

            // Assign multiple roles to the user
            $user->assignRole($request->roles);

            // Send email to the created user
        \Mail::to($user->email)->send(new \App\Mail\UserCreated($user, $request->password));

            DB::commit();

            return redirect()->route('user.all')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something went wrong. Please try again.')->withInput();
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
            return redirect()->route('user.profile', $id)->with('success', 'User password updated.');
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

    public function myApplication(Request $request)
    {
        $data = $request->all();
        return view('application.index');
    } //end of function

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::all();
        $designations = Designation::all();
       
        $roles = Role::all();
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
        $validatedData=$request->validate([
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

            // Update user details
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'cnic' => $request->cnic,
                'phone' => $request->phone,
                'department_id' => $request->department_id,
                'designation_id' => $request->designation_id,
                
            ]);

            // $message = "Dear {$user->name}, your profile has been updated successfully.";
            // $user->notify(new UserNotification($message));

            // Sync roles for the user
            $user->syncRoles($request->roles);

              // Send email to the updated user
        \Mail::to($user->email)->send(new \App\Mail\UserUpdated($user,$request->password));

            DB::commit();

            return redirect()->route('user.all')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something went wrong. Please try again.')->withInput();
        }
    }

    public function delete(Request $request, $id)
    {
        $data = $request->all();

        $user = User::find($id);
        $user->deleted_by = auth()->user()->id;
        $user->save();

        $user->delete();

        return redirect()->route('user.all')->with('User deleted successfully.');
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
