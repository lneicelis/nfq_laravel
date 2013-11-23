@extends('admin.layouts.master')

@section('content')

    <h3 class="header smaller lighter blue">Users</h3>
    <div class="table-header">
        Results for "All users"
    </div>

    <div class="table-responsive">
        <table id="sample-table-2" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th class="center">Id</th>
                <th>Email</th>
                <th>First name</th>
                <th>Last name</th>
                <th class="hidden-480">Registered</th>

                <th>
                    <i class="icon-time bigger-110 hidden-480"></i>
                    Update
                </th>
                <th class="hidden-480">Activated</th>

                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->first_name }}</td>
                    <td>{{ $user->last_name }}</td>
                    <td>{{ $user->created_at }}</td>
                    <td>{{ $user->activated_at }}</td>
                    <td>{{ $user->group }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@stop

@section('scripts')
    <script src="{{ URL::asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery.dataTables.bootstrap.js') }}"></script>

    <script type="text/javascript">
        (function($) {
            var oTable1 = $('#sample-table-2').dataTable( {
                "aoColumns": [
                    { "bSortable": false },
                    null, null, null, null, null,
                    { "bSortable": false }
                ] } );


            $('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
            function tooltip_placement(context, source) {
                var $source = $(source);
                var $parent = $source.closest('table')
                var off1 = $parent.offset();
                var w1 = $parent.width();

                var off2 = $source.offset();
                var w2 = $source.width();

                if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
                return 'left';
            }
        })(jQuery)
    </script>

@stop