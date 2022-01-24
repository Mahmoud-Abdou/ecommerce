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
 
    .page-item.disabled .page-link, .page-link:not(:disabled):not(.disabled)  {
        width: auto !important;
    }
    .dataTables_filter {
        height: 60px;
        text-align: right;
    }

     .gallery-wrapper {
         max-width: 960px;
         width: 100%;
         margin: 0 auto;
         padding: 0 1em;
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
         grid-gap: 1em;
     }

     .gallery-wrapper .image-wrapper a {
         padding: 0.5em;
         display: block;
         width: 100%;
         text-decoration: none;
         color: #333;
         box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
         transition: all 200ms ease-in-out;
     }

     .gallery-wrapper .image-wrapper a:hover {
         box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
     }

     .gallery-wrapper .image-wrapper a img {
         width: 100%;
     }

     .gallery-lightboxes .image-lightbox {
         position: fixed;
         top: 0;
         left: 0;
         width: 100vw;
         height: 100vh;
         background: rgba(0, 0, 0, 0.8);
         display: flex;
         align-items: center;
         justify-content: center;
         opacity: 0;
         visibility: hidden;
         transition: opacity 0ms ease-in-out;
     }

     .gallery-lightboxes .image-lightbox:target {
         opacity: 1;
         visibility: visible;
     }

     .gallery-lightboxes .image-lightbox:target .image-lightbox-wrapper {
         opacity: 1;
         transform: scale(1, 1) translateY(0);
     }

     .gallery-lightboxes .image-lightbox .image-lightbox-wrapper {
         transform: scale(0.95, 0.95) translateY(-30px);
         transition: opacity 500ms ease-in-out, transform 500ms ease-in-out;
         opacity: 0;
         margin: 1em auto;
         max-width: 75%;
         padding: 0.5em;
         display: inline-block;
         background: #fff;
         box-shadow: 0 0 5px rgba(0, 0, 0, 0.8);
         position: relative;
     }

     .gallery-lightboxes .image-lightbox .image-lightbox-wrapper .close {
         width: 1.5em;
         height: 1.5em;
         background: #000;
         color: #fff;
         font-weight: bold;
         text-decoration: none;
         border-radius: 50%;
         box-shadow: 0 0 0 2px white inset, 0 0 5px rgba(0, 0, 0, 0.5);
         position: absolute;
         right: -1em;
         top: -1em;
     }

     .gallery-lightboxes .image-lightbox .image-lightbox-wrapper .close:before {
         content: '';
         display: block;
         width: 10px;
         height: 2px;
         background: #fff;
         margin: 0;
         position: absolute;
         top: 50%;
         left: 50%;
         margin: -1px 0 0 -5px;
         transform: rotate(-45deg);
     }

     .gallery-lightboxes .image-lightbox .image-lightbox-wrapper .close:after {
         content: '';
         display: block;
         width: 10px;
         height: 2px;
         background: #fff;
         margin: 0;
         position: absolute;
         top: 50%;
         left: 50%;
         margin: -1px 0 0 -5px;
         transform: rotate(45deg);
     }

     .gallery-lightboxes .image-lightbox .image-lightbox-wrapper .arrow-left {
         position: absolute;
         top: 0;
         right: 50%;
         bottom: 0;
         left: 0;
     }

     .gallery-lightboxes .image-lightbox .image-lightbox-wrapper .arrow-left:before {
         content: '';
         display: inline-block;
         width: 20px;
         height: 20px;
         border: 2px solid #fff;
         border-bottom: 0;
         border-right: 0;
         border-radius: 4px 0 0 0;
         position: absolute;
         top: 50%;
         right: 100%;
         cursor: pointer;
         transform: rotate(-45deg) translateY(-50%);
     }

     .gallery-lightboxes .image-lightbox .image-lightbox-wrapper .arrow-right {
         position: absolute;
         top: 0;
         right: 0;
         bottom: 0;
         left: 50%;
     }

     .gallery-lightboxes .image-lightbox .image-lightbox-wrapper .arrow-right:before {
         content: '';
         display: block;
         width: 20px;
         height: 20px;
         border: 2px solid #fff;
         border-bottom: 0;
         border-left: 0;
         border-radius: 0 4px 0 0;
         position: absolute;
         top: 50%;
         left: 100%;
         cursor: pointer;
         transform: rotate(45deg) translateY(-50%);
     }

     .gallery-lightboxes .image-lightbox .image-lightbox-wrapper img {
         margin: 0 auto;
         max-height: 70vh;
     }
 </style>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.order_plural')}} <small>{{trans('lang.order_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('orders.index') !!}">{{trans('lang.order_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.order')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
  <div class="card">
    <div class="card-header d-print-none">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link" href="{!! route('orders.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.order_table')}}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.order')}}</a>
        </li>
        <div class="ml-auto d-inline-flex">
          <li class="nav-item">
            <a class="nav-link pt-1" id="printOrder" href="#"><i class="fa fa-print"></i> {{trans('lang.print')}}</a>
          </li>
        </div>
      </ul>
    </div>
    <div class="card-body">
      <div class="row">
        @include('orders.show_fields')
      </div>
      @include('product_orders.table')
      <div class="row">
      <table id="tbproduct_orders" class="data-table table "
            class="display nowrap" style="width:100%">
            <thead>
                <tr>
                  <th>{{ __('lang.productname') }}</th>
                  <th>{{ __('lang.productimage') }}</th>
                  <th>{{ __('lang.productprice') }}</th>
                  <th>{{ __('lang.productquantity') }}</th>
                  <!-- <th>{{ __('lang.startingcost') }}</th> -->
                </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
              <tr>
                <td>{{$product->name}}</td>
                <td>
                  <script>
                  
                  </script>
               
                  <img height="100" width="100" src="{{isset($product->media[0]) ? $product->media[0]->url : '/images/image_default.png'}}" onclick="showImageSlider({{$product->id}})">
                </td>
                <td>{{$product->price}}</td>
                <td>{{$product->quantity}}</td>
              </tr>
            @endforeach
            </tbody>
        </table>
      <div class="col-5 offset-7">
        <div class="table-responsive table-light">
    

          <table class="table">
            <tbody><tr>
              <th class="text-right">{{trans('lang.order_subtotal')}}</th>
              <td>{!! getPrice($subtotal) !!}</td>
            </tr>
            <tr>
              <th class="text-right">{{trans('lang.order_tax')}} ({!!$order->tax!!}%) </th>
              <td>{!! getPrice($subtotal * $order->tax/100)!!}</td>
            </tr>
            <tr>
              <th class="text-right">{{trans('lang.order_delivery_fee')}}</th>
              <td>{!! getPrice($order['delivery_fee'])!!}</td>
            </tr>

            <tr>
              <th class="text-right">{{trans('lang.order_total')}}</th>
              <td>{!!getPrice($total)!!}</td>
            </tr>
            </tbody></table>
        </div>
      </div>
      </div>
      <div class="clearfix"></div>
      <div class="row d-print-none">
        <!-- Back Field -->
        <div class="form-group col-12 text-right">
          <a href="{!! route('orders.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.back')}}</a>
        </div>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>

<div class="gallery-lightboxes" id="slidesID">
</div>
@endsection

@push('scripts')
  <script type="text/javascript">
    $("#printOrder").on("click",function () {
      window.print();
    });
  </script>
@endpush

@push('scripts_lib')
@include('layouts.datatables_js')

  <script>
    function checkandopenmodal(){
      $('#importModal').modal('show');
    }

     function showImageSlider(id){
      //  alert(id);
       var firstID=0;
        $.ajax({
                url: `{{url('get-product-images?id=${id}')}}`,
                type: 'GET',
                success: function(res) {
                    console.log(res.data);
                    $('#slidesID').html('');
                    res.data.forEach((element,index) => {
                    firstID=res.data[0].id;
                    // console.log('********************************************');
                    // console.log(index , '  storage' +element.url.split('storage')[1]);
                    // console.log('********************************************');
                    // console.log("sssss :    " ,firstID);
                    //    var s = `<div id="${element.id}"></div>`;
                    //     var temp = document.createElement('div');
                    //     temp.innerHTML = element.url;
                        var src = element.url.split('storage')[1];
                        let nextID=res.data[0].id;
                        let previousID=res.data[res.data.length-1].id;
                        if(index==0){

                            nextID=res.data.length>1?res.data[1].id:element.id;
                            previousID=res.data[res.data.length-1].id;
                            $('#slidesID').append(`
                            <div class="image-lightbox" id="lightbox-image-${element.id}">
                                <div class="image-lightbox-wrapper">
                                    <a href="#" class="close"></a>
                                    <a href="#lightbox-image-${previousID}" class="arrow-left"></a>
                                    <a href="#lightbox-image-${nextID}" class="arrow-right"></a>
                                    <img src="{{url('storage${src}')}}" alt="">
                                    <div class="image-title">${element.name}</div>
                                </div>
                            </div>
                            `);
                        }
                        
                        else{
                            nextID=index===res.data.length-1?res.data[0].id: res.data[index+1].id;
                            previousID=res.data[index-1].id;

                            $('#slidesID').append(`
                            <div class="image-lightbox" id="lightbox-image-${element.id}">
                                <div class="image-lightbox-wrapper">
                                    <a href="#" class="close"></a>
                                    <a href="#lightbox-image-${previousID}" class="arrow-left"></a>
                                    <a href="#lightbox-image-${nextID}" class="arrow-right"></a>
                                    <img src="{{url('storage${src}')}}" alt="">
                                    <div class="image-title">${element.name}</div>
                                </div>
                            </div>
                            `);
                        }
                    });
                    // alert(res);
                    console.log('********************************************');
                    console.log($('#slidesID').html());
                    console.log('********************************************');
                    console.log(firstID);
            
                  window.location.href=`#lightbox-image-${firstID}`;
                }
            });
          
    }
 
  $(document).ready(function() {
 
        var table= $('#tbproduct_orders').DataTable( {
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