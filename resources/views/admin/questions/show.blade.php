@include('admin.navigation')
    <x-admin-layout>
        <div class="card">
            @include('admin.header')
            <div class="card-header">
                {{ trans('global.show') }} {{ trans('titles.question.title') }}
            </div>

            <div class="card-body">
                <div class="form-group">
                    <div class="form-group">
                        <a class="btn btn-default" href="{{ route('admin.questions.index') }}">
                            {{ trans('global.back_to_list') }}
                        </a>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>
                                    {{ trans('titles.question.fields.id') }}
                                </th>
                                <td>
                                    {{ $question->id }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('titles.question.fields.category') }}
                                </th>
                                <td>
                                    {{ $question->category->name ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('titles.question.fields.question_text') }}
                                </th>
                                <td>
                                    {{ $question->question_text }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <a class="btn btn-default" href="{{ route('admin.questions.index') }}">
                            {{ trans('global.back_to_list') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </x-admin-layout>
