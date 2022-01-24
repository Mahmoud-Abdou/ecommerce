<div class='btn-group btn-group-sm'>

  @can('category_translation.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.category_edit')}}" href="{{ route('category_translation.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

</div>
