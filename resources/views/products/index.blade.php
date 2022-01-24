

@extends('layouts.app') @section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.product_plural')}} <small>{{trans('lang.product_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('products.index') !!}">{{trans('lang.product_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.product_table')}}</li>
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
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.product_table')}}</a>
        </li>
        @can('products.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('products.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.product_create')}}</a>
        </li>
        @endcan
        <li class="nav-item">
            <a href="xls_samples/products.xlsx" id="STsample"  data-toggle="tooltip" data-placement="bottom" title="{{ __('lang.xlsxsampleSt') }}" download ><img src="images/samplexls.jpg" style="width: 38px; height: 38px"></a>
        </li class="nav-item">
        <li>
            <a  id="xls-btn" onclick="checkandopenmodal()" data-toggle="tooltip" data-placement="bottom" title="{{ __('lang.uploadxlsfileSt') }}" style="margin-left:5px"><img src="images/excel.png" style="width: 38px; height: 38px"></a>
        </li>
        {{-- @include('layouts.right_toolbar', compact('dataTable')) --}}
        <div class="ml-auto d-inline-flex">
            <li id="colVisDatatable" class="nav-item dropdown">
              <a class="nav-link dropdown-toggle pt-1" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-eye"></i> Columns
              </a>
              <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 34px, 0px); top: 0px; left: 0px; will-change: transform;">
                <a class="dropdown-item toggle-vis text-bold" href="#"  data-column="0"> <i class="fa fa-check mr-2 fa0"></i>{{trans('lang.product_name')}}</a>
                <a class="dropdown-item toggle-vis text-bold" href="#"  data-column="1"> <i class="fa fa-check mr-2 fa1"></i>{{trans('lang.product_image')}}</a>
                <a class="dropdown-item toggle-vis text-bold" href="#"  data-column="2"> <i class="fa fa-check mr-2 fa2"></i>{{trans('lang.product_price')}}</a>
                <a class="dropdown-item toggle-vis text-bold" href="#"  data-column="3"> <i class="fa fa-check mr-2 fa3"></i>{{trans('lang.product_discount_price')}}</a>
                <a class="dropdown-item toggle-vis text-bold" href="#"  data-column="4"> <i class="fa fa-check mr-2 fa4"></i>{{trans('lang.product_capacity')}}</a>
                <a class="dropdown-item toggle-vis text-bold" href="#"  data-column="5"> <i class="fa fa-check mr-2 fa5"></i>{{trans('lang.product_featured')}}</a>
                <a class="dropdown-item toggle-vis text-bold" href="#"  data-column="6"> <i class="fa fa-check mr-2 fa6"></i>{{trans('lang.product_market_id')}}</a>
                <a class="dropdown-item toggle-vis text-bold" href="#"  data-column="7"> <i class="fa fa-check mr-2 fa7"></i>{{trans('lang.product_category_id')}}</a>
                <a class="dropdown-item toggle-vis text-bold" href="#"  data-column="8"> <i class="fa fa-check mr-2 fa8"></i>{{trans('lang.product_updated_at')}}</a>
                <a class="dropdown-item toggle-vis text-bold" href="#"  data-column="9"> <i class="fa fa-check mr-2 fa9"></i>{{trans('lang.actions')}}</a>
                </div>
            </li>

        </div>
        </ul>
    </div>


 
    <div class="modal" id="importModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
            <h4 class="modal-title"><div id="model">{{ __('lang.uploadxlsfileSt') }}</div></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
            <form action="{!! url('/product_import') !!}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control">
                <br>
                <select name="category_id" id="category_id" class='select2 form-control' onChange="getsubcategory(this.value)">
                    <option selected disabled>{{ trans("lang.choose_category") }}</option>
                    @foreach ($categories as $category)
                        <option value="{{$category['id']}}">{{$category['name']}}</option>
                    @endforeach
                </select>
                <br>
                <button class="btn btn-success">{{trans('lang.import')}}</button>
            </form>
            </div>
            <div class="card-body">
      
        </div>
            </div>
        </div>
    </div>
    
    <div class="card-body">

          @include('products.table')


      <div class="clearfix"></div>
    </div>
  </div>
</div>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> --}}
<script>

  // $(document).ready(function() {
  //   $('#product_table').DataTable( {
  //     responsive: !1,
  //               columnDefs: [
  //                   {
  //                       orderable: !0,
  //                       targets: 0,
  //                       checkboxes: {
  //                           selectRow: !0
  //                       }
  //                   }
  //               ],
  //               "serverSide": true,
  //               oLanguage: {
  //                   sLengthMenu: "_MENU_",
  //                   sSearch: ""
  //               },
  //               aLengthMenu: [
  //                   [10, 15, 20, 100, 500, 1000],
  //                   [10, 15, 20, 100, 500, 1000]
  //               ],
  //               select: {
  //                   style: "multi"
  //               },
  //               order: [[1, "asc"]],
  //               bInfo: !1,
  //               pageLength: 10,
  //       "ajax": "all-products",
  //       "columns": [
          
  //           { "data": "name" },
  //           { "data": "image" },
  //           { "data": "price" },
  //           { "data": "discount_price" },
  //           { "data": "capacity" },
  //           { "data": "featured" },
  //           { "data": "market.name" },
  //           { "data": "category_name" },
  //           { "data": "updated_at" },
  //           { "data": function (data) {
  //                                               return `  

  //                     <div class='btn-group btn-group-sm'>
  //                                   @can('products.show')
  //                                   <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('products.show', '${data.id}') }}" class='btn btn-link'>
  //                                     <i class="fa fa-eye"></i>
  //                                   </a>
  //                                   @endcan
  //                                   @can('products.edit')
  //                                     <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.product_edit')}}" href="products/${data.id}/edit" class='btn btn-link'>
  //                                       <i class="fa fa-edit"></i>
  //                                     </a>
  //                                     @endcan
                                
  //                                     @can('products.destroy')
  //                                     <form method="POST" action="/products/${data.id}" accept-charset="UTF-8" style="margin-bottom:0"><input name="_method" type="hidden" value="DELETE"><input name="_token" type="hidden" value="IYeQrtlJ2i47jGPHe41Nz3wjFTIul6NxtnuP8fUc">
  //                                         @csrf    <button type="submit" class="btn btn-link text-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
  //                                           </form>
  //                                       @endcan
  //                                 </div>
 
  //                           `
  //                       },
  //                   }
  //           // { "data": "description" },
        
  
  //       ]
  //   } );
  // });

  function checkandopenmodal(){
    $('#importModal').modal('show');
  }
</script>


@endsection

