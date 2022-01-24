@extends('layouts.settings.default')
@push('css_lib')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
@endpush

    @if (  __('lang.language') == "ar")
    <style>
    .main-sidebar, .main-sidebar{
    right:0 !important;
    left:unset !important;
}

.content-wrapper, .main-footer
{
    direction: rtl !important;
    margin-left:0 !important;
    margin-right:270px !important;
}
 /* .main-header{
    margin-left: 0;
    flex-direction: row-reverse;
} */

/* .navbar-expand > .navbar-nav{
    margin-left:auto;
} */

.main-header{
    margin-left:0 !important;
    margin-right:270px !important;
    flex-direction: row-reverse !important;
}
.navbar-expand .navbar-nav{
    margin-left:0 !important;
}
.navbar-expand .navbar-nav:first-child
{margin-left: auto !important}

.navbar-expand .navbar-nav .dropdown-menu{
    left:0 !important;
}

.sidebar-collapse .content-wrapper, .sidebar-collapse
.main-footer, .sidebar-collapse
.main-header {
    margin-right: 0 !important;
    margin-left: 0 !important;
}
.sidebar-mini.sidebar-collapse .content-wrapper, .sidebar-mini.sidebar-collapse .main-footer, .sidebar-mini.sidebar-collapse .main-header{
    margin-left: 0 !important;
    margin-right: 4.6rem !important;
    
    
}
.content-header{
    direction: ltr !important;;
}

.text-right {
    text-align: left !important;
}
.dropzone .dz-message span:before{
  left: -155px;
}
</style>
    @endif
@section('settings_title',trans('lang.role_create'))
@section('settings_content')
    @include('adminlte-templates::common.errors')
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                <li class="nav-item">
                    <a class="nav-link" href="{!! route('permissions.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.permission_table')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{!! route('permissions.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.permission_create')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{!! route('roles.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.role_table')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{!! route('roles.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.role_create')}}</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            {!! Form::open(['route' => 'roles.store']) !!}
            <div class="row">
                @include('settings.roles.fields')
            </div>
            {!! Form::close() !!}
            <div class="clearfix"></div>
        </div>
    </div>
@endsection
@push('scripts_lib')
    <!-- iCheck -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
    <!-- select2 -->
    <script src="{{asset('plugins/select2/select2.min.js')}}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
@endpush


