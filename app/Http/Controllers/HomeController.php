<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Group_Service;
use Auth;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('banned');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
            return redirect()->route('admin_dashboard');
      
    }

    public function user_dashboard()
    {
        return view('home');
    }//end of function

    public function admin_dashboard()
    {

// return $group_total;
        return view('dashboard.admin');
    }//end of function

    public function getGroupWiseReport($group_id, $user_id = -1)
    {

        // $group_service = Group_Service::where('id',$group_id)->first();
// return $group_services[0]->group_grade;

        $reports = DB::select(
            'CALL group_wise_report_report_views("' . $group_id . '","' . $user_id . '")'
        );
        return $reports[0];

    }//end of function

    public function getTotalCadreReport()
    {

        $reports = DB::select(
            'CALL total_cadre_view()'
        );
        return $reports[0];

    }//end of function

}
