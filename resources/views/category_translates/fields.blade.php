@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
  
<div class="form-group row ">
  {!! Form::label('language', trans("lang.languages"),['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <select name="language_id" id="language_id" class='select2 form-control' >
      @foreach ($categoryTranslateLanguages as $language)
    <option value="{{$language->id}}">{{$language->name}}</option>
      @endforeach
    </select>
      <!-- {{-- {!! Form::select('category_id', [null =>"none"] +$categoryTranslate, null, ['class' => 'select2 form-control']) !!} --}}
      <div class="form-text text-muted">{{ trans("lang.product_category_id_help") }}</div> -->
  </div>
</div>

<!-- Name Field -->
<div class="form-group row ">
  {!! Form::label('name', trans("lang.category_name"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.category_name_help") }}
    </div>
  </div>
</div>

<!-- Description Field -->
<div class="form-group row ">
  {!! Form::label('description', trans("lang.category_description"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::text('description', null, ['class' => 'form-control','placeholder'=>
     trans("lang.category_description_placeholder")  ]) !!}
    <div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div>
  </div>
</div>
</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
      
@prepend('scripts')
<script type="text/javascript">
    var var15866134771240834480ble = '';
    @if(isset($category) && $category->hasMedia('image'))
    var15866134771240834480ble = {
        name: "{!! $category->getFirstMedia('image')->name !!}",
        size: "{!! $category->getFirstMedia('image')->size !!}",
        type: "{!! $category->getFirstMedia('image')->mime_type !!}",
        collection_name: "{!! $category->getFirstMedia('image')->collection_name !!}"};
    @endif
    var dz_var15866134771240834480ble = $(".dropzone.image").dropzone({
        url: "{!!url('uploads/store')!!}",
        addRemoveLinks: true,
        maxFiles: 1,
        init: function () {
        @if(isset($category) && $category->hasMedia('image'))
            dzInit(this,var15866134771240834480ble,'{!! url($category->getFirstMediaUrl('image','thumb')) !!}')
        @endif
        },
        accept: function(file, done) {
            dzAccept(file,done,this.element,"{!!config('medialibrary.icons_folder')!!}");
        },
        sending: function (file, xhr, formData) {
            this.element.children[0].value=dzSending(this,file,formData,'{!! csrf_token() !!}');
        },
        maxfilesexceeded: function (file) {
            dz_var15866134771240834480ble[0].mockFile = '';
            dzMaxfile(this,file);
        },
        complete: function (file) {
            dzComplete(this, file, var15866134771240834480ble, dz_var15866134771240834480ble[0].mockFile);
            dz_var15866134771240834480ble[0].mockFile = file;
        },
        removedfile: function (file) {
            dzRemoveFile(
                file, var15866134771240834480ble, '{!! url("category_translation/remove-media") !!}',
                'image', '{!! isset($category) ? $category->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
            );
        }
    });
    dz_var15866134771240834480ble[0].mockFile = var15866134771240834480ble;
    dropzoneFields['image'] = dz_var15866134771240834480ble;
</script>
@endprepend
</div>
@if($customFields)
<div class="clearfix"></div>
<div class="col-12 custom-field-container">
  <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
  {!! $customFields !!}
</div>
@endif
<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.category')}}</button>
  <a href="{!! route('category_translation.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
