@include('admin.navigation')
    <x-admin-layout>
        <div class="card">
            @include('admin.header')
            <div class="card-header">
                {{ trans('global.show') }} {{ trans('titles.option.title') }}
            </div>

            <div class="card-body">
                <div class="form-group">
                    <div class="form-group">
                        <a class="btn btn-default" href="{{ route('admin.options.index') }}">
                            {{ trans('global.back_to_list') }}
                        </a>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>
                                    {{ trans('titles.option.fields.id') }}
                                </th>
                                <td>
                                    {{ $option->choice_id }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('titles.option.fields.question') }}
                                </th>
                                <td>
                                    {{ $option->question->question_text ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('titles.option.fields.option_text') }}
                                </th>
                                <td>
                                    {{ $option->choice_text }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('titles.option.fields.points') }}
                                </th>
                                <td>
                                    {{ $option->correct_choice }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <a class="btn btn-default" href="{{ route('admin.options.index') }}">
                            {{ trans('global.back_to_list') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-admin-layout>

