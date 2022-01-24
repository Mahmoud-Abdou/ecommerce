

@extends('layouts.app') 
@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.category_plural')}}
          <small>{{trans('lang.category_desc')}}</small>
        </h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">
            <a href="{{url('/dashboard')}}">
              <i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a>
          </li>
          <li class="breadcrumb-item">
            <a href="{!! route('categories.index') !!}">{{trans('lang.category_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.category_table')}}</li>
        </ol>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}">
            <i class="fa fa-list mr-2"></i>{{trans('lang.category_table')}}</a>
        </li>
        @can('categories.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('categories.create') !!}">
            <i class="fa fa-plus mr-2"></i>{{trans('lang.category_create')}}</a>
          </li>
          @endcan 
          <li class="nav-item">
                <a href="xls_samples/categoris.xlsx" id="STsample"  data-toggle="tooltip" data-placement="bottom" title="{{ __('lang.xlsxsampleSt') }}" download ><img src="images/samplexls.jpg" style="width: 38px; height: 38px"></a>
          </li>
          <li class="nav-item">
                <a  id="xls-btn" onclick="checkandopenmodal()" data-toggle="tooltip" data-placement="bottom" title="{{ __('lang.uploadxlsfileSt') }}" style="margin-left:5px"><img src="images/excel.png" style="width: 38px; height: 38px"></a>
          </li>

        {{-- @include('layouts.right_toolbar', compact('dataTable')) --}}
        <div class="ml-auto d-inline-flex">
            {{-- <li class="nav-item dropdown">
                <a class="nav-link  dropdown-toggle pt-1" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-save"></i> Export
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" id="exportCsvDatatable" href="#"> <i class="fa fa-file-excel-o mr-2"></i>CSV</a>
                    <a class="dropdown-item" id="exportExcelDatatable" href="#"> <i class="fa fa-file-excel-o mr-2"></i>Excel</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" id="exportPdfDatatable" href="#"> <i class="fa fa-file-pdf-o mr-2"></i>PDF</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link pt-1" id="refreshDatatable" href="#"><i class="fa fa-refresh"></i> Refresh</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pt-1" id="printDatatable" href="#"><i class="fa fa-print"></i> Print</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pt-1" id="resetDatatable" href="#"><i class="fa fa-undo"></i> Reset</a>
            </li> --}}
            <li id="colVisDatatable" class="nav-item dropdown">
                <a class="nav-link dropdown-toggle pt-1" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-eye"></i> Columns
                </a>
                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 34px, 0px); top: 0px; left: 0px; will-change: transform;">
                  <a class="dropdown-item toggle-vis text-bold" href="#"  data-column="0"> <i class="fa fa-check mr-2 fa0"></i>{{trans('lang.category_name')}}</a>
                  <a class="dropdown-item toggle-vis text-bold" href="#"  data-column="1"> <i class="fa fa-check mr-2 fa1"></i>{{trans('lang.category_image')}}</a>
                  <a class="dropdown-item toggle-vis text-bold" href="#"  data-column="2"> <i class="fa fa-check mr-2 fa2"></i>{{trans('lang.category_updated_at')}}</a>
                  <a class="dropdown-item toggle-vis text-bold" href="#"  data-column="3"> <i class="fa fa-check mr-2 fa3"></i>{{trans('lang.actions')}}</a>
                  
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
            <form action="{!! url('/category_import') !!}" method="POST" enctype="multipart/form-data">
              @csrf
              <select name="parent_id" id="parent_id" class='select2 form-control'>
                  <option selected disabled>{{ trans("lang.choose_parent_category") }}</option>
                  @foreach ($categories as $category)
                      <option value="{{$category['id']}}">{{$category['name']}}</option>
                  @endforeach
              </select>
              <br>

              <input type="file" name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="form-control">
              <br>
              <button class="btn btn-success">{{trans('lang.import')}}</button>
            </form>
            </div>
            <div class="card-body">
      
    </div>
        </div>
    </div>
</div>
          @include('categories.table')


 


    
    
      <div class="clearfix"></div>
    </div>
  </div>
</div>


@endsection