<link rel="stylesheet" href="css/dashboard.css" />
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ trans('global.exam_dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="py-7 flex-container-div border border-transparent rounded-md font-semibold  ">
             <div class="flex-box-left">
                @auth

                @if (Session::get('total_question'))

                <div class="    text-xl text-gray-800 leading-tight">
                     Thanks <b> &nbsp; {{ Session::get('myName') }} &nbsp;</b> for completing this Quiz. </div>

                 <h1 class="mt-12  font-semibold text-xl text-gray-800 leading-tight" style="margin-left: 1rem">

                    Your score is:  {{ Session::get('total_correct') > Session::get('total_question')?
                     Session::get('total_question'): (Session::get('total_correct') > 0 ? Session::get('total_correct')  : 0) }} out of
                     {{ Session::get('total_question') }}

                  </h1>

                   @if (session()->has('total'))
                      {{
                          session()->forget('total','answers')

                      }}
                  @endif
              @else

              @if(isset($no_record_message))

              <label class="font-semibold text-xl text-gray-800  mr-10 leading-tight required" for="category_id">
                  {{ trans('global.no_record_message') }}</label>

              @endif

                  <div class="relative flex justify-center content-center pt-8  sm:pt-0" style="margin-top:130px; margin-left:-340px;">
                      <div>
                      <form  method="post" action="{{asset(route('start-exam'))}}">
                          @csrf
                      <label class="font-semibold text-xl text-gray-800 ml-1 mr-12 leading-tight required" for="category_id"
                       >
                          {{ trans('titles.category.title_select') }}</label>
                          <select class="form-control text-xl items-center with-border px-4 py-2 bg-gray-800 border border-transparent rounded-md font-bold  hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 mr-5 transition select2  {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id" required>
                              @foreach($categories as  $category)
                                  <option value="{{ $category->id }}" class="text-black font-bold form-group" >{{ strtoupper($category->name) }}</option>
                              @endforeach
                          </select>
                          @if($errors->has('category_id'))
                              <div class="invalid-feedback">
                                  {{ $errors->first('category_id') }}
                              </div>
                          @endif


                          <button type="submit"
                          class="inline-flex  items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xl text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" >Start Test</button>
                      </form>
                  </div>

              </div>
              @endif

           @endauth
             </div>

             <div class="flex-box-right">
                @if (!(Session::get('total_question')))
                 <div class="head">
                    <h4>Instructions to follow:-</h4>
                 </div>
                <ol class="instruction-list">
                    <li>Time allotted : 1 hr. </li>
                    <li>Click the submit button at the end of the questions to save your result.</li>
                    <li>If you quit the exam without clicking the submitt button, your results will not be recorded, If so I will ask you to write second time.
                    <li>All your steps should be written on your work book.</li>
                    <li>All your assessments steps should be produced when requested.</li>
                    <li>After  submit your answers, Please signout using the link which is provided in the right corner (may be the first two letters of your name) </li>
                  </ol>
                  @endif
             </div>

        </div>
    </div>

    </x-app-layout>

