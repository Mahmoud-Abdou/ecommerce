@extends('layouts.app')

@section('content')
<style>
    
    .page-item.disabled .page-link, .page-link:not(:disabled):not(.disabled)  {
        width: auto !important;
    }
    .dataTables_filter {
        height: 60px;
        text-align: right;
    }
 table.data-list-view.dataTable thead th:first-child, table.data-thumb-view.dataTable thead th {
    text-align: center;}
    table.data-thumb-view.dataTable tbody tr td {
    text-align: center;
    }
</style>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.product_order_plural')}} <small>{{trans('lang.product_order_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('productOrders.index') !!}">{{trans('lang.product_order_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.product_order_table')}}</li>
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
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.product_order_table')}}</a>
        </li>
        @can('productOrders.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('productOrders.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.product_order_create')}}</a>
        </li>
        @endcan
        @include('layouts.right_toolbar', compact('dataTable'))
      </ul>
    </div>
    <div class="card-body">
  
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@endsection

@push('scripts_lib')
@include('layouts.datatables_js')

  <script>
    function checkandopenmodal(){
      $('#importModal').modal('show');
    }
 
  $(document).ready(function() {
        var table= $('#example').DataTable( {
            "serverside": true,
            "ordering": false,
            "orderClasses": false,
            responsive: true,
            "dom":  "<'row'<'col-md-4'l><'col-md-4'B><'col-md-4'f><'col-md-12't><'col-md-4'i><'col-md-4'><'col-md-4'p>>",
            "paging": true,
             charset: 'UTF-8',
            "buttons": 
            [
                {
                    "extend": "csv",
                    "text": "{{ __('lang.excel') }}",
                    "filename": "{{ __('lang.categories') }}",
                    "className": "btn btn-green",
                    "charset": "utf-8",
                    "bom": "true",
                    init: function(api, node, config) {
                        $(node).removeClass("btn-default");
                    },
                
                },
                {
                    "extend": "print",
                    "text": "{{ __('lang.pdf') }}",
                    "filename": "{{ __('lang.categories') }}",
                    "className": "btn btn-green",
                    "charset": "utf-8",
                    "bom": "true",
                    init: function(api, node, config) {
                        $(node).removeClass("btn-default");
                    },
                    
                }
            ],
        "ajax": "get-product-orders?id="+"{{$id}}",
        "columns":
        [
            { "data": "name" , "defaultContent": "" },
           
               {
                            "data": function (data) {
                               
                                return `<img width="100" height="100" src="${data.image}">`
                            },
                        },
           
            { "data": "price" , "defaultContent": "" }, 
            { "data": "quantity" , "defaultContent": "" },
            
              
        ],
    });
    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = table.column( $(this).attr('data-column') );
        $(".fa"+$(this).attr('data-column')).toggle();
        // Toggle the visibility
            column.visible( ! column.visible() );
        } );
   
    })
 </script>
@endpush