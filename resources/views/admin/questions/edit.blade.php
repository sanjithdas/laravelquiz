@include('admin.navigation')
<x-admin-layout>
    <div class="card">
        @include('admin.header')
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('titles.question.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.questions.update", [$question->id]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label class="required" for="category_id">{{ trans('titles.question.fields.category') }}</label>
                    <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id" required>
                        @foreach($categories as $id => $category)
                            <option value="{{ $id }}" {{ ($question->category ? $question->category->id : old('category_id')) == $id ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('category_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('category_id') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('titles.question.fields.category_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="question_text">{{ trans('titles.question.fields.question_text') }}</label>
                    <textarea class="form-control {{ $errors->has('question_text') ? 'is-invalid' : '' }}" name="question_text" id="question_text" required>{{ old('question_text', $question->question_text) }}</textarea>
                    <trix-editor input="question_text"></trix-editor>
                    @if($errors->has('question_text'))
                        <div class="invalid-feedback">
                            {{ $errors->first('question_text') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('titles.question.fields.question_text_helper') }}</span>
                </div>
                <div class="form-group">
                    <label  for="image">{{ trans('titles.question.fields.image') }}</label>
                    <input type="file" class="form-control" name="description" id="description" value={{ old('description') }}>
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
