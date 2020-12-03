@extends('layouts._adminlayout')
@section('content')
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">Tạo đơn</h3>
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
                            <label class="col-form-label col-lg-2 col-sm-12">Bàn số</label>
                            <div class="col-lg-8 col-md-9 col-sm-12">
                                <input value="{{$order->table}}" id="table_number" name="table_number" placeholder="Nhập số bàn" autocomplete="off" class="form-control m-input" type="text" />
                            </div>
                        </div>
                        <div class="form-group m-form__group m-form__group-sub row">
                            <label class="col-form-label col-lg-2 col-sm-12"></label>
                            <div class="col-lg-10 col-md-12 col-sm-12">
                                <div id="table" class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="tr-head">
                                                <th data-attr-ignore><span class="table-add fa fa-plus-square"></span></th>
                                                <th width="150">Sản phẩm</th>
                                                <th>Giá tiền</th>
                                                <th width="90">Số lượng</th>
                                                <th width="200">Ghi chú</th>
                                                <th>Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($detail as $data)
                                            <tr class="tr_old">
                                                <td><span data-rmid="{{$data->order_id}}|{{$data->product_id}}" class="table-remove fa fa-trash-alt"></span></td>
                                                <td>
                                                    <select disabled class="form-control m-select2 product" name="product">
                                                        <option></option>
                                                        @if($data->product_id == 0)
                                                        @foreach($products as $itm)
                                                        <option data-price="{{$itm->price}}" value="{{$itm->id}}">{{$itm->name}}</option>
                                                        @endforeach
                                                        <option selected data-price="0" value="0">Sản phẩm khác</option>
                                                        @else
                                                        @foreach($products as $itm)
                                                        <option {{selected($itm->id,$data->product_id)}} data-price="{{$itm->discount_price ? $itm->discount_price: $itm->price}}" value="{{$itm->id}}">{{$itm->name}}</option>
                                                        @endforeach
                                                        <option data-price="0" value="0">Sản phẩm khác</option>
                                                        @endif
                                                    </select>
                                                </td>
                                                <td>{{number_format($data->total_amount / $data->quantity, 2)}}</td>
                                                <td><input disabled data-price="{{$data->total_amount / $data->quantity}}" min="1" type="number" value="{{$data->quantity}}" class="form-control m-input input-quantity" placeholder="SL"></td>
                                                <td><textarea disabled class="form-control m-input" rows="2">{{$data->note}}</textarea></td>
                                                <td>{{number_format($data->total_amount)}}</td>
                                            </tr>
                                            @endforeach
                                            <tr class="hidden">
                                                <td><span class="table-remove fa fa-trash-alt"></span></td>
                                                <td>
                                                    <select class="form-control m-select2 clone-product" name="product">
                                                        <option></option>
                                                        @foreach($products as $itm)
                                                        <option data-price="{{$itm->discount_price ? $itm->discount_price: $itm->price}}" value="{{$itm->id}}">{{$itm->name}}</option>
                                                        @endforeach
                                                        <option data-price="0" value="0">Sản phẩm khác</option>
                                                    </select>
                                                </td>
                                                <td></td>
                                                <td><input min="1" type="number" value="1" class="form-control m-input input-quantity" placeholder="SL"></td>
                                                <td><textarea class="form-control m-input" rows="2"></textarea></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group m-form__group-sub row">
                            <label class="col-form-label col-lg-2 col-sm-12">Chiết khấu (%)</label>
                            <div class="col-lg-2 col-md-9 col-sm-12">
                                <input value="{{$order->discount_rate}}" id="discount" name="discount" placeholder="Chiết khấu (%)" min="0" max="70" autocomplete="off" class="form-control m-input" type="number" />
                            </div>
                        </div>
                        <div class="form-group m-form__group m-form__group-sub row">
                            <label class="col-form-label col-lg-2 col-sm-12">Chiết khấu (đ)</label>
                            <div class="col-lg-2 col-md-9 col-sm-12">
                                <input value="{{$order->discount_vnd}}" id="discount_vnd" name="discount_vnd" placeholder="Chiết khấu (đ)" min="0" autocomplete="off" class="form-control m-input" type="text" />
                            </div>
                        </div>
                        <div class="form-group m-form__group m-form__group-sub row">
                            <label class="col-form-label col-lg-2 col-sm-12">Thành tiền</label>
                            <div class="col-lg-2 col-md-9 col-sm-12 middle-div">
                                <b>{{number_format($order->grand_amount)}}</b><sup>đ</sup>
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions m-form__actions">
                                <div class="row">
                                    <div class="col-lg-7 ml-lg-auto">
                                        <button type="button" onclick="updateOrder();" class="btn btn-success ajax_disabled" id="btn_update"><i class="fa fa-check"></i>&nbsp;Cập nhật</button>
                                        <a href="{{ route('order.admin') }}" class="btn btn-secondary ajax_disabled" id="btn_cancel" title="Quay lại"><i class="fa fa-undo"></i>&nbsp;Quay lại</a>
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
        $('#discount_vnd').inputmask({
            prefix: "đ ",
            alias: 'currency',
            digits: 2,
            rightAlign: 0,
            clearMaskOnLostFocus: false
        });
        $(".product").select2({
            placeholder: "Chọn sản phẩm",
            allowClear: true,
            matcher: function(params, data) {
                return matchStart(params, data);
            }
        });
        $(document).on("select2:select", '.product, .clone-product', function(e) {
            var td = $(this).parent('td');
            if ($(this).val() != 0) {
                var price = $(this).find(":selected").data("price");
                var total = td.next().next().find('input').data('price', price).val();
                var grandAmount = price * total;
                td.next().text(formatMoney(price))
                td.next().next().next().next().text(formatMoney(grandAmount));
                var totalOrderAmount = 0;
                $TABLE.find('table tr').not('.tr-head,.hidden').each(function() {
                    var amount = 0;
                    if ($(this).find('td').eq(5).text())
                        amount = parseInt($(this).find('td').eq(5).text().replace(/\D/g, ''));
                    totalOrderAmount += amount;
                });
                $('.middle-div b').html(formatMoney(totalOrderAmount));
                return;
            }
            td.next().next().find('input').data('price', '').val('1');
            td.next().next().next().next().text('');
            td.next().html('<input type="text" class="form-control m-input input-custom-price" placeholder="Giá tiền">');
            $(".input-custom-price").inputmask('Regex', {
                regex: "^[0-9]*$"
            });
        });
        $(document).on("select2:unselecting", '.product, .clone-product', function(e) {
            var td = $(this).parent('td');
            td.next().next().find('input').data('price', '').val('1');
            td.next().next().next().next().text('');
            td.next().html('');
            var totalOrderAmount = 0;
            $TABLE.find('table tr').not('.tr-head,.hidden').each(function() {
                var amount = 0;
                if ($(this).find('td').eq(5).text())
                    amount = parseInt($(this).find('td').eq(5).text().replace(/\D/g, ''));
                totalOrderAmount += amount;
            });
            $('.middle-div b').html(formatMoney(totalOrderAmount));
        });
    });

    $(document).on('change', '.input-quantity', function() {
        var price = $(this).data('price');
        var td = $(this).parent('td');
        if (price > 0) {
            var grandAmount = price * $(this).val();
            td.prev().text(formatMoney(price));
            td.next().next().text(formatMoney(grandAmount));

        } else {
            if (td.prev().find('input').val()) {
                var grandAmount = td.prev().find('input').val() * $(this).val();
                td.next().next().text(formatMoney(grandAmount));
            }
        }
        var totalOrderAmount = 0;
        $TABLE.find('table tr').not('.tr-head,.hidden').each(function() {
            var amount = 0;
            if ($(this).find('td').eq(5).text())
                amount = parseInt($(this).find('td').eq(5).text().replace(/\D/g, ''));
            totalOrderAmount += amount;
        });

        $('.middle-div b').html(formatMoney(totalOrderAmount));
    });

    $(document).on('blur', '.input-custom-price', function() {
        var price = $(this).val();
        var td = $(this).parent('td');
        if (price) {
            var grandAmount = price * td.next().find('input').val();
            td.next().next().next().text(formatMoney(grandAmount));

        } else {
            td.next().next().next().text('');
        }
        var totalOrderAmount = 0;
        $TABLE.find('table tr').not('.tr-head,.hidden').each(function() {
            var amount = 0;
            if ($(this).find('td').eq(5).text())
                amount = parseInt($(this).find('td').eq(5).text().replace(/\D/g, ''));
            totalOrderAmount += amount;
        });

        $('.middle-div b').html(formatMoney(totalOrderAmount));
    });
    $(document).on('blur', '#discount_vnd', function() {
        var totalAmount = 0;
        $TABLE.find('table tr').not('.tr-head,.hidden').each(function() {
            var amount = 0;
            if ($(this).find('td').eq(5).text())
                amount = parseInt($(this).find('td').eq(5).text().replace(/\D/g, ''));
            totalAmount += amount;
        });
        var discountVnd = Number($(this).val().replace(/[^0-9.-]+/g, ""));
        if (totalAmount > 0) {
            totalAmount -= discountVnd;
        }
        var discount = $('#discount').val();
        if (discount)
            totalAmount = (100 - discount) * totalAmount / 100;
        $('.middle-div b').html(formatMoney(totalAmount > 0 ? totalAmount : 0));
    });

    $(document).on('blur', '#discount', function() {
        var totalAmount = 0;
        $TABLE.find('table tr').not('.tr-head,.hidden').each(function() {
            var amount = 0;
            if ($(this).find('td').eq(5).text())
                amount = parseInt($(this).find('td').eq(5).text().replace(/\D/g, ''));
            totalAmount += amount;
        });
        var discountVnd = Number($('#discount_vnd').val().replace(/[^0-9.-]+/g, ""));
        if (discountVnd > 0)
            totalAmount -= discountVnd;
        var discount = $(this).val();
        if (totalAmount > 0) {
            totalAmount = (100 - discount) * totalAmount / 100;
        }

        $('.middle-div b').html(formatMoney(totalAmount > 0 ? totalAmount : 0));
    });

    var $TABLE = $('#table');
    var $BTN = $('#export-btn');
    var $EXPORT = $('#export');

    $('.table-add').on('click', function() {
        var $clone = $TABLE.find('tr.hidden').clone(true).removeClass('hidden table-line');
        $TABLE.find('table').append($clone);
        var select = $TABLE.find('table tr:last').find('td').eq(1).find('select');
        $(select).select2({
            placeholder: "Chọn sản phẩm",
            allowClear: true,
            matcher: function(params, data) {
                return matchStart(params, data);
            }
        });
    });

    $('.table-remove').on('click', function() {
        var $that = $(this);
        if ($that.data('rmid')) {
            swal({
                title: 'Xóa sản phẩm?',
                html: '<label style="float:left">Lý do</label><textarea id="swal-input1" class="form-control m-input"></textarea>',
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        resolve([
                            $('#swal-input1').val()
                        ])
                    })
                },
                onOpen: function() {
                    $('#swal-input1').focus()
                }
            }).then(function(result) {
                if (result.value) {
                    if (!$('#swal-input1').val()) {
                        alert('Vui lòng nhập lý do');
                        return;
                    }
                    $.ajax({
                        type: "DELETE",
                        data: {
                            remove_data: $that.data('rmid'),
                            reason: $('#swal-input1').val()
                        },
                        url: "{{route('remove_order_item.admin')}}",
                        success: function(data) {
                            if (data.statusCode < 0) {
                                swal("Có lỗi xảy ra!", data.message, "error");
                                return;
                            }
                            $('.middle-div b').html(formatMoney(data.grandAmount));
                            $that.parents('tr').detach();
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            swal("Có lỗi xảy ra!", "Xóa sản phẩm thất bại", "error");
                        }
                    });

                }
            }).catch(swal.noop);
            return;
        }
        $that.parents('tr').detach();
    });

    $('.table-up').on('click', function() {
        var $row = $(this).parents('tr');
        if ($row.index() === 1) return; // Don't go above the header
        $row.prev().before($row.get(0));
    });

    $('.table-down').on('click', function() {
        var $row = $(this).parents('tr');
        $row.next().after($row.get(0));
    });
    // A few jQuery helpers for exporting only
    jQuery.fn.pop = [].pop;
    jQuery.fn.shift = [].shift;

    $BTN.click(function() {
        var $rows = $TABLE.find('tr:not(:hidden)');
        var headers = [];
        var data = [];

        // Get the headers (add special header logic here)
        $($rows.shift()).find('th:not(:empty):not([data-attr-ignore])').each(function() {
            headers.push($(this).text().toLowerCase());
        });

        // Turn all existing rows into a loopable array
        $rows.each(function() {
            var $td = $(this).find('td');
            var h = {};

            // Use the headers from earlier to name our hash keys
            headers.forEach(function(header, i) {
                h[header] = $td.eq(i).text(); // will adapt for inputs if text is empty
            });

            data.push(h);
        });

        // Output the result
        $EXPORT.text(JSON.stringify(data));
    });

    function updateOrder() {
        var tableNumber = $('#table_number').val();
        if (!tableNumber) {
            swal("Kiểm tra lại thông tin!", "Vui lòng nhập số bàn", "error");
            return;
        }

        var arrayProduct = [];
        $TABLE.find('table tr').not('.tr-head,.hidden,.tr_old').each(function() {
            var product, quantity, note;
            var $that = $(this);
            product = $that.find('td').eq(1).find('.m-select2').val();
            quantity = $that.find('td').eq(3).find('input').val();
            note = $that.find('td').eq(4).find('textarea').val();
            if (product && quantity > 0) {
                var orderItem = {
                    productId: product,
                    quantity: quantity,
                    note: note
                };
                arrayProduct.push(orderItem);
            }
        });
        var discountPrice = Number($('#discount_vnd').val().replace(/[^0-9.-]+/g, ""));
        $.ajax({
            type: "POST",
            data: {
                id: "{{$order->id}}",
                table: tableNumber,
                product_data: arrayProduct,
                discount_rate: $('#discount').val(),
                discount_vnd: discountPrice
            },
            url: "{{route('save_edit.admin')}}",
            success: function(data) {
                if (data.statusCode < 0) {
                    swal("Có lỗi xảy ra!", data.message, "error");
                    return;
                }
                swal("Thành công!", data.message, "success");
                //add disable
                $TABLE.find('table tr').not('.tr-head,.hidden,.tr_old').each(function() {
                    var $that = $(this);
                    $that.find('td').find('select,input, textarea').prop('disabled', true);
                });
                $('.middle-div b').html(formatMoney(data.grandAmount));
            },
            error: function(xhr, ajaxOptions, thrownError) {
                swal("Có lỗi xảy ra!", "Tạo đơn hàng thất bại", "error");
            }
        });
    }

    function matchStart(params, data) {
        if (!data.text)
            return false;
        params.term = params.term || '';
        var alias = change_alias(data.text);
        var matches = alias.match(/\b(\w)/g);
        if (matches.join('').indexOf(params.term.toLowerCase()) == 0 || alias.indexOf(params.term.toLowerCase()) > -1) {
            return data;
        }
        return false;
    }
</script>
<style>
    .table-editable .fa {
        font-size: 20px;
    }

    .table-remove {
        color: #700;
        cursor: pointer;
    }

    .table-remove:hover {
        color: #f00;
    }

    .table-up,
    .table-down {
        color: #007;
        cursor: pointer;
    }

    .table-up:hover,
    .table-down:hover {
        color: #00f;
    }

    .table-add {
        color: #070;
        cursor: pointer;
        top: 8px;
        right: 0;
    }

    .table-add:hover {
        color: #0b0;
    }

    .table td {
        vertical-align: top;
    }

    tr.hidden {
        display: none;
    }

    #table select.form-control,
    #table input.form-control,
    #table textarea.form-control {
        width: inherit;
        ;
    }
</style>
@endsection