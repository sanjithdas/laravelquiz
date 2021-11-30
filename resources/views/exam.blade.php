
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight ">
            {{ __('PHP Quiz') }}
        </h2>
        <div class="flex  flex-column  align-items-right align-content-end m-7  ">
            Time remaining:
        <span id="cd-minutes" class="ml-5       align-items-end"></span> :
        <span id="cd-seconds"></span>

        </div>
    </x-slot>

    <div class="py-12  flex   align-content-center justify-center " >
        <div class="border-8 border-dotted border-gray-400
                    " style="min-width: 600px;">
            <h2 class="py-2 px-5">Question {{ $question_order  }} of {{ $total_question_count_per_category }}:</h2>
                <p class="font-semibold text-xl text-gray-800 leading-tight ">{{ $question_order }}.{!!  $quest_options->question_text !!}</p>
                    <form role="form" id="quizform" name="quizform" action="{{ asset(route('submit-response')) }}" method="post">

                        @csrf

                        <input type="hidden" name="question_order" value="{{ $question_order }}">
                        <input type="hidden" name="prevnumber" value="1">
                        <input type="hidden" name="qid" value= "{{ $quest_options->id }}">
                        <input type="hidden" name="total_question" value= "{{ $total_question }}">

                        {{-- <input type="hidden" name="hour" id="hour"> --}}
                        <input type="hidden" name="timer" id="time">
                        <input type="hidden" name="sec" id="sec">

                        <input type="hidden" name="category_id" value="{{ $category_id }}">


                        <div  class="py-3">
                            <div id="altcontainer" class="">
                                @foreach ($quest_options->images as $img )
                                <p class="px-12 py-2">
                                   <img src="{{ asset('images/questions/'.$img->description) }}"  width="370px" height="170px"/>
                                </p>
                                {{-- <input type="text" name="correct_choice" value="{{ $opt->correct_choice }}"> --}}
                                @endforeach
                            </div>
                        </div>

                        <div  class="py-3">
                            <div id="altcontainer" class="">
                                @foreach ($quest_options->options as $opt )
                                <p class="px-12 py-2">
                                <input type="radio" class="form form-control" name="quiz_answer" id="{{ $opt->choice_id }}"  value="{{ $opt->choice_id }}"><span class="checkmark"></span>
                                <label class="radiocontainer" id="label1"> {{ $opt->choice_id }}. &NonBreakingSpace;&NonBreakingSpace; {!! strip_tags($opt->choice_text,'<div>') !!} </label>
                                </p>
                                {{-- <input type="text" name="correct_choice" value="{{ $opt->correct_choice }}"> --}}
                                @endforeach
                            </div>
                        </div>

                        <div class="flex   align-content-center justify-center">
                            @if ($question_order > 1)
                            <button id="prev" class="inline-flex align-content-center  items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"  type="submit" name="action"  value="prev">Previous

                            </button>
                            @endif
                            <button id="next" class="inline-flex ml-3 items-center px-4  py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" type="submit"  name="action"  value="next">Next ❯</button>
                        </div>

                    </form>
                <hr>
        </div>
    </div>

</x-app-layout>

<script>
     let timer = function (date) {
    let timer = Math.round(new Date(date).getTime()/1000) - Math.round(new Date().getTime()/1000);
        @if (Session::has('timer'))
            timer = "{{ Session::get('timer') }}"
        @endif
		let minutes, seconds;
        //alert(timer);
		setInterval(function () {
                if (--timer < 0) {
                    timer = 0;
                }

             //   alert(timer);

			//days = parseInt(timer / 60 / 60 / 24, 10);
			//hours = parseInt((timer / 60 / 60) % 24, 10);
			minutes = parseInt((timer / 60) % 60, 10);
			seconds = parseInt(timer % 60, 10);

			//days = days < 10 ? "0" + days : days;
			//hours = hours < 10 ? "0" + hours : hours;
			minutes = minutes < 10 ? "0" + minutes : minutes;
			seconds = seconds < 10 ? "0" + seconds : seconds;


            document.getElementById('time').value=timer;
            document.getElementById('sec').value=seconds;

			//document.getElementById('cd-days').innerHTML = days;
			//document.getElementById('cd-hours').innerHTML = hours;
			document.getElementById('cd-minutes').innerHTML = minutes;
			document.getElementById('cd-seconds').innerHTML = seconds;

		}, 1000);
	}

//using the function
@if (Session::get('time'))
let time =    "{{  Session::get('time') }}";
@endif


const today = new Date(time)

//document.getElementById('sec').value=seconds;

today.setDate(today.getDate() +1)
timer(today);
     </script>

