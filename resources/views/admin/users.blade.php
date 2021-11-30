
@include('admin.navigation')
<x-admin-layout>
    @include('admin.header')

    @can('option_create')
    <div style="margin-bottom: 10px;" class="">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.users.create") }}">
                {{ trans('global.add') }} {{ trans('titles.user.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('titles.user.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th>
ffff
                        </th>
                        <th>
                            {{ trans('titles.user.fields.id') }}
                        </th>
                        <th>
                            {{ trans('titles.user.fields.name') }}
                        </th>
                        <th>
                            {{ trans('titles.user.fields.email') }}
                        </th>
                        <th>
                            {{ trans('titles.user.fields.email_verified_at') }}
                        </th>
                        <th>
                            {{ trans('titles.user.fields.roles') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        <tr data-entry-id="{{ $user->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $user->id ?? '' }}
                            </td>
                            <td>
                                {{ $user->name ?? '' }}
                            </td>
                            <td>
                                {{ $user->email ?? '' }}
                            </td>
                            <td>
                                {{ $user->email_verified_at ?? '' }}
                            </td>
                            <td>
                                @foreach($user->roles as $key => $item)
                                    <span class="badge badge-info">{{ $item->title }}</span>
                                @endforeach
                            </td>
                            <td nowrap>
                                @can('user_access')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.users.show', $user->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('user_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.users.edit', $user->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('user_delete')

                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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


      @can('user_delete')



        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'

        let deleteButton = {
          text: deleteButtonTrans,
          url: "{{ route('admin.users.massDestroy') }}",
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
        jQuery('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
        jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
          jQuery(jQuery.fn.dataTable.tables(true)).DataTable()
                  .columns.adjust();
          });
      })

      </script>

</x-admin-layout>
