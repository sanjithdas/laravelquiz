@include('admin.navigation')

<x-admin-layout>
    @include('admin.header')

    @can('option_create')
    <div style="margin-bottom: 10px;" class="">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.options.create") }}">
                {{ trans('global.add') }} {{ trans('titles.option.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('titles.option.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="options-table-export" class=" table table-bordered table-striped table-hover datatable datatable-Option">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('titles.option.fields.id') }}
                        </th>
                        <th>
                            {{ trans('titles.option.fields.question') }}
                        </th>
                        <th>
                            {{ trans('titles.option.fields.option_text') }}
                        </th>
                        <th>
                            {{ trans('titles.option.fields.points') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($options as $key => $option)
                        <tr data-entry-id="{{ $option->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $option->id ?? '' }}
                            </td>
                            <td>
                                {{ $option->question->question_text ?? '' }}
                            </td>
                            <td>
                                {{ $option->choice_text ?? '' }}
                            </td>
                            <td>
                                {{ $option->correct_choice ?? '' }}
                            </td>
                            <td nowrap>
                                @can('option_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.options.show', $option->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('option_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.options.edit', $option->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('option_delete')
                                    <form action="{{ route('admin.options.destroy', $option->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
     jQuery(function () {

let dtButtons = jQuery.extend(true, [], jQuery.fn.dataTable.defaults.buttons)
@can('option_delete')

    let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'

    let deleteButton = {
        text: deleteButtonTrans,
        url: "{{ route('admin.options.massDestroy') }}",
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
            .done(function (result) {

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
    jQuery('.datatable-Option:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        jQuery(jQuery.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    })
</script>
</x-admin-layout>
