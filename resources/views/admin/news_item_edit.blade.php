@extends('layouts._adminlayout')

@section('content')
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">Thêm tin bài</h3>
        </div>
    </div>
</div>
<div class="m-content">
    <!--Begin::Section-->
    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--tab">

                <!--begin::Form-->
                <form id="main_form" role="form" method="POST" action='{{ route("store_news.admin") }}' class="m-form m-form--fit m-form--label-align-right">
                    <div class="m-portlet__body">
                        @if(count($errors))
                        <div class="form-group m-form__group m--margin-top-10">
                            <div class="alert m-alert m-alert--default" role="alert">
                                <ul>
                                    @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                        <input type="hidden" name="id" value="{{$news_detail->id}}">
                        <div class="form-group m-form__group">
                            <label for="title">Tiêu đề</label>
                            <input value="{{$news_detail->title}}" autocomplete="off" type="text" class="form-control m-input m-input--square" name="title" id="title" aria-describedby="emailHelp">
                        </div>
                        <div class="form-group m-form__group">
                            <div class="m-checkbox-inline">
                                <label class="m-checkbox">
                                    <input id="is_hot" name="is_hot" type="checkbox" {{$news_detail->is_hot ? "checked" : ""}}> Tin hot
                                    <span></span>
                                </label>
                                <label class="m-checkbox">
                                    <input id="is_new" name="is_new" type="checkbox" {{$news_detail->is_new ? "checked" : ""}}> Tin mới
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group form-group-upload m-form__group">
                            {!! csrf_field() !!}
                            <label for="exampleInputPassword1">Banner</label>
                            <div class="file-loading">
                                <input id="file-1" type="file" name="file" multiple class="file" data-overwrite-initial="false" data-min-file-count="2">
                            </div>
                            <input id="images_name" name="images_name" type="hidden">
                        </div>
                        <div class="form-group m-form__group">
                            <label for="intro_txt">Giới thiệu</label>
                            <textarea class="form-control m-input" name="intro_txt" id="intro_txt" rows="3">{{$news_detail->summary}}</textarea>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="desc_txt">Nội dung</label>
                            <textarea class="form-control m-input" name="desc_txt" id="desc_txt" rows="3">{{$news_detail->content}}</textarea>
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__foot--fit">
                        <div class="m-form__actions">
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                            <a href="{{route('news.admin')}}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>

                <!--end::Form-->
            </div>
        </div>
    </div>
    <!--End::Section-->
</div>
<script type="text/javascript">
    var array_item_name = [];
    var initial_preview = [];
    var initial_preview_config = [];
    if ("{{$init_preview_config}}") {
        var elem = document.createElement('textarea');
        elem.innerHTML = "{{$init_preview_config}}";
        var decoded = elem.value;
        initial_preview_config = JSON.parse(decoded);
        elem.innerHTML = "{{$init_preview}}";
        var decoded = elem.value;
        initial_preview = JSON.parse(decoded);
        array_item_name = initial_preview_config;
        $('#images_name').val(JSON.stringify(initial_preview_config));
    }
    $(function() {
        CKEDITOR.replace('desc_txt', {
            height: 500,
            filebrowserUploadUrl: '/filemanager/index.html',
            filebrowserBrowseUrl: "{{ route('ckfinder_browser') }}"
        });

        $("#file-1").fileinput({
            theme: 'fa',
            uploadUrl: "{{route('store_image.admin')}}",
            deleteUrl: "{{route('delete_image.admin')}}",
            showClose: false,
            overwriteInitial: false,
            dropZoneTitle: 'Kéo thả file vào đây &hellip;',
            browseLabel: 'Chọn tệp',
            initialPreview: initial_preview,
            initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
            initialPreviewFileType: 'image', // image is the default and can be overridden in config below
            initialPreviewConfig: initial_preview_config,
            uploadExtraData: function() {
                return {
                    _token: $("input[name='_token']").val(),
                };
            },
            deleteExtraData: function() {
                return {
                    _token: $("input[name='_token']").val(),
                };
            },
            allowedFileExtensions: ['jpg', 'png', 'gif'],
            overwriteInitial: false,
            maxFileSize: 2000,
            maxFilesNum: 10,
            slugCallback: function(filename) {
                return filename.replace('(', '_').replace(']', '_');
            },
            fileActionSettings: {
                showDrag: false
            }

        }).on('fileuploaded', function(event, data, previewId, index) {
            var response = data.response;
            array_item_name.push(response.initialPreviewConfig[0]);
            $('#images_name').val(JSON.stringify(array_item_name));
        }).on('filedeleted', function(event, key, jqXHR, data) {
            array_item_name = array_item_name.filter(e => e.key !== key); // will return ['A', 'C']
            $('#images_name').val(JSON.stringify(array_item_name));
        });


        jQuery.validator.addMethod('ckrequired', function(value, element, params) {
            var idname = jQuery(element).attr('id');
            var messageLength = jQuery.trim(CKEDITOR.instances[idname].getData());
            return !params || messageLength.length !== 0;
        }, "Vui lòng nhập nội dung tin");

        $("#main_form").validate({
            ignore: [],
            rules: {
                title: {
                    required: true
                },
                intro_txt: "required",
                desc_txt: {
                    ckrequired: true
                }
            },
            messages: {
                product_name: {
                    title: "Vui lòng nhập tiêu đề"
                },
                intro_txt: "Vui lòng nhập giới thiệu"
            }
        });
    });
</script>
@include('ckfinder::setup')
@endsection