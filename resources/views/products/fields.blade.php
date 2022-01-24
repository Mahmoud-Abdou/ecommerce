@if($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
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

    <!-- Image Field -->
    <div class="form-group row">
        {!! Form::label('image', trans("lang.product_image"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            <div style="width: 100%" class="dropzone image" id="image" data-field="image">
                <input type="hidden" name="image">
            </div>
            {{-- <a href="#loadMediaModal" data-dropzone="image" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color','primary')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
            <div class="form-text text-muted w-50">
                {{ trans("lang.product_image_help") }}
            </div> --}}
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
                    maxFiles: 4,
                    init: function () {
                        @if(isset($product) && $product->hasMedia('image'))
                        dzInit(this, var15671147171873255749ble, '{!! url($product->getFirstMediaUrl('image','thumb')) !!}')
                        @endif
                    },
                    accept: function (file, done) {
                        dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
                    },
                    sending: function (file, xhr, formData) {
                        var oldname=this.element.children[0].value;
                        var name= dzSending(this, file, formData, '{!! csrf_token() !!}');
                        if(this.element.children[0].value == "" ){
                            this.element.children[0].value= name;
                        }else{
                            this.element.children[0].value= oldname + ","+name;
                        }
                    },
                    maxfilesexceeded: function (file) {
                        dz_var15671147171873255749ble[0].mockFile = '';
                        dzMaxfile(this, file);
                    },
                    complete: function (file) {
                        dzComplete(this, file, var15671147171873255749ble, dz_var15671147171873255749ble[0].mockFile);
                        console.log(file);
                        dz_var15671147171873255749ble[0].mockFile = file;
                    },
                    removedfile: function (file) {
                        dzRemoveFile(
                            file, var15671147171873255749ble, '{!! url("products/remove-media") !!}',
                            'image', '{!! isset($product) ? $product->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                        );
                    }
                });
            dz_var15671147171873255749ble[0].mockFile = var15671147171873255749ble;
            dropzoneFields['image'] = dz_var15671147171873255749ble;
        </script>
    @endprepend

<!-- Price Field -->
    <div class="form-group row ">
        {!! Form::label('price', trans("lang.product_price"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('price', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_price_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.product_price_help") }}
            </div>
        </div>
    </div>

    <!-- Discount Price Field -->
    <div class="form-group row ">
        {!! Form::label('discount_price', trans("lang.product_discount_price"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('discount_price', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_discount_price_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.product_discount_price_help") }}
            </div>
        </div>
    </div>

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
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

    <!-- Capacity Field -->
    <div class="form-group row ">
        {!! Form::label('capacity', trans("lang.product_capacity"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('capacity', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_capacity_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.product_capacity_help") }}
            </div>
        </div>
    </div>

    <!-- unit Field -->
    <div class="form-group row ">
        {!! Form::label('unit', trans("lang.product_unit"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('unit', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_unit_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.product_unit_help") }}
            </div>
        </div>
    </div>

    <!-- package_items_count Field -->
    <div class="form-group row ">
        {!! Form::label('package_items_count', trans("lang.product_package_items_count"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('package_items_count', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_package_items_count_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.product_package_items_count_help") }}
            </div>
        </div>
    </div>
    

    <!-- Market Id Field -->
    <div class="form-group row ">
        {!! Form::label('market_id', trans("lang.product_market_id"),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('market_id', $market, null, ['class' => 'select2 form-control']) !!}
            <div class="form-text text-muted">{{ trans("lang.product_market_id_help") }}</div>
        </div>
    </div>

    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('category_id', trans("lang.product_category_id"),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            <select name="category_id" id="category_id" class='select2 form-control' onChange="getsubcategory(this.value)">
                <option selected disabled>{{ trans("lang.choose_category") }}</option>
                @foreach ($category as $key => $value)
                    <option @isset($product) @if($product->category_id == $key) selected @endif @endisset value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
            {{-- {!! Form::select('category_id', $category, null, ['class' => 'select2 form-control', "onChange" => "getsubcategory(this.value)"]) !!} --}}
            <div class="form-text text-muted">{{ trans("lang.product_category_id_help") }}</div>
        </div>
    </div>
    <!-- Category Id Field -->
    <div class="form-group row ">
        {!! Form::label('sub_category_id', trans("lang.product_sub_category_id"),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            <select id='sub_category_id' name="sub_category" class=' form-control' >
                
            </select>
            <div class="form-text text-muted">{{ trans("lang.product_category_id_help") }}</div>
        </div>
    </div>

    <!-- 'Boolean Featured Field' -->
    <div class="form-group row ">
        {!! Form::label('featured', trans("lang.product_featured"),['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('featured', 0) !!}
                {!! Form::checkbox('featured', 1, null) !!}
            </label>
        </div>
    </div>

    <!-- 'Boolean deliverable Field' -->
    <div class="form-group row ">
        {!! Form::label('deliverable', trans("lang.product_deliverable"),['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('deliverable', 0) !!}
                {!! Form::checkbox('deliverable', 1, null) !!}
            </label>
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
    <a href="{!! route('products.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>

<script>
    function getsubcategory(id){
        $("#sub_category_id").html("");
         $.ajax({
            // type:"put",
            //  data: {
            //     _token: "{{ csrf_token() }}",
            //     id:id
            // },
            url: "{{ url('get-sub-category') }}?id="+id,
            success: function (html) {
                var x=`<option selected disabled>{{ trans("lang.none") }}</option>`;
                html.forEach(element => {
                    x+=`<option value="${element.id}">${element.name}</option>`;
                });
                $("#sub_category_id").html(x);
                // $("#sub_category_id").val($("#sub_category_id option:first").val());
            }, 
            error: function (error) {
                alert("error");
                console.log(error);
            }
        });
    }
</script>

{{-- 
<div id="mediaModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header align-items-stretch">
                <h5 class="modal-title flex-grow-1">{!! trans('lang.media_title') !!}</h5>
                <div style="width: 250px;" id="selectCollection" class="ml-auto mr-3">
                    <select name="collection_name" id="collection_name" class="form-control select2">
                    </select>
                </div>

                <div id="resizeItems" class="ml-auto btn-group">
                    <button type="button" data-size="2" class="btn btn-outline-secondary"><i class="fa fa-th"></i></button>
                    <button type="button" data-size="3" class="btn btn-primary"><i class="fa fa-th-large"></i></button>
                    <button type="button" data-size="4" class="btn btn-outline-secondary"><i class="fa fa-square"></i></button>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row medias-items">
                    <div class="card loader">
                        <div class="overlay">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span>{!! trans('lang.media_hint') !!}</span>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{!! trans('lang.close') !!}</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script type="text/template" data-template="mediaitem2">
        <div class="col-sm-3">
            <div class="card clickble">
                <img class="card-img"
                     src="${src}"
                     data-name="${file_name}"
                     data-type="${mime_type}"
                     data-size="${size}"
                     data-uuid="${uuid}"
                     alt="Card image">
                <div class="card-footer">
                    <small>${name} (${formated_size})</small><br> <small class="text-muted">${updated_at}</small>
                </div>
            </div>
        </div>
    </script>

    <script type="text/javascript">
        var triggerButton;
        var dropzoneIndex = '';

        /**
        * add selected media to dropzone
        */
        function initDropzone(index = '') {
            var dz = dropzoneFields[index][0];
            $('#mediaModal2 .card.clickble').on('click', function () {
                var img = $(this).find('.card-img');
                console.log(dz.mockFile);
                if (dz.mockFile !== '') {
                    dz.dropzone.removeFile(dz.mockFile);
                }
                var mockFile = {name: img.data('name'), size: img.data('size'), type: img.data('type'), upload: {uuid: img.data('uuid')}};
                dz.mockFile = mockFile;
                var oldname=dz.dropzone.element.children[0].value;
                var name=  img.data('uuid')
                if(oldname == "" ){
                    dz.dropzone.element.children[0].value= name;
                }else{
                    dz.dropzone.element.children[0].value= oldname + ","+name;
                }
                // dz.dropzone.element.children[0].value = img.data('uuid');
                dz.dropzone.options.addedfile.call(dz.dropzone, mockFile);
                dz.dropzone.options.thumbnail.call(dz.dropzone, mockFile, img.attr('src'));
                dz.dropzone.previewsContainer.lastChild.classList.add('dz-success');
                dz.dropzone.previewsContainer.lastChild.classList.add('dz-complete');
                $('#mediaModal2').modal('hide');
            });
        }

        function initSelectCollection(){
            var select = $('#selectCollection #collection_name');
            $.ajax({
                url: "{!! url('uploads/collectionsNames') !!}",
                type: 'GET',
                success: function (data, status) {
                    const collections = Object.keys(data.data).map(i => data.data[i])
                    collections.forEach(function (coll) {
                        if(coll.value === dropzoneIndex){
                            select.append('<option selected value="'+coll.value+'">'+coll.title+'</option>');
                            select.val(coll.value).trigger('change');
                        }else{
                            select.append('<option value="'+coll.value+'">'+coll.title+'</option>');
                        }
                    })
                }
            });
        }

        /**
         * resize buttons
         * */
        $('#mediaModal2 #resizeItems button').on('click',function () {
            $('#mediaModal2 #resizeItems button').attr('class','btn btn-outline-secondary');
            $(this).removeClass('btn-outline-secondary').addClass('btn-primary')
            var size = $(this).data('size');
            var mediaItems = $('#mediaModal2 .medias-items')
                .find('div[class^="col-sm"]')
                .removeAttr( "class" )
                .addClass('col-sm-'+size);
        });

        /**
         * load media with ajax
         */
        function loadMedia(url) {

            var itemTpl = $('script[data-template="mediaitem"]').text().split(/\$\{(.+?)\}/g);
            var items = [];
            var mediaItems = $('#mediaModal2 .medias-items');
            
            console.log("Ssssss");
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data, status) {
                    if(status === 'success'){
                        data = JSON.parse(data);
                        console.log(data);
                        data.forEach(function (item) {
                            items.push({
                                src: item.thumb,
                                file_name: item.file_name,
                                mime_type: item.mime_type,
                                size: item.size,
                                formated_size: item.formated_size,
                                uuid: item.custom_properties.uuid,
                                name: item.name,
                                updated_at: item.updated_at,
                            });
                        });
                    }else{
                        mediaItems.find('.card.loader').html('Error please refresh page or use (Ctrl+F5)');
                    }
                },
                error : function(data, status, error){
                    mediaItems.find('.card.loader').html('Error please refresh page or use (Ctrl+F5)');
                },
                complete: function (data, status) {
                    if (status === 'success') {
                        mediaItems.html(items.map(function (item) {
                            return itemTpl.map(render(item)).join('');
                        }));
                        mediaItems.find('.card.loader').remove();
                        initDropzone(dropzoneIndex);
                    }else{
                        mediaItems.find('.card.loader').html('Error please refresh page or use (Ctrl+F5)');
                    }
                }
            });
        }
        $('#mediaModal2').on('show.bs.modal',function (event) {
            triggerButton = $(event.relatedTarget) // Button that triggered the modal
            dropzoneIndex = triggerButton.data('dropzone'); // Optionct info from data-* attributes
            loadMedia("{!! url('uploads/all') !!}/"+dropzoneIndex);
            initSelectCollection();
        });
        $('#selectCollection #collection_name').on('change',function () {
            loadMedia("{!! url('uploads/all') !!}/"+$(this).val());
        })
    </script>
@endpush --}}