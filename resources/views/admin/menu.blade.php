@extends('layouts._adminlayout')

@section('content')
<style>
    .list-group-item > div { margin-bottom: 5px; }
    .btn-group-xs > .btn, .btn-xs {
        padding: 1px 5px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }
</style>
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">Quản lý danh mục</h3>
        </div>
    </div>
</div>
<div class="m-content">
    <!--Begin::Section-->
    <div class="row">
        <div class="col-md-6">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Thêm/ Sửa danh mục
                            </h3>
                        </div>
                    </div>
                </div>

                <!--begin::Form-->
                <form id="frmEdit" class="m-form m-form--fit m-form--label-align-right">
                    <input type="hidden" name="mnu_icon" id="mnu_icon" value="">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group">
                            <label for="example-text-input">Tên danh mục</label>
                            <div class="input-group">
                                <input type="text" id="mnu_text" name="mnu_text" class="form-control m-input" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button id="mnu_iconpicker" class="btn btn-secondary" data-iconset="fontawesome5" role="iconpicker"></button>
                                </div>
                            </div>

                        </div>    
                        <div class="form-group m-form__group">
                            <label for="mnu_href">Đường dẫn</label>
                            <input id="mnu_href" name="mnu_href" type="text" class="form-control m-input m-input--square">
                        </div>
                        <div class="form-group m-form__group">
                            <label for="exampleInputPassword1">Tooltip</label>
                            <input id="mnu_title" name="mnu_title" type="text" class="form-control m-input m-input--square">
                        </div>
                        <div class="form-group m-form__group">
                            <label for="exampleInputPassword1">Thứ tự</label>
                            <input id="mnu_index" name="mnu_index" type="text" class="form-control m-input m-input--square">
                        </div>
                        <div class="form-group m-form__group">
                            <div class="m-checkbox-inline">
                                <label class="m-checkbox">
                                    <input id="mnu_showhome" name="mnu_showhome" type="checkbox"> Hiển thị trang chủ
                                    <span></span>
                                </label>                              
                            </div>
                        </div>
                        <input id="mnu_id" name="mnu_id" type="hidden" class="form-control m-input m-input--square">
                    </div>
                    <div class="m-portlet__foot m-portlet__foot--fit">
                        <div class="m-form__actions text-center">
                            <button type="button" id="btnUpdate" class="btn btn-primary" disabled><i class="fa fa-refresh"></i> Update</button>
                            <button type="button" id="btnAdd" class="btn btn-success"><i class="fa fa-plus mr-2"></i> Add</button>

                        </div>
                    </div>
                </form>

                <!--end::Form-->
            </div>
        </div>
        <div class="col-md-6">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Menu
                            </h3>
                        </div>
                    </div>
                </div>

                <!--begin::Form-->
                <form class="m-form m-form--fit m-form--label-align-right">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group">
                            <ul id="myList" class="sortableLists list-group">

                            </ul>
                        </div>                     
                    </div>
                    <div class="m-portlet__foot m-portlet__foot--fit">
                        <div class="m-form__actions text-center">
                            <button type="button" id="btnOut" class="btn btn-accent"><i class="la la-save mr-2"></i>Save</button>
                        </div>
                    </div>
                </form>

                <!--end::Form-->
            </div>
        </div>
    </div>
    <!--End::Section-->
</div>
<script>
    console.log('{{$treeJson}}');
    var txt = document.createElement("textarea");
    txt.innerHTML = '{{$treeJson}}';
    console.log(txt.value);
    jQuery(document).ready(function () {
        var iconPickerOpt = {cols: 5, footer: false};
        var options = {
            hintCss: {'border': '1px dashed #13981D'},
            placeholderCss: {'background-color': 'gray'},
            ignoreClass: 'btn',
            opener: {
                active: true,
                as: 'html',
                close: '<i class="fa fa-minus"></i>',
                open: '<i class="fa fa-plus"></i>',
                openerCss: {'margin-right': '10px'},
                openerClass: 'btn btn-success btn-xs'
            }
        };
        menuEditor('myList',
                {data: txt.value,
                    listOptions: options,
                    iconPicker: iconPickerOpt,
                    labelEdit: '<i class="fa fa-edit"></i>',
                    labelRemove: 'X',
                    callback: 'callback'
                });
    });
    function callback(str) {
        if (!str)
            return;
        $.ajax({
            type: 'POST',
            url: '{{ route("save_menu.admin") }}',
            data: {menu_data: str},
            success: function (data) {
                $('#btn_update').removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                if (data.ResponseCode >= 0) {
                    swal("Thành công!", data.Description, "success");
                    setTimeout(function () {
                        window.location.href = '{{ route("menu.admin") }}';
                    }, 4000);
                } else if (data.ResponseCode == -600) {
                    $("#m_form_1_msg").removeClass("m--hide").show(), mUtil.scrollTop();
                    return;
                } else {
                    swal("Thất bại!", data.Description, "error");
                    return;
                }
            }
        });
    }
</script>

@endsection