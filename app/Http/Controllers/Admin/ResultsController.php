<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResultFetchRequest;
use App\Models\Category;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResultsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Write seperate functions for this,
        $questions = Question::all();
        $categories = Category::all();
        $users = User::all();

        DB::enableQueryLog();

        $resultQuery = DB::select('call getResultsReport');


        $quries = DB::getQueryLog();
        $results = $resultQuery;

        return view('admin.results.index',compact('results','users','categories'));
    }

    /**
     * get detailed results
     */

    public function getResults(ResultFetchRequest $request){
       echo $request->user_id . '--'. $request->category_id;
        DB::enableQueryLog();
         $all_my_correct_answers = DB::table("options")
             ->join("results", function($join){
                 $join->on("options.question_id", "=", "results.question_id")
                     ->on("options.choice_id", "=", "results.reponse_id");
                     })
                     ->select(DB::raw('count(results.id) as totresp'))
                     //->where("options.correct_choice", "=", 1)
                 ->where("results.user_id", "=", $request->user_id)
                 ->where('options.category_id',$request->category_id)
                 ->get();
                     $quries = DB::getQueryLog();
                     dd($all_my_correct_answers);
                  return $all_my_correct_answers[0]->totresp;
     }

     public function getIncorrectAnswers(Request $request){


        $res = $this->getIncorrectResultFromDB($request->catid,$request->user);

        $questions = Question::all();
        $categories = Category::all();
        $users = User::all();
       // $resultQuery = DB::select('call getResultsReport');
         $results = $res;

        //   echo "<pre>";
        // print_r($results);
        // echo "</pre>";

        return view('admin.results.incorrect-answers',compact('results' ,'categories','users'));
     }

     public function getUnansweredQns(Request $request){

        $res = $this->getUnattendedQns($request->catid,$request->user);

        $questions = Question::all();
        $categories = Category::all();
        $users = User::all();

        $results = $res;
        // echo "<pre>";
        // print_r($results);
        // echo "</pre>";
        return view('admin.results.unanswered',compact('results' ,'categories','users'));
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getIncorrectResultFromDB($category_id, $userid){


        $all_my_wrong_answers = DB::select("call getWrongResponse($userid,$category_id)");
        return $all_my_wrong_answers;

        // DB::enableQueryLog();
        //  $all_my_correct_answers = DB::table("options")
        //      ->join("results", function($join){
        //          $join->on("options.question_id", "=", "results.question_id")
        //              ->on("options.choice_id", "=", "results.reponse_id");
        //              })
        //              //->select(DB::raw('count(results.id) as totresp'))
        //              ->where("options.correct_choice", "=", 0)
        //          ->where("results.user_id", "=", $userid)
        //          ->where('options.category_id',$category_id)
        //          ->get();
        //           $quries = DB::getQueryLog();
        //           print_r($quries);
     }

     function getUnattendedQns($catid, $userid){
        DB::enableQueryLog();
        $all_my_unattended_qns = DB::select("call getUnattendedQns($userid,$catid)");
        $quries = DB::getQueryLog();
        print_r($quries);
        return $all_my_unattended_qns;
     }

}
