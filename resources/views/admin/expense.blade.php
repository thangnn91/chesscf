@extends('layouts._adminlayout')

@section('content')
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">Danh sách khoản chi</h3>
        </div>
    </div>
</div>

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <a href="{{ route('create_expense.admin') }}" class="btn btn-primary"><i class="fa fa-plus mr-2"></i> Thêm mới</a>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <form method="GET" action='{{ route("expense.admin") }}' class="m-form m-form--fit m--margin-bottom-20">
                <div class="row m--margin-bottom-20">
                    <div class="col-lg-6 m--margin-bottom-10-tablet-and-mobile">
                        <label>Ngày chi:</label>
                        <div class="input-daterange input-group" id="m_datepicker">
                            <input value="{{isset($data_back) && isset($data_back['start']) ? $data_back['start'] : ''}}" autocomplete="false" type="text" class="form-control m-input" id="start" name="start" placeholder="From" data-col-index="5">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                            </div>
                            <input value="{{isset($data_back) && isset($data_back['end']) ? $data_back['end'] : ''}}" autocomplete="false" type="text" class="form-control m-input" id="end" name="end" placeholder="To" data-col-index="5">
                        </div>
                    </div>
                    <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                        <label>Người chi:</label>
                        <select class="form-control m-select2" id="expense_user" name="expense_user">
                            <option></option>
                            @foreach($user as $itm)
                            <option {{isset($data_back) && isset($data_back['expense_user']) ? selected($itm->id,$data_back['expense_user']) : ''}} value="{{$itm->id}}">{{$itm->username}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                        <label>Mục đích:</label>
                        <select class="form-control m-select2" id="expense_purpose" name="expense_purpose">
                            <option></option>
                            @foreach($config as $itm)
                            <option {{isset($data_back) && isset($data_back['expense_purpose']) ? selected($itm->id,$data_back['expense_purpose']) : ''}} value="{{$itm->id}}">{{$itm->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="m-separator m-separator--md m-separator--dashed"></div>
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-brand m-btn m-btn--icon" id="m_search">
                            <span>
                                <i class="la la-search"></i>
                                <span>Tìm kiếm</span>
                            </span>
                        </button>
                        &nbsp;&nbsp;
                        <a type="button" href={{ route("expense.admin") }} class="btn btn-secondary m-btn m-btn--icon" id="m_reset">
                            <span>
                                <i class="la la-close"></i>
                                <span>Bỏ chọn</span>
                            </span>
                        </a>
                    </div>
                </div>
            </form>
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_2">
                <thead>
                    <tr>
                        <th>Mục đích</th>
                        <th>Số tiền</th>
                        <th>Mô tả</th>
                        <th>Ngày chi</th>
                        <th>Người chi</th>
                        <th>Trạng thái</th>
                        <th width="120">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expense as $itm)
                    <tr id="{{$itm->id}}">
                        <td>{{$itm->name}}</td>
                        <td>
                            {{number_format($itm->amount, 2)}}
                        </td>
                        <td>
                            {{$itm->note}}
                        </td>
                        <td>
                            {{$itm->date_string}}
                        </td>
                        <td>
                            {{$itm->username}}
                        </td>
                        <td>
                            @if($itm->is_refund)
                            <div class="m-demo-icon">
                                <div class="m-demo-icon__preview">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                                <div class="m-demo-icon__class">
                                    Đã thanh toán </div>
                            </div>
                            @else
                            <div class="m-demo-icon">
                                <div class="m-demo-icon__preview">
                                    <i class="fa fa-times-circle"></i>
                                </div>
                                <div class="m-demo-icon__class">
                                    Chưa thanh toán </div>
                            </div>
                            @endif
                        </td>
                        <td>
                            <a href="{{route('edit_expense.admin').'/'.$itm->id}}" class="btn btn-success btn-sm mr-2 edit_user" title="Sửa/Xem chi tiết">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(\Auth::guard('admin')->user()->is_super_admin)
                            <button class="btn btn-warning btn-sm" title="Thanh toán" onclick="refundItem('{{$itm->id}}', '{{$itm->name}}')">
                                <i class="fas fa-undo-alt"></i>
                            </button>
                            &nbsp;
                            <button class="btn btn-danger btn-sm" title="Xóa" onclick="deleteItem('{{$itm->id}}', '{{$itm->name}}')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Tổng tiền</th>
                        <th></th>
                        <th colspan="5"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $("#start,#end").datetimepicker({
            format: "dd/mm/yyyy",
            todayHighlight: !0,
            autoclose: !0,
            startView: 2,
            minView: 2,
            forceParse: 0,
            pickerPosition: "bottom-left"
        });
        $("#expense_purpose").select2({
            placeholder: "Chọn mục đích chi",
            width: '100%',
            allowClear: true,
        });
        $("#expense_user").select2({
            placeholder: "Chọn người chi",
            allowClear: true,
            width: '100%'
        });
        $("#m_table_2").DataTable({
            responsive: !0,
            //dom: "<'row'<'col-sm-12'tr>>\n\t\t\t<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
            language: {
                lengthMenu: "Display _MENU_"
            },
            order: !1,
            // order: [
            //     [3, "desc"]
            // ],
            footerCallback: function(t, e, n, a, r) {
                var o = this.api(),
                    l = function(t) {
                        return "string" == typeof t ? 1 * t.replace(/[\$,]/g, "") : "number" == typeof t ? t : 0
                    },
                    u = o.column(1).data().reduce(function(t, e) {
                        return l(t) + l(e)
                    }, 0),
                    i = o.column(1).data().reduce(function(t, e) {
                        return l(t) + l(e)
                    }, 0);
                $(o.column(1).footer()).html(mUtil.numberString(i.toFixed(2)) + "<sup>đ</sup>")
            }
        });
    });

    function deleteItem(_id, _name) {
        _id = Number(_id);
        swal({
                title: "Xóa khoản chi",
                text: "Bạn có chắc chắn muốn xóa khoản chi [" + _name + "] không?",
                type: "warning",
                showCancelButton: true,
            })
            .then((res) => {
                if (res.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{route('delete_expense.admin')}}",
                        data: {
                            id: _id
                        },
                        success: function(data) {
                            if (data === "1") {
                                swal("Thành công!", "Xóa khoản chi thành công.", "success");
                                $('#' + _id).remove();
                            } else {
                                swal("Thất bại!", "Hệ thống đang bận, vui lòng thử lại sau.", "error");
                            }
                        }
                    });
                }
            });
    }

    function refundItem(_id, _name) {
        _id = Number(_id);
        swal({
                title: "Thanh toán khoản chi",
                text: "Bạn có chắc chắn muốn thanh toán khoản chi [" + _name + "] không?",
                type: "warning",
                showCancelButton: true,
            })
            .then((res) => {
                if (res.value) {
                    $.ajax({
                        type: "PUT",
                        url: "{{route('refund_expense.admin')}}",
                        data: {
                            id: _id
                        },
                        success: function(data) {
                            if (data === "1") {
                                swal("Thành công!", "Thanh toán thành công.", "success");
                                //$('#' + _id).remove();
                            } else {
                                swal("Thất bại!", "Hệ thống đang bận, vui lòng thử lại sau.", "error");
                            }
                        }
                    });
                }
            });
    }
</script>
<style>
    .dataTables_scroll::-webkit-scrollbar {
        width: 6px;
        background-color: #F5F5F5;
    }
</style>
@endsection