@include('admin.navigation')
<x-admin-layout>

    <div id="right-panel" class="left-panel">
        @include('admin.header')
        <!-- Header-->

        @can('category_create')
    <div style="margin-bottom: 10px;" class="">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.categories.create") }}">
                {{ trans('global.add') }} {{ trans('titles.category.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('titles.category.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="bootstrap-data-table-export" class="table table-bordered table-striped table-hover datatable datatable-Category">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('titles.category.fields.id') }}
                        </th>
                        <th>
                            {{ trans('titles.category.fields.name') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $key => $category)
                        <tr data-entry-id="{{ $category->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $category->id ?? '' }}
                            </td>
                            <td>
                                {{ $category->name ?? '' }}
                            </td>
                            <td nowrap>
                                @can('category_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.categories.show', $category->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('category_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.categories.edit', $category->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('category_delete')
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
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
    </div>
    <script>
        // jQuery(document).ready(function () {
        //     jQuery('#bootstrap-data-table-export').DataTable();
        // });
    jQuery(function () {

    let dtButtons = jQuery.extend(true, [], jQuery.fn.dataTable.defaults.buttons)
    @can('category_delete')

        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'

        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.categories.massDestroy') }}",
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
        jQuery('.datatable-Category:not(.ajaxTable)').DataTable({ buttons: dtButtons })
        jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            jQuery(jQuery.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })

    </script>
</x-admin-layout>
