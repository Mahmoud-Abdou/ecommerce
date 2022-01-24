@push('css_lib')
@include('layouts.customdatatble_css')
@endpush
<style>
    
    .page-item.disabled .page-link, .page-link:not(:disabled):not(.disabled)  {
        width: auto !important;
    }
    .dataTables_filter {
        height: 60px;
        text-align: right;
    }


</style>
{{-- {!! $dataTable->table(['width' => '100%']) !!} --}}
 <div class="container">
    <div class="table-responsive">
        <table id="example" class="data-table table "
            class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>{{trans('lang.category_name')}}</th>
                    <th>{{trans('lang.category_image')}}</th>
                    <th>{{trans('lang.category_updated_at')}}</th>
                    <th>{{trans('lang.actions')}}</th>
                    
                </tr>
            </thead>
        </table>
    </div>
</div>
@push('scripts_lib')
@include('layouts.datatables_js')

  <script>
    function checkandopenmodal(){
      $('#importModal').modal('show');
    }
 
  $(document).ready(function() {
        var table= $('#example').DataTable( {
        lengthMenu: [
        [ 10, 25, 50, 100, 250, 500, 1000, 2000, 5000],
        [ 10, 25, 50, 100, 250, 500, 1000, 2000, 5000]
        ],
        pageLength: 10,
        processing: true,
        serverSide: true,
        paging: true,
        "ajax": "new-categories?id="+"{{$idofcategeory}}"+"",
            "ordering": false,
            
            "orderClasses": false,
                
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
        "columns": [
            { "data": "name" },
            { "data": "image" },
            { "data": "pdatedat" },
            { "data": function (data) {
                return `  
                <div class='btn-group btn-group-sm'>
                    @can('categories.show')
                        <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('categories.show', '${data.id}') }}" class='btn btn-link'>
                        <i class="fa fa-eye"></i>
                        </a>
                    @endcan
                    @can('categories.edit')
                        <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.category_edit')}}" href="categories/${data.id}/edit" class='btn btn-link'>
                            <i class="fa fa-edit"></i>
                        </a>
                    @endcan
                    @can('categories.destroy')
                        <form method="POST" action="/categories/${data.id}" accept-charset="UTF-8" style="margin-bottom:0"><input name="_method" type="hidden" value="DELETE"><input name="_token" type="hidden" value="IYeQrtlJ2i47jGPHe41Nz3wjFTIul6NxtnuP8fUc">
                            @csrf    <button type="submit" class="btn btn-link text-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
                        </form>
                    @endcan
                        <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.sub')}}" href="categories?id=${data.id}" class='btn btn-link'>
                        <i class="fa fa-paste"></i>
                        </a>
                </div>
                `
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