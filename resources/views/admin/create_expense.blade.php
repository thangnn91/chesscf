@extends('layouts._adminlayout')
@section('content')
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">Thêm khoản chi</h3>
        </div>
    </div>
</div>
<div class="m-content">
    <!--Begin::Section-->
    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--tab">
                <!--begin::Form-->
                <form id="form_data" method="POST" action='{{ route("save_expense.admin") }}' class="m-form m-form--state m-form--fit m-form--label-align-right form-ajax">
                    {!! csrf_field() !!}
                    <div class="m-portlet__body">
                        <div class="m-alert m-alert--icon alert alert-danger m--hide" role="alert" id="m_form_1_msg">
                            <div class="m-alert__icon">
                                <i class="la la-warning"></i>
                            </div>
                            <div class="m-alert__text">
                                <div>Có một sỗ lỗi trong khi nhập liệu. Vui lòng kiểm tra dữ liệu nhập vào !</div>
                                <div class="alert_detail"></div>
                            </div>
                            <div class="m-alert__close">
                                <button type="button" class="close" data-close="alert" aria-label="Close"></button>
                            </div>
                        </div>

                        <div class="form-group m-form__group m-form__group-sub row">
                            <label class="col-form-label col-lg-3 col-sm-12">Số tiền</label>
                            <div class="col-lg-4 col-md-9 col-sm-12">
                                <input id="amount" name="amount" placeholder="Số tiền chi" autocomplete="off" class="form-control m-input" type="text" />
                            </div>
                        </div>

                        <div class="form-group m-form__group m-form__group-sub row">
                            <label class="col-form-label col-lg-3 col-sm-12">Mục đích chi</label>
                            <div class="col-lg-4 col-md-9 col-sm-12">
                                <select class="form-control m-select2" id="expense_purpose" name="expense_purpose">
                                    <option></option>
                                    @foreach($config as $itm)
                                    <option value="{{$itm->id}}">{{$itm->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group m-form__group-sub row">
                            <label class="col-form-label col-lg-3 col-sm-12">Ghi chú</label>
                            <div class="col-lg-4 col-md-9 col-sm-12">
                                <textarea class="form-control m-input m-input--air" id="note" name="note" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group m-form__group m-form__group-sub row">
                            <label class="col-form-label col-lg-3 col-sm-12">Ngày chi</label>
                            <div class="col-lg-4 col-md-9 col-sm-12">
                                <div class="input-group date">
                                    <input type="text" class="form-control m-input" readonly placeholder="Select date & time" name="expense_date" id="expense_date" />
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group m-form__group-sub row">
                            <label class="col-form-label col-lg-3 col-sm-12">Người chi</label>
                            <div class="col-lg-4 col-md-9 col-sm-12">
                                <select class="form-control m-select2" id="expense_user" name="expense_user">
                                    <option></option>
                                    @foreach($user as $itm)
                                    <option value="{{$itm->id}}">{{$itm->username}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group m-form__group-sub row">
                            <label class="col-form-label col-lg-3 col-sm-12"></label>
                            <div class="col-lg-4 col-md-9 col-sm-12">
                                <label class="m-checkbox">
                                    <input name="is_refund" id="is_refund" class="form-control m-input" type="checkbox">
                                    Đã hoàn tiền
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions m-form__actions">
                                <div class="row">
                                    <div class="col-lg-7 ml-lg-auto">
                                        <button type="submit" class="btn btn-success ajax_disabled" id="btn_update"><i class="fa fa-check"></i>&nbsp;Cập nhật</button>
                                        <button type="reset" class="btn btn-secondary ajax_disabled" id="btn_cancel" title="Quay lại"><i class="fa fa-undo"></i>&nbsp;Quay lại</button>
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
    $(function() {
        $('#amount').inputmask({
            prefix: "đ ",
            alias: 'currency',
            digits: 2,
            rightAlign: 0,
            clearMaskOnLostFocus: false
        });
        $("#expense_purpose").select2({
            placeholder: "Chọn mục đích chi"
        });
        $("#expense_user").select2({
            placeholder: "Chọn người chi"
        });
        $("#expense_date").datetimepicker({
            format: "dd/mm/yyyy",
            todayHighlight: !0,
            autoclose: !0,
            startView: 2,
            minView: 2,
            forceParse: 0,
            pickerPosition: "bottom-left"
        });
    });
</script>

@endsection