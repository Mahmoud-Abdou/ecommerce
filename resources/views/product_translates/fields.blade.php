@if($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

    <div class="form-group row ">
        {!! Form::label('language', trans("lang.languages"),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            <select name="language_id" id="language_id" class='select2 form-control' >
            @foreach ($productTranslateLanguages as $language)
            <option value="{{$language->id}}">{{$language->name}}</option>
            @endforeach
            </select>
        </div>
    </div>


    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('name', trans("lang.product_name"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_name_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.product_name_help") }}
            </div>
        </div>
    </div>

    
    @prepend('scripts')
        <script type="text/javascript">
            var var15671147171873255749ble = '';
            @if(isset($product) && $product->hasMedia('image'))
                var15671147171873255749ble = {
                name: "{!! $product->getFirstMedia('image')->name !!}",
                size: "{!! $product->getFirstMedia('image')->size !!}",
                type: "{!! $product->getFirstMedia('image')->mime_type !!}",
                collection_name: "{!! $product->getFirstMedia('image')->collection_name !!}"
            };
                    @endif
            var dz_var15671147171873255749ble = $(".dropzone.image").dropzone({
                    url: "{!!url('uploads/store')!!}",
                    addRemoveLinks: true,
                    maxFiles: 1,
                    init: function () {
                        @if(isset($product) && $product->hasMedia('image'))
                        dzInit(this, var15671147171873255749ble, '{!! url($product->getFirstMediaUrl('image','thumb')) !!}')
                        @endif
                    },
                    accept: function (file, done) {
                        dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
                    },
                    sending: function (file, xhr, formData) {
                        this.element.children[0].value=dzSending(this, file, formData, '{!! csrf_token() !!}');
                    },
                    maxfilesexceeded: function (file) {
                        dz_var15671147171873255749ble[0].mockFile = '';
                        dzMaxfile(this, file);
                    },
                    complete: function (file) {
                        dzComplete(this, file, var15671147171873255749ble, dz_var15671147171873255749ble[0].mockFile);
                        dz_var15671147171873255749ble[0].mockFile = file;
                    },
                    removedfile: function (file) {
                        dzRemoveFile(
                            file, var15671147171873255749ble, '{!! url("product_translation/remove-media") !!}',
                            'image', '{!! isset($product) ? $product->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                        );
                    }
                });
            dz_var15671147171873255749ble[0].mockFile = var15671147171873255749ble;
            dropzoneFields['image'] = dz_var15671147171873255749ble;
        </script>
@endprepend

<!-- Price Field -->

    <!-- Description Field -->
    <div class="form-group row ">
        {!! Form::label('description', trans("lang.product_description"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('description', null, ['class' => 'form-control','placeholder'=>
             trans("lang.product_description_placeholder")  ]) !!}
            <div class="form-text text-muted">{{ trans("lang.product_description_help") }}</div>
        </div>
    </div>
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
    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.product')}}</button>
    <a href="{!! route('product_translation.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
