<!DOCTYPE html>
<html lang="en">
  <head>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <link
      rel="stylesheet"
      href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"
    />

    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
      $(document).ready(function() {
        $("#example").DataTable({
          lengthMenu: [
            [1, 10, 25, 50, 100, 250, 500, 1000, 2000, 5000],
            [1, 10, 25, 50, 100, 250, 500, 1000, 2000, 5000]
          ],
          pageLength: 1,
          processing: true,
          serverSide: true,
          paging: true,
          dom: "<'row'<'col-md-2'l><'col-md-3'i><'col-md-2'B><'col-md-5'p>>",
          buttons: [
            {
              extend: "collection",
              text: "Export",
              buttons: [
                "copy",
                // 'excel',
                // 'csv',
                // 'pdf',
                "print"
              ]
            }
          ],
          ajax: "new-categories",
          columns: [
            { data: "name" },
            { data: "image" },
            { data: "pdatedat" },
            {
              data: function(data) {
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
                `;
              }
            }
          ]
        });
      });
    </script>
  </head>

  <body class="wide hero">
    <table id="example" class="display" style="width:100%">
      <thead>
        <tr>
          <th>{{ trans("lang.category_name") }}</th>
          <th>{{ trans("lang.category_image") }}</th>
          <th>{{ trans("lang.category_updated_at") }}</th>
          <th>{{ trans("lang.actions") }}</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th>{{ trans("lang.category_name") }}</th>
          <th>{{ trans("lang.category_image") }}</th>
          <th>{{ trans("lang.category_updated_at") }}</th>
          <th>{{ trans("lang.actions") }}</th>
        </tr>
      </tfoot>
    </table>
  </body>
</html>
