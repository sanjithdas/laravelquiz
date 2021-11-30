<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOptionRequest;
use App\Http\Requests\StoreOptionRequest;
use App\Http\Requests\UpdateOptionRequest;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class OptionsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('option_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $options = Option::all();

        return view('admin.options.index', compact('options'));
    }

    public function create()
    {
        abort_if(Gate::denies('option_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $questions = Question::all()->pluck('question_text', 'id')->prepend(trans('global.pleaseSelect'), '');


        return view('admin.options.create', compact('questions'));
    }

    public function store(StoreOptionRequest $request)
    {

        $data = $request->all();
        $qid = $request->question_id;

        $category = Question::select('category_id')->where('id',$qid)->get();
        $category_id = $category[0]->category_id;

        // retrieve the max choice_id based on the question.

       $max_choice_per_question=DB::table('QUESTIONS')->Join('OPTIONS','options.question_id','=','questions.id')
       ->select(DB::raw('MAX(options.choice_id)+1 as choice_id'))
       ->where('questions.id','=',$qid)
       ->groupBy('questions.ID','QUESTIONS.QUESTION_TEXT')->get() ;

        //return  $max_choice_per_question;
        $choice_id = count($max_choice_per_question)>0 ? $max_choice_per_question[0]->choice_id : 1;

        $data["choice_id"] = $choice_id;
        $data["category_id"] = $category_id;

        $option = Option::create($data);
        return redirect()->route('admin.options.index');
    }

    public function edit(Option $option)
    {
        abort_if(Gate::denies('option_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $questions = Question::all()->pluck('question_text', 'id')->prepend(trans('global.pleaseSelect'), '');

        $option->load('question');

        return view('admin.options.edit', compact('questions', 'option'));
    }

    public function update(UpdateOptionRequest $request, Option $option)
    {
        $data = $request->all();
        $qid = $request->question_id;

        $category = Question::select('category_id')->where('id',$qid)->get();
        $category_id = $category[0]->category_id;

        $data["category_id"] = $category_id;

        //return $data;

        $option->update($data);


        return redirect()->route('admin.options.index');
    }

    public function show(Option $option)
    {
        abort_if(Gate::denies('option_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $option->load('question');

        return view('admin.options.show', compact('option'));
    }

    public function destroy(Option $option)
    {
        abort_if(Gate::denies('option_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $option->delete();

        return back();
    }

    public function massDestroy(MassDestroyOptionRequest $request)
    {
        Option::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
