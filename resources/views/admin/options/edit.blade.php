@include('admin.navigation')
<x-admin-layout>
    <div class="card">
        @include('admin.header')
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('titles.option.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.options.update", [$option->id]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label class="required" for="question_id">{{ trans('titles.option.fields.question') }}</label>
                    <select class="form-control select2 {{ $errors->has('question') ? 'is-invalid' : '' }}" name="question_id" id="question_id" required>
                        @foreach($questions as $id => $question)
                            <option value="{{ $id }}" {{ ($option->question ? $option->question->id : old('question_id')) == $id ? 'selected' : '' }}>{{ $question }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('question_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('question_id') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('titles.option.fields.question_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="choice_text">{{ trans('titles.option.fields.option_text') }}</label>
                    <input type="text" value="{{ old('choice_text', $option->choice_text) }}" class="form-control {{ $errors->has('choice_text') ? 'is-invalid' : '' }}" name="choice_text" id="choice_text" required />
                    <trix-editor input="choice_text" name="choice_text" ></trix-editor>
                    <input type="text" name="choice_id" value="{{ $option->choice_id }}">
                    @if($errors->has('option_text'))
                        <div class="invalid-feedback">
                            {{ $errors->first('option_text') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('titles.option.fields.option_text_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="points">{{ trans('titles.option.fields.points') }}</label>
                    <input class="form-control {{ $errors->has('correct_choice') ? 'is-invalid' : '' }}" type="number" name="correct_choice" id="correct_choice" value="{{ old('correct_choice', $option->correct_choice) }}" step="1">
                    @if($errors->has('correct_choice'))
                        <div class="invalid-feedback">
                            {{ $errors->first('correct_choice') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('titles.option.fields.points_helper') }}</span>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-admin-layout>
