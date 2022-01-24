@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
  
<!-- Name Field -->
<div class="form-group row ">
  {!! Form::label('code', trans("lang.promoCode_code"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::text('code', null,  ['class' => 'form-control','placeholder'=>  trans("lang.promoCode_code_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.promoCode_code_help") }}
    </div>
  </div>
</div>
<!-- value Field -->
<div class="form-group row ">
  {!! Form::label('value', trans("lang.promoCode_value"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::number('value', null,  ['class' => 'form-control','placeholder'=>  trans("lang.promoCode_value_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.promoCode_value_help") }}
    </div>
  </div>
</div>



@prepend('scripts')
<script type="text/javascript">
    var var15866134771240834480ble = '';
    // var old='';
    @if(isset($promoCode) && $promoCode->hasMedia('image'))
    var15866134771240834480ble = {
        name: "{!! $promoCode->getFirstMedia('image')->name !!}",
        size: "{!! $promoCode->getFirstMedia('image')->size !!}",
        type: "{!! $promoCode->getFirstMedia('image')->mime_type !!}",
        collection_name: "{!! $promoCode->getFirstMedia('image')->collection_name !!}"};
    @endif
    var dz_var15866134771240834480ble = $(".dropzone.image").dropzone({
        url: "{!!url('uploads/store')!!}",
        addRemoveLinks: true,
        // maxFiles: 1,
        init: function () {
        //  old= $("#imagehidden").val(); 

        @if(isset($promoCode) && $promoCode->hasMedia('image'))
            dzInit(this,var15866134771240834480ble,'{!! url($promoCode->getFirstMediaUrl('image','thumb')) !!}')
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
            // var newss= $("#imagehidden").val(); 
            // newss = newss +","+ old
            // $("#imagehidden").val(newss); 
            dzComplete(this, file, var15866134771240834480ble, dz_var15866134771240834480ble[0].mockFile);
            dz_var15866134771240834480ble[0].mockFile = file;
        },
        removedfile: function (file) {
            dzRemoveFile(
                file, var15866134771240834480ble, '{!! url("categories/remove-media") !!}',
                'image', '{!! isset($promoCode) ? $promoCode->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
            );
        }
    });
    dz_var15866134771240834480ble[0].mockFile = var15866134771240834480ble;
    // dz_var15866134771240834480ble= dz_var15866134771240834480ble + ","+$("#imagehidden").val();
    
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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.promoCode')}}</button>
  <a href="{!! route('categories.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
