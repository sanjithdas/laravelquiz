
@include('admin.navigation')
<x-admin-layout>
    <div class="card">
        @include('admin.header')
        <div class="card">
            <div class="card-header">
                {{ trans('global.edit') }} {{ trans('titles.category.title_singular') }}
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route("admin.categories.update", [$category->id]) }}" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('titles.category.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('titles.category.fields.name_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <select name="display_status">
                            <option value="1"> Visible  </option>
                            <option value="0"> Invisible </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-danger" type="submit">
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
