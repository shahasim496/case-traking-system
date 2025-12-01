<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group_Service;
use App\Models\Province;
use App\Models\Domicile;
use App\Models\District;
use App\Models\Ministry;
use App\Models\Division;
use App\Models\Entity;
use App\Models\Qualification;
use App\Models\Degree;
use App\Models\Post_Type;
use App\Models\Grade;
use App\Models\Post;
use App\Models\Officer_Profile;
use App\Models\Language;
use App\Models\Country;
use Auth;
use DB;

class AppController extends Controller
{
    public function getModelData(Request $request){

        $entities = Entity::take(10)->get(['id','name']);
        $divisions = Division::where('name','Capital Development Authority')->get(['id','name']);
        $posts = Post::take(10)->get(['id','name']);
        $qualifications = Degree::take(10)->get(['id','name']);
        $countries = Country::take(10)->get(['id','name']);
        // $languages = Language::take(10)->get(['id','name']);
        $languages = Language::get(['id','name']);

        $search = null;
        // if($request->has('q')){
        //     $search = $request->q;
        // }

        if($request->has('entity')){
            $search = $request->entity;
            $entities =Entity::select("id","name")
            		->where('name','LIKE',"%$search%")
            		->get(['id','name']);
        }

        if($request->has('division')){
            $search = $request->division;
            $divisions =Division::select("id","name")
            		->where('name','LIKE',"%$search%")
            		->get(['id','name']);
        }

        if($request->has('post')){
            $search = $request->post;
            $posts =Post::select("id","name")
            		->where('name','LIKE',"%$search%")
            		->get(['id','name']);
        }

        if($request->has('qualification')){
            $search = $request->qualification;
            $qualifications =Degree::select("id","name")
            		->where('name','LIKE',"%$search%")
            		->get(['id','name']);
        }

        if($request->has('language')){
            $search = $request->language;
            $languages =Language::select("id","name")
            ->where('name','LIKE',"%$search%")
            ->get(['id','name']);
        }

        if($request->has('country')){
            $search = $request->country;
            $countries =Country::select("id","name")
            ->where('name','LIKE',"%$search%")
            ->get(['id','name']);
        }

        if($request->type == 'entity'){
            $records = $entities;
        }else if($request->type == 'division'){
            $records = $divisions;
        }else if($request->type == 'qualification'){
            $records = $qualifications;
        }else if($request->type == 'post'){
            $records = $posts;
        }else if($request->type == 'language'){
            $records = $languages;
        }else if($request->type == 'country'){
            $records = $countries;
        }else {
            $records = array();
        }

        return response()->json($records);

    }//end of function
}
