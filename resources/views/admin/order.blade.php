@extends('layouts._adminlayout')

@section('content')
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">Đơn hàng</h3>
        </div>
    </div>
</div>

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <a href='{{ route("create_order.admin") }}' class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Thêm mới</a>
                </div>
                &nbsp;&nbsp;
                <div class="m-portlet__head-title">
                    <a href='javascript:;' onclick="collectIncome();" class="btn btn-success"><i class="fa fa-edit mr-2"></i>Tổng hợp doanh thu</a>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">

            <form method="GET" action='{{ route("order.admin") }}' class="m-form m-form--fit m--margin-bottom-20">
                <div class="row m--margin-bottom-20">
                    <div class="col-lg-9 m--margin-bottom-10-tablet-and-mobile">
                        <label>Ngày chi:</label>
                        <div class="input-daterange input-group" id="m_datepicker">
                            <input value="{{isset($data_back) && isset($data_back['start']) ? $data_back['start'] : ''}}" autocomplete="false" type="text" class="form-control m-input" id="start" name="start" placeholder="From" data-col-index="5">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                            </div>
                            <input value="{{isset($data_back) && isset($data_back['end']) ? $data_back['end'] : ''}}" autocomplete="false" type="text" class="form-control m-input" id="end" name="end" placeholder="To" data-col-index="5">
                        </div>
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
                        <a type="button" href={{ route("order.admin") }} class="btn btn-secondary m-btn m-btn--icon" id="m_reset">
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
                        <th>Bàn số</th>
                        <th width="120">Thời gian</th>
                        <th>Mã đơn</th>
                        <th>Chiết khấu</th>
                        <th width="120">Sản phẩm</th>
                        <th>Trạng thái</th>
                        <th>Tổng tiền</th>
                        <th width="150">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $itm)
                    <tr id="{{$itm->id}}">
                        <td>{{$itm->table}}</td>
                        <td>{{ date('d-m-Y H:i:s', strtotime($itm->created_at))}}</td>

                        <td>{{$itm->code}}</td>
                        <td>
                            @if($itm->discount_rate)
                            <span class="m-badge m-badge--warning m-badge--wide">{{$itm->discount_rate}}%</span>
                            @endif
                            @if($itm->discount_vnd)
                            <span class="m-badge m-badge--info m-badge--wide">{{number_format($itm->discount_vnd)}}<sup>đ</sup></span>
                            @endif
                        </td>
                        <td><a onclick="showDetail({{$itm->id}});" href="javascript:;">Chi tiết</a></td>
                        <td>
                            @if($itm->order_status == \Config::get('constants.order_init'))
                            Khởi tạo
                            @elseif($itm->order_status == \Config::get('constants.order_done'))
                            Hoàn thành
                            @endif
                        </td>
                        <td>{{number_format($itm->grand_amount)}}<sup>đ</sup></td>
                        <td>
                            <a href="{{route('edit_order.admin').'/'.$itm->id}}" class="btn btn-success btn-sm" title="Sửa/Xem chi tiết">
                                <i class="fa fa-eye"></i>
                            </a>
                            <button class="btn btn-warning btn-sm" title="Thanh toán" onclick="payProduct('{{$itm->id}}', '{{$itm->code}}')">
                                <i class="fab fa-cc-amazon-pay"></i>
                            </button>
                            <button class="btn btn-info btn-sm" title="In hóa đơn" onclick="printPreview('{{$itm->id}}', '{{$itm->code}}')">
                                <i class="fa fa-print"></i>
                            </button>
                            @if(\Auth::guard('admin')->user()->admin || isset($allow_remove_order))
                            <button class="btn btn-danger btn-sm" title="Xóa đơn hàng" onclick="deleteOrder('{{$itm->id}}', '{{$itm->code}}')">
                                <i class="fa fa-trash"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="Chi tiết đơn hàng" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="main_form" class="m-form m-form--fit m-form--label-align-right form-ajax">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Chi tiết đơn hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered m-table m-table--border-brand m-table--head-bg-brand">
                        <thead>
                            <tr>
                                <th width="15">#</th>
                                <th>Sản phẩm</th>
                                <th width="100">Số lượng</th>
                                <th>Ghi chú</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="m_modal_print" tabindex="-1" role="dialog" aria-labelledby="Chi tiết đơn hàng" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-print" role="document">
        <div class="modal-content">
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
            pickerPosition: "bottom-left",
            defaultDate: new Date()
        });
        $("#m_table_2").DataTable({
            responsive: !0,
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
            filter: true,
            searching: true,
            language: {
                lengthMenu: "Display _MENU_"
            },
            order: !1
            // order: [
            //     [2, "desc"]
            // ]
        });
    });

    function showDetail(id) {
        $.ajax({
            type: "GET",
            data: {
                id: id
            },
            url: "{{route('order_detail.admin')}}",
            success: function(data) {
                $('#m_modal_1 table tbody').html(data);
                $('#m_modal_1').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                swal("Có lỗi xảy ra!", "Tạo đơn hàng thất bại", "error");
            }
        });
    }

    function printPreview(id) {
        var link = "{{route('order_detail.admin')}}?id=" + id + "&printer=1"
        var iframe = document.createElement('iframe');
        iframe.frameBorder = 0;
        iframe.width = "300px";
        iframe.height = "450px";
        iframe.id = "randomid";
        iframe.setAttribute("src", link);
        $('#m_modal_print .modal-content').html(iframe);
        $('#m_modal_print').modal('show');
    }

    function payProduct(id, code) {
        var _id = Number(id);
        swal({
                title: "Thanh toán đơn hàng",
                text: "Bạn có chắc chắn muốn thanh toán đơn hàng [" + code + "] không?",
                type: "warning",
                showCancelButton: true,
            })
            .then((res) => {
                if (res.value) {
                    $.ajax({
                        type: "PUT",
                        url: "{{route('pay_order.admin')}}",
                        data: {
                            id: _id
                        },
                        success: function(data) {
                            if (data.statusCode > 0) {
                                swal("Thành công!", "Thanh toán thành công.", "success");
                                //$('#' + _id).remove();
                            } else {
                                swal("Thất bại!", data.message, "error");
                            }
                        }
                    });
                }
            });
    }

    function deleteOrder(id, code) {
        var _id = Number(id);
        swal({
                title: "Xóa đơn hàng",
                text: "Bạn có chắc chắn muốn xóa đơn hàng [" + code + "] không?",
                type: "warning",
                showCancelButton: true,
            })
            .then((res) => {
                if (res.value) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{route('delete_order.admin')}}",
                        data: {
                            id: _id
                        },
                        success: function(data) {
                            if (data.statusCode > 0) {
                                swal("Thành công!", "Xóa đơn hàng thành công.", "success");
                                $('#' + _id).remove();
                            } else {
                                swal("Thất bại!", data.message, "error");
                            }
                        }
                    });
                }
            });
    }

    function collectIncome() {
        var start = $('#start').val();
        var end = $('#end').val();

        if (!start && !end) {
            $('body').loading({
                onStart: function(loading) {
                    loading.overlay.slideDown(400);
                }
            });
            $.ajax({
                type: "POST",
                url: "{{route('collect_income.admin')}}",
                success: function(data) {
                    if (data.statusCode > 0) {
                        swal("Thành công!", data.message, "success");
                        //$('#' + _id).remove();
                    } else {
                        swal("Thất bại!", data.message, "error");
                    }
                    $('body').loading('stop');
                },
                error: function(request, status, error) {
                    swal("Thất bại!", "Hệ thống đang bận, vui lòng thử lại sau.", "error");
                    $('body').loading('stop');
                }
            });
        } else {
            $('body').loading({
                onStart: function(loading) {
                    loading.overlay.slideDown(400);
                }
            });
            $.ajax({
                type: "POST",
                url: "{{route('collect_income.admin')}}",
                data: {
                    from_date: start,
                    to_date: end
                },
                success: function(data) {
                    if (data.statusCode > 0) {
                        swal("Thành công!", data.message, "success");
                        //$('#' + _id).remove();
                    } else {
                        swal("Thất bại!", data.message, "error");
                    }
                    $('body').loading('stop');
                },
                error: function(request, status, error) {
                    swal("Thất bại!", "Hệ thống đang bận, vui lòng thử lại sau.", "error");
                    $('body').loading('stop');
                }
            });
        }
    }
</script>
<style>
    .dataTables_scroll::-webkit-scrollbar {
        width: 6px;
        background-color: #F5F5F5;
    }

    .modal-print {
        max-width: 300px;
    }
</style>
@endsection