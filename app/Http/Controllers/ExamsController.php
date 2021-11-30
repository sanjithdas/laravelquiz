<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Models\Result;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ExamsController extends Controller
{
    
    /**
     * check if there is any question for the selected category
     * if no record found, redirect to the dashboard.
     * Get first Question
     * c
     */
    public function start(Request $request)
    {


        Session::put('answers',[]) ;
        $category_id = $request->category_id;

        $total_question = $this->totalQnCount($category_id);

     // IF Record found - QUestions and Options.
        if ($total_question > 0) {

           //s dd($request->min);
           $time = date('Y-m-d H:i:s');

           $request->session()->put('time',$time);


            $qid = $this->getQuestionByCategory($category_id);

            // Options of Q1

            $quest_options =   $this->getQuestionsAndOptions($qid,$category_id);


            $total_question_count_per_category = $this->totalQnCount($category_id);

            // Question_order - print the question from 1 to total question count - just to display the order in the exam page blade template.

            $question_order = 1;

            return view("exam", compact('quest_options', 'total_question','category_id','question_order','total_question_count_per_category'));
        }

        else {
            // If no recored redirect to ExamDashboardController with the no_record_message status.
            $no_record_message = true;
            Session::put('no_record_message',$no_record_message);
            return redirect()->route("dashboard");
        }

    }
    /**
     *  Qid > 1, handle all the logic related to get next and previous questions and options.
     *  Store the result into the session and then store the user response into the db.
     */
    public function submitResponse(Request $request)
    {

        $total          = 0;
        $total_correct  = 0;
        $current_qid    = $request->qid;
        $category_id    = $request->category_id;
        $selected_ans   = $request->quiz_answer;
        $correct_choice = $request->correct_choice;
        $question_order = $request->question_order;
        $correct_choice = $this->getCorrectAns($current_qid,$category_id);
        $total_question = $this->totalQnCount($category_id);

        $action         =  $request->input('action');

            $time = date('Y-m-d H:i:s');
           $request->session()->put('time',$time);
           if (isset($request->timer))
            $request->session()->put('timer',$request->timer);
        // Check , there is only one question,

        $max_qn= $this->getMaxQuestionByCategory($category_id);
        if ($current_qid==$max_qn){
            $total_question = $max_qn;
        }

        // If category is > 1 , then the $total_question will be the maximum question in that category.

        if ($category_id!=1)
            $total_question = $max_qn;

        // logic -  Next and previous condition check
        switch (trim($action)) {
            case 'next': {
                    if ($current_qid < $total_question) {
                        $next_qn = $this->getNextQuestion($current_qid,$category_id);
                    }
                    $question_order +=1;
                    break;
                }
            case 'prev': {

                    if ($current_qid > 1 || $current_qid < $total_question) {

                        $next_qn = $this->getPreviousQuestion($current_qid,$category_id);

                    }
                   // $next_qn;
                    $question_order -=1;
                    break;
                }
        }

        // if ($selected_ans == $correct_choice) {

        //     if (session("total")) {
        //         $total  = session()->get("total");
        //         session()->put("total", $total + 1);
        //     } else {
        //         $total = $total + 1;
        //         session()->put("total", $total);
        //     }
        // }

        // if ($action == "prev") {
        //     if (session()->get("total") > 0)
        //         session()->put("total", session()->get("total") - 1);
        // }

        //$total_correct =  session()->get("total");
        session(['answers.' .  $current_qid => $selected_ans]);

        if ($current_qid < $total_question)
            $quest_options =   $this->getQuestionsAndOptions($next_qn,$category_id);

        if ($current_qid == $total_question) {
            if ($action == "prev")
                $quest_options =   $this->getQuestionsAndOptions($next_qn,$category_id);
        }

        /**
         * Insert the response into the  database -  from the session
         */

        if ($request->qid   == $total_question) {
            $my_id =  Auth::user()->id;
          //  $all_correct_choices=$this->getAllCorrectAnswers($category_id);

            $pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';

            if ($action == 'next') {

               if (count(Auth::user()->results)){
                   $this->clearPastResponses($category_id);
               }
                if(session("answers")){

                    foreach (session('answers') as $key=>$value){

                           $my_answers = new Result();
                            $my_answers->user_id = $my_id;
                            $my_answers->question_id = $key;
                            $my_answers->reponse_id = $value;
                            $my_answers->category_id = $category_id;
                            $my_answers->save();
                    }
                }
                /**
                 * clear the session - answers
                 * and rediret to the result page.
                 */
                session()->forget('answers');
                $total_correct = $this->getMyTotalScoresFromDB($category_id);
                $myName = Auth::user()->name;
                $request->session()->flash('total_correct',$total_correct);
                $total_question_per_category=$this->totalQnCount($category_id);
                $request->session()->flash('total_question',$total_question_per_category);
                $request->session()->flash('myName',$myName);
                return redirect('dashboard');

            }
        }
        // return to this view , if more questions are to be displayed
        $total_question_count_per_category = $this->totalQnCount($category_id);
        return view("exam", compact('quest_options', 'total_question','category_id', 'question_order','total_question_count_per_category'));
    }
    /**
     * return first question based on the category.
     */
    public function getQuestionByCategory($category_id){
        $first_question = Question::has('options')->where('category_id',$category_id)->first('id');
        return $first_question->id;
    }
    /**
     * return questions which has only options
     */
    public function getQuestionsAndOptions($question_id,$category_id)
    {
        $question = Question::has('options')->where('category_id',$category_id)->find($question_id);
        return $question;
    }
    /**
     * get the right choice for a particular question based on category.
     */
    public function getCorrectAns($qid,$category_id)
    {
        $option = Option::where('question_id', $qid)->where('correct_choice', '=', 1)->where('category_id',$category_id)->first();
        return $option->choice_id;
    }
    /**
     * get next question - when clicking the next button
     */
    public function getNextQuestion($qid,$category_id)
    {
        $next_Qn = Question::has('options')->where('id', '>', $qid)->where('category_id',$category_id)->first();
        return $next_Qn->id;
    }
    /**
     * get previous question - when clicking the previous question.
     */
    public function getPreviousQuestion($qid,$category_id)
    {

        $next_Qn = Question::has('options')->where('id', '<', $qid)->where('category_id',$category_id)->orderBy('id', 'desc')->firstOrFail();
        return $next_Qn->id;
    }
    /**
     * total question count based on category
     */
    public function totalQnCount($category_id)
    {
        $tot_qn_count = Question::has('options')->where('category_id',$category_id)->count();

        return $tot_qn_count>0 ? $tot_qn_count : 0;
    }
    /**
     * maximum question in the category
     * */
    public function getMaxQuestionByCategory($category_id){
       $maxQn =  DB::table('questions')->Join('options','options.question_id','=','questions.id')
        ->where('questions.category_id',$category_id)
        ->select(DB::raw('max(questions.id) AS maxquestion'))->get();
       return $maxQn[0]->maxquestion;

    }
    /**
     * not in use
     *
     */
    public function getAllCorrectAnswers($category_id){
       $correct_choices =  Option::has('question')->where('correct_choice','=',1)->where('category_id',$category_id)->get(['question_id','choice_id'])->toArray();
       return $correct_choices;
    }
    /**
     * not in use
     */
    public function fetchMyResultFromDb(){
       $my_result_data =  Result::has('user')->where('user_id','=',Auth::user()->id)->get(['question_id','reponse_id'])->toArray();
       return $my_result_data;
    }
    /**
     * @return void
     * clear this users al previous responses.
     */

    public function clearPastResponses($category_id){

       DB::table('results')->join('questions','results.question_id','=','questions.id')
            ->where('results.user_id','=',Auth::user()->id)
            ->where('questions.category_id','=',$category_id)->delete();

    }
    /**
     * return total correct answer count of the logged in user who attended the exam.
     */
    public function getMyTotalScoresFromDB($category_id){
       DB::enableQueryLog();
        $all_my_correct_answers = DB::table("options")
            ->join("results", function($join){
	            $join->on("options.question_id", "=", "results.question_id")
	                ->on("options.choice_id", "=", "results.reponse_id");
                    })
                    ->select(DB::raw('count(results.id) as totresp'))
                    ->where("options.correct_choice", "=", 1)
                ->where("results.user_id", "=", Auth::user()->id)
                ->where('options.category_id',$category_id)
                ->get();
                    $quries = DB::getQueryLog();
                 return $all_my_correct_answers[0]->totresp;
    }
}
