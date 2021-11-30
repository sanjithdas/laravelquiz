<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Examcategory selection
 */

class ExamDashboardController extends Controller
{
    public function index(){

        $categories = Category::where('display_status',1)->orderBy('id')->get();
        if (Session::get("no_record_message")){
            $no_record_message = Session::get("no_record_message");
            return view('dashboard',compact('categories','no_record_message'));
        }
        return view('dashboard',compact('categories'));
    }
}
