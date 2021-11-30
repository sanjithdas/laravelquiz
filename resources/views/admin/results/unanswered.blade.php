

<x-admin-layout>
    @include('admin.navigation')
    <div id="right-panel" class="right-panel">

        <!-- Header-->
        @include('admin.header')
        <!-- /header -->
        <!-- Header-->

<div class="card">
    <div class="card-header">
        {{ trans('titles.result.title') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.results.user_result") }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="required" for="question_id">{{ trans('global.select_user') }}</label>
                   <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                        @foreach($users as $id => $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email}})</option>
                        @endforeach
                    </select>
                    @if($errors->has('user_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('user_id') }}
                        </div>
                    @endif
                    {{-- <span class="help-block">dddddd{{ trans('titles.option.fields.question_helper') }}</span> --}}
                </div>

                <div class="form-group">
                    <label class="required" for="question_id">{{ trans('titles.category.title') }}</label>
                   <select class="form-control select2 {{ $errors->has('question') ? 'is-invalid' : '' }}" name="category_id" id="category_id" required>
                        @foreach($categories as $id => $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.show') }}
                    </button>
                </div>
            </form>
        <div class="table-responsive">
            <table  class=" table table-bordered table-striped table-hover datatable datatable-Question">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('titles.result.fields.user_email') }}
                        </th>
                        <th>
                            {{ trans('titles.result.fields.category') }}
                        </th>
                        <th>
                            {{ trans('titles.result.incorrect_fields.questions') }}
                        </th>
                        <th>
                            {{ trans('titles.result.incorrect_fields.question_text') }}
                        </th>




                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($results as $key => $result)
                        <tr data-entry-id="{{ $result->qid}}">
                            <td>

                            </td>
                            <td>
                                {{ $result->user_email ?? '' }}
                            </td>
                            <td>
                                {{ $result->catname ?? '' }}
                            </td>
                            <td class="">
                               <b> {{ $result->qid ?? ''  }}</b>
                            </td>
                            <td class="">
                                <b> {{ $result->qtext ?? ''  }}</b>
                             </td>


                            {{-- <td nowrap>
                                @can('question_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.questions.show', $question->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('question_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.questions.edit', $question->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('question_delete')
                                    <form action="{{ route('admin.questions.destroy', $question->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                 @endcan

                            </td> --}}

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
  </div>

</div>
    <!-- Right Panel -->
<script>

jQuery(function () {


  let dtButtons = jQuery.extend(true, [], jQuery.fn.dataTable.defaults.buttons)


@can('question_delete')

  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.questions.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {

      var ids = jQuery.map(dt.rows({ selected: true }).nodes(), function (entry) {

          return jQuery(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        jQuery.ajax({
          headers: {'x-csrf-token':  jQuery('meta[name="csrf-token"]').attr('content')},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () {
             location.reload()
         })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

jQuery.extend(true, jQuery.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  jQuery('.datatable-Question:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
    jQuery(jQuery.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>


</x-admin-layout>
