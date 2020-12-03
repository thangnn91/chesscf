@extends('layouts._adminlayout')
@section('content')
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">Đổi mật khẩu</h3>
        </div>
    </div>
</div>
<div class="m-content">
    <!--Begin::Section-->
    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--tab">
                <!--begin::Form-->
                   <form id="form_data" class="m-form m-form--state m-form--fit m-form--label-align-right">
                    <div class="m-portlet__body">
                        <div class="m-alert m-alert--icon alert alert-danger m--hide" role="alert" id="m_form_1_msg">
                            <div class="m-alert__icon">
                                <i class="la la-warning"></i>
                            </div>
                            <div class="m-alert__text">
                                Có một sỗ lỗi trong khi nhập liệu. Vui lòng kiểm tra dữ liệu nhập vào !
                            </div>
                            <div class="m-alert__close">
                                <button type="button" class="close" data-close="alert" aria-label="Close"></button>
                            </div>
                        </div>

                        <div class="form-group m-form__group m-form__group-sub row">
                            <label class="col-form-label col-lg-3 col-sm-12">Mật khẩu cũ</label>
                            <div class="col-lg-4 col-md-9 col-sm-12">
                                <input id="oldPass" placeholder="Nhập vào mật khẩu cũ của bạn" autocomplete="off" class="form-control m-input" type="password" />
                            </div>
                        </div>

                        <div class="form-group m-form__group m-form__group-sub row">
                            <label class="col-form-label col-lg-3 col-sm-12">Mật khẩu mới</label>
                            <div class="col-lg-4 col-md-9 col-sm-12">
                                <input id="newPass" placeholder="Nhập vào mật khẩu của bạn" autocomplete="off" class="form-control m-input" type="password" />
                            </div>
                        </div>
                         <div class="form-group m-form__group m-form__group-sub row">
                            <label class="col-form-label col-lg-3 col-sm-12">Nhập lại mật khẩu mới</label>
                            <div class="col-lg-4 col-md-9 col-sm-12">
                                <input id="reNewpass" placeholder="Nhập lại mật khẩu của bạn" autocomplete="off" class="form-control m-input" type="password" />
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions m-form__actions">
                                <div class="row">
                                    <div class="col-lg-7 ml-lg-auto">
                                        <button type="button" onclick="confirm();" class="btn btn-success ajax_disabled" id="btn_update"><i
                                                class="fa fa-check"></i>&nbsp;Cập nhật</button>
                                        <button type="reset" class="btn btn-secondary ajax_disabled" id="btn_cancel" title="Quay lại"><i
                                                class="fa fa-undo"></i>&nbsp;Quay lại</button>
                                    </div>
                                </div>
                            </div>
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
    $(function () {
       
    });
  
    function confirm() {
        toastr.options = {
            "closeButton": true,
            "positionClass": "toast-bottom-left",
        };
        var oldPass = $('#oldPass').val();
        if (!oldPass) {
            toastr.warning('Vui lòng nhập mật khẩu cũ');
            return;
        }

        var newPass = $('#newPass').val();
        if (!newPass) {
            toastr.warning('Vui lòng nhập mật khẩu mới');
            return;
        }
        var reNewPass = $('#reNewpass').val();
        if (!reNewPass) {
            toastr.warning('Vui lòng nhập lại mật khẩu mới');
            return;
        }
        if (newPass !== reNewPass) {
            toastr.warning('Mật khẩu cũ và mới không trùng nhau');
            return;
        }
        var regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
        if(!newPass.match(regex)){
            toastr.warning('Mật khẩu phải dài hơn 8 ký tự, bao gồm chữ hoa, chữ thường và chữ số');
            return;
        }
        $(".ajax_disabled").prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "{{route('confirm_change_pass.admin')}}",
            data: {
                old_password: oldPass,
                password: newPass
            },
            success: function (data) {
                $(".ajax_disabled").prop('disabled', false);
                if (data.ResponseCode > 0) {
                    toastr.success('Đổi mật khẩu thành công');
                    $('#form_data input[type=text]').val('');
                } else {
                    toastr.error(data.Description);
                }
            }
        }).fail(function () {
            $(".ajax_disabled").prop('disabled', false);
            swal("Thất bại!", 'Hệ thống đang bận, vui lòng thử lại sau!', "warning");
        });      
    }
</script>

@endsection