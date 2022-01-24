@extends('layouts.settings.default')
@push('css_lib')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
@endpush
@section('settings_title',trans('lang.permission_table'))

@section('settings_content')
<style>
    
    .page-item.disabled .page-link, .page-link:not(:disabled):not(.disabled)  {
        width: auto !important;
    }
    .dataTables_filter {
        height: 60px;
        text-align: right;
    }


</style>
    @include('flash::message')
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                <li class="nav-item">
                    <a class="nav-link active" href="{!! route('permissions.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.permission_table')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{!! route('permissions.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.permission_create')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{!! route('roles.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.role_table')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{!! route('roles.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.role_create')}}</a>
                </li>

                {{-- @include('layouts.right_toolbar', compact('dataTable')) --}}
            </ul>
        </div>
        <div class="card-body">
        <div class="container">
            <div class="table-responsive">
                <table id="example" class="data-table table "
                        class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>{{trans('lang.permission_name')}}</th>
                            <th>{{trans('lang.permission_guard_name')}}</th>
                            @foreach($roles as $role)
                                <th>{{trans('lang.'.$role->name)}}</th>
                            @endforeach
                            <th>{{trans('lang.actions')}}</th>
                        
                        </tr>
                    </thead>
                    <tfoot>
                        <!-- <tr>
                            <th>{{trans('lang.category_name')}}</th>
                            <th>{{trans('lang.category_image')}}</th>
                            <th>{{trans('lang.category_updated_at')}}</th>
                        
                        </tr> -->
                    </tfoot>
                </table>
            </div>
        </div>
      <!-- @include('settings.permissions.table') -->
            <div class="clearfix"></div>
        </div>
    </div>


@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
    var roles;
    function get_roles(){
        $.ajax({
            url: "{{url('/get-roles')}}",
            method: 'get',
            success: function(res) {
                roles = res.roles;
                init_table();   
            }
        });
    }
    $(document).ready(function() {
        get_roles();
        renderiCheck('example');
    });
    function init_table(){
        var columns = [
            { "data": "permission_name" },
            { "data": "gaurd_name" }
        ];
        for(let role in roles){
            
            columns.push({"data": roles[role].name});
        }
        $('#example').DataTable(
            {
                lengthMenu: [
                    [ 10, 25, 50, 100, 250, 500, 1000, 2000, 5000],
                    [ 10, 25, 50, 100, 250, 500, 1000, 2000, 5000]
                ],
                pageLength: 10,
                processing: true,
                serverSide: true,
                paging: true,
                    "ajax": "{{url('/all-permissions')}}",
                    "columns": columns
            }
            );
        }

</script>
@push('scripts_lib')
    <!-- iCheck -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
@endpush

