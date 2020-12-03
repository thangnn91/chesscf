@extends('layouts._adminlayout')

@section('content')
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">Thêm sản phẩm</h3>
        </div>
    </div>
</div>
<div class="m-content">
    <!--Begin::Section-->
    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--tab">

                <!--begin::Form-->
                <form id="main_form" role="form" method="POST" action='{{ route("save_product.admin") }}'
                      class="m-form m-form--fit m-form--label-align-right">
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

                        <div class="form-group m-form__group">
                            <label for="product_name">Tên sản phẩm</label>
                            <input type="text" class="form-control m-input m-input--square" name="product_name"
                                   id="product_name" aria-describedby="emailHelp">
                        </div>
                        <div class="form-group m-form__group">
                            <label for="tree_menu">Danh mục</label>
                            <select multiple="multiple" class="form-control m-input m-input--square" id="tree_menu"
                                    name="tree_menu">
                                @foreach ($menus as $item)
                                <option value="{{$item->id}}"
                                        {{$item->parentid ? "data-parent=" . $item->parentid : ""}}
                                    data-icon="{{$item->icon}}">{{$item->text}}</option>
                                @endforeach
                            </select>
                            <input type="hidden" id="selected_mid" name="selected_mid">
                        </div>
                        <div class="form-group m-form__group">
                            <label for="product_amount">Số lượng</label>
                            <input type="text" class="form-control m-input m-input--square" name="product_amount"
                                   id="product_amount">
                        </div>
                        <div class="form-group m-form__group">
                            <label for="price">Giá tiền</label>
                            <input type="text" class="form-control m-input m-input--square" name="price" id="price">
                        </div>
                        <div class="form-group m-form__group">
                            <label for="discount_price">Giá sale</label>
                            <input type="text" class="form-control m-input m-input--square" name="discount_price"
                                   id="discount_price">
                        </div>
                        <div class="form-group m-form__group">
                            <div class="m-checkbox-inline">
                                <label class="m-checkbox">
                                    <input id="is_hot" name="is_hot" type="checkbox"> Sản phẩm hot
                                    <span></span>
                                </label>
                                <label class="m-checkbox">
                                    <input id="is_new" name="is_new" type="checkbox"> Sản phẩm mới
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group form-group-upload m-form__group">
                            {!! csrf_field() !!}
                            <label for="exampleInputPassword1">Ảnh sản phẩm</label>
                            <div class="file-loading">
                                <input id="file-1" type="file" name="file" multiple class="file"
                                       data-overwrite-initial="false" data-min-file-count="2">
                            </div>
                            <input id="images_name" name="images_name" type="hidden">
                        </div>
                        <div class="form-group m-form__group">
                            <label for="intro_txt">Giới thiệu</label>
                            <textarea class="form-control m-input" name="intro_txt" id="intro_txt" rows="3"></textarea>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="desc_txt">Mô tả</label>
                            <textarea class="form-control m-input" name="desc_txt" id="desc_txt" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__foot--fit">
                        <div class="m-form__actions">
                            <button type="submit" class="btn btn-success">Thêm mới</button>
                            <a href="{{route('product.admin')}}" class="btn btn-secondary">Cancel</a>
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
    var array_product_name = [];
    $(function () {
        $('#price, #discount_price').inputmask({
            prefix: "đ ",
            alias: 'currency',
            digits: 2,
            rightAlign: 0,
            clearMaskOnLostFocus: false
        });
        $("#product_amount").inputmask('integer', {min: 1, max: 10000, rightAlign: 0});

        CKEDITOR.replace('desc_txt', {height: 500, filebrowserUploadUrl: '/filemanager/index.html', filebrowserBrowseUrl: "{{ route('ckfinder_browser') }}"});
        $('#tree_menu').treeselect({buttontext: "Chọn danh mục", hiddenid: 'selected_mid'});
        $("#file-1").fileinput({
            theme: 'fa',
            uploadUrl: "{{route('store_image.admin')}}",
            deleteUrl: "{{route('delete_image.admin')}}",
            overwriteInitial: false,
            dropZoneTitle: 'Kéo thả file vào đây &hellip;',
            browseLabel: 'Chọn tệp',
            showClose: false,
            initialPreview: [],
            initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
            initialPreviewFileType: 'image', // image is the default and can be overridden in config below
            initialPreviewConfig: [],
            uploadExtraData: function () {
                return {
                    _token: $("input[name='_token']").val(),
                };
            },
            deleteExtraData: function () {
                return {
                    _token: $("input[name='_token']").val(),
                };
            },
            allowedFileExtensions: ['jpg', 'png', 'gif'],
            overwriteInitial: false,
            maxFileSize: 2000,
            maxFilesNum: 10,
            slugCallback: function (filename) {
                return filename.replace('(', '_').replace(']', '_');
            },
            fileActionSettings: {
                showDrag: false
            }

        }).on('fileuploaded', function (event, data, previewId, index) {
            var response = data.response;
            array_product_name.push(response.initialPreviewConfig[0]);
            $('#images_name').val(JSON.stringify(array_product_name));
        }).on('filedeleted', function (event, key, jqXHR, data) {
            array_product_name = array_product_name.filter(e => e.key !== key); // will return ['A', 'C']
            $('#images_name').val(JSON.stringify(array_product_name));
        });

        jQuery.validator.addMethod('moneyrequire', function (value, element, params) {
            if(!value)
                return false;
            var price = Number(value.replace(/[^0-9.-]+/g,""));
            return price > 0;
        }, "Vui lòng nhập giá tiền");
        jQuery.validator.addMethod('ckrequired', function (value, element, params) {
            var idname = jQuery(element).attr('id');
            var messageLength = jQuery.trim(CKEDITOR.instances[idname].getData());
            return !params || messageLength.length !== 0;
        }, "Vui lòng nhập mô tả sản phẩm");

        $("#main_form").validate({
            ignore: [],
            rules: {
                product_name: {required: true},
                selected_mid: {required: true},
                product_amount: "required",
                price: {
                    moneyrequire: true
                },
                intro_txt: "required",
                desc_txt: {
                    ckrequired: true
                }
            },
            messages: {
                product_name: {required: "Vui lòng nhập tên sản phẩm"},
                selected_mid: "Vui lòng chọn danh mục",
                product_amount: "Vui lòng nhập số lượng sản phẩm",
                intro_txt: "Vui lòng nhập giới thiệu sản phẩm"
            }
        });
    });

</script>
@include('ckfinder::setup')
@endsection
