@push('css_lib')
@include('layouts.datatables_css')
@endpush
<style>
    
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
{{-- {!! $dataTable->table(['width' => '100%']) !!} --}}
 <div class="container">
    <div class="table-responsive">
        <table id="product_table" class="data-table table "
            class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>{{trans('lang.product_name')}}</th>
                        <th>{{trans('lang.product_image')}}</th>
                        <th>{{trans('lang.product_price')}}</th>
                        <th>{{trans('lang.product_discount_price')}}</th>
                        <th>{{trans('lang.product_capacity')}}</th>
                        <th>{{trans('lang.product_featured')}}</th>
                        <th>{{trans('lang.product_market_id')}}</th>
                        <th>{{trans('lang.product_category_id')}}</th>
                        <th>{{trans('lang.product_updated_at')}}</th>
                        <th>{{trans('lang.actions')}}</th>
                    </tr>
                </thead>
        </table> 
    </div>
</div>
<div class="gallery-lightboxes" id="slidesID">
</div>
@push('scripts_lib')
@include('layouts.datatables_js')
{{-- {!! $dataTable->scripts() !!} --}}
  <script>
    function checkandopenmodal(){
      $('#importModal').modal('show');
    }
 
  $(document).ready(function() {
        var table= $('#product_table').DataTable( {
        lengthMenu: [
        [ 10, 25, 50, 100, 250, 500, 1000, 2000, 5000],
        [ 10, 25, 50, 100, 250, 500, 1000, 2000, 5000]
        ],
        pageLength: 10,
        processing: true,
        serverSide: true,
        paging: true,
        
                responsive: true,
                "dom":  "<'row'<'col-md-4'l><'col-md-4'B><'col-md-4'f><'col-md-12't><'col-md-4'i><'col-md-4'><'col-md-4'p>>",
    
            // "scrollY": 300,
            // "scrollX": true,
            "paging": true,
             charset: 'UTF-8',
            "buttons": [
                        {
                            "extend": "csv",
                            "text": "{{ __('lang.excel') }}",
                            "filename": "{{ __('lang.products') }}",
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
                            "filename": "{{ __('lang.products') }}",
                            "className": "btn btn-green",
                            "charset": "utf-8",
                            "bom": "true",
                            init: function(api, node, config) {
                                $(node).removeClass("btn-default");
                            },
                           
                        }
                    ],
        "ajax": "all-products",
        "columns": [
            {'data' : 'name'},
       
            { "data": function (data) {
                var s = '<div id="myDiv"></div>';
                var temp = document.createElement('div');
                temp.innerHTML = data.image;
                 var src = temp.firstChild.src.split('storage')[1];

               // var s = data.image;
              // var htmlObject = $(s); // jquery call
               //var tt= document.createElement(data.image);
                console.log(temp.firstChild);

                return ` 
                <img class="round" style="width:80px" alt="image_default" src="{{url('storage${src}')}}" onclick="showImageSlider('${data.id}')">
               `
                },
            },

            {'data' : 'price'},
            {'data' : 'discount_price'},
            {'data' : 'capacity'},
            {'data' : 'featured'},
            {'data' : 'market.name'},
            {'data' : 'category_name'},
            {'data' : 'updated_at'},
            { "data": function (data) {
                return ` 
                <div class='btn-group btn-group-sm'>
                    @can('products.show')
                    <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('products.show', '${data.id}') }}" class='btn btn-link'>
                        <i class="fa fa-eye"></i>
                    </a>
                    @endcan
                    @can('products.edit')
                        <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.product_edit')}}" href="products/${data.id}/edit" class='btn btn-link'>
                        <i class="fa fa-edit"></i>
                        </a>
                        @endcan
                        @can('products.destroy')
                        <form method="POST" action="/products/${data.id}" accept-charset="UTF-8" style="margin-bottom:0"><input name="_method" type="hidden" value="DELETE"><input name="_token" type="hidden" value="IYeQrtlJ2i47jGPHe41Nz3wjFTIul6NxtnuP8fUc">
                            @csrf    <button type="submit" class="btn btn-link text-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
                            </form>
                        @endcan
                </div>`
                },
            },
            // { "data": "description" },
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

    function showImageSlider(id){
       var firstID=0;
        $.ajax({
                url: "get-product-images?id="+id,
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
 </script>
@endpush