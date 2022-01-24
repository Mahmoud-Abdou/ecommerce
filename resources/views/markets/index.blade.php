@extends('layouts.app')

@section('content')
@if (  __('lang.language') == "ar")
        <link rel="stylesheet" href="css/styleRtl.css">

    @endif
    @if (  __('lang.language') != "ar")
        <style>
        .navbar-expand .navbar-nav:first-child
        {margin-right: auto !important}
        </style>
    @endif

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
.slimScrollBar{
    background:black !important;
    cursor:grap;
}
.breadcrumb-item + .breadcrumb-item::before {
    display: inline-block !important;
    padding-left: 0.5rem !important;
    color: #6c757d !important;
   
    content: "/" !important;
}

.content-header .breadcrumb{
    direction: rtl !important;
}
.content-header {
    direction: rtl !important;
}

.content-header {
    text-align: start;
}

</style>
    @endif
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.market_plural')}} <small>{{trans('lang.market_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('markets.index') !!}">{{trans('lang.market_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.market_table')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.market_table')}}</a>
        </li>
        @can('markets.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('markets.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.market_create')}}</a>
        </li>
        @endcan
        @include('layouts.right_toolbar', compact('dataTable'))
      </ul>
    </div>
    <div class="card-body">
      @include('markets.table')
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@endsection

