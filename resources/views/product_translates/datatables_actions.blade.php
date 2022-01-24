<div class='btn-group btn-group-sm'>


  @can('products.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.translate')}}" href="{{ route('product_translation.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

</div>
