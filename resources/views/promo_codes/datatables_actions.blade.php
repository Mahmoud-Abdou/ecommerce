<div class='btn-group btn-group-sm'>
  @can('promo_codes.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('promoCodes.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('promo_codes.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.promoCode_edit')}}" href="{{ route('promoCodes.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('promo_codes.destroy')
  {!! Form::open(['route' => ['promoCodes.destroy', $id], 'method' => 'delete']) !!}
    {!! Form::button('<i class="fa fa-trash"></i>', [
    'type' => 'submit',
    'class' => 'btn btn-link text-danger',
    'onclick' => "return confirm('Are you sure?')"
    ]) !!}
  {!! Form::close() !!}
  @endcan
</div>
