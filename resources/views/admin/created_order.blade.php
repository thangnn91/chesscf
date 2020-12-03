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
                                <input id="table_number" name="table_number" placeholder="Nhập số bàn" autocomplete="off" class="form-control m-input" type="text" />
                            </div>
                        </div>
                        <div class="form-group m-form__group m-form__group-sub row">
                            <label class="col-form-label col-lg-2 col-sm-12"></label>
                            <div class="col-lg-10 col-md-12 col-sm-12">
                                <div id="table" class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="tr-head">
                                                <th width="20" data-attr-ignore><span class="table-add fa fa-plus-square"></span></th>
                                                <th width="150">Sản phẩm</th>
                                                <th>Giá tiền</th>
                                                <th width="90">Số lượng</th>
                                                <th width="200">Ghi chú</th>
                                                <th>Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><span class="table-remove fa fa-trash-alt"></span></td>
                                                <td>
                                                    <select class="form-control m-select2 product" name="product">
                                                        <option></option>
                                                        @foreach($products as $itm)
                                                        <option data-price="{{$itm->discount_price ? $itm->discount_price: $itm->price}}" value="{{$itm->id}}">{{$itm->name}}</option>
                                                        @endforeach
                                                        <option data-price="0" value="0">Sản phẩm khác</option>
                                                    </select>
                                                </td>
                                                <td></td>
                                                <td><input min="1" type="number" value="1" class="form-control m-input input-quantity" placeholder="SL"></td>
                                                <td><textarea class="form-control m-input" id="exampleTextarea" rows="3"></textarea></td>
                                                <td></td>
                                            </tr>
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
                                                <td><textarea class="form-control m-input" id="exampleTextarea" rows="2"></textarea></td>
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
                                <input id="discount" name="discount" placeholder="Chiết khấu (%)" min="0" max="70" autocomplete="off" class="form-control m-input" type="number" />
                            </div>
                        </div>
                        <div class="form-group m-form__group m-form__group-sub row">
                            <label class="col-form-label col-lg-2 col-sm-12">Chiết khấu (đ)</label>
                            <div class="col-lg-2 col-md-9 col-sm-12">
                                <input id="discount_vnd" name="discount_vnd" placeholder="Chiết khấu (đ)" min="0" autocomplete="off" class="form-control m-input" type="text" />
                            </div>
                        </div>
                        <div class="form-group m-form__group m-form__group-sub row">
                            <label class="col-form-label col-lg-2 col-sm-12">Thành tiền</label>
                            <div class="col-lg-2 col-md-9 col-sm-12 middle-div">
                                <b>0</b><sup>đ</sup>
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions m-form__actions">
                                <div class="row">
                                    <div class="col-lg-7 ml-lg-auto">
                                        <button type="button" onclick="createOrder();" class="btn btn-success ajax_disabled" id="btn_update"><i class="fa fa-check"></i>&nbsp;Tạo đơn</button>
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
            $(".input-custom-price").inputmask({
                prefix: "đ ",
                alias: 'currency',
                digits: 2,
                rightAlign: 0,
                clearMaskOnLostFocus: false
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
                var grandAmount = Number(td.prev().find('input').val().replace(/[^0-9.-]+/g, "")) * $(this).val();
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
            price = Number(price.replace(/[^0-9.-]+/g, ""));
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
        var discount = $(this).val();
        if (totalAmount > 0) {
            totalAmount = (100 - discount) * totalAmount / 100;
        }
        var discountVnd = Number($('#discount_vnd').val().replace(/[^0-9.-]+/g, ""));
        if (discountVnd > 0)
            totalAmount -= discountVnd;
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
        $(this).parents('tr').detach();
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

    function createOrder() {
        var tableNumber = $('#table_number').val();
        if (!tableNumber) {
            swal("Kiểm tra lại thông tin!", "Vui lòng nhập số bàn", "error");
            return;
        }

        var arrayProduct = [];
        var error = '';
        $TABLE.find('table tr').not('.tr-head,.hidden').each(function() {
            var product, quantity, note;
            var $that = $(this);
            product = $that.find('td').eq(1).find('.m-select2').val();
            quantity = $that.find('td').eq(3).find('input').val();
            note = $that.find('td').eq(4).find('textarea').val();
            if (product && quantity > 0) {
                var price = 0;
                if (product == 0) {
                    price = Number($that.find('td').eq(2).find('input').val().replace(/[^0-9.-]+/g, ""));
                    if (!price) {
                        error = "Vui lòng nhập giá cho sản phẩm khác";
                        return false;
                    }
                    if (!note) {
                        error = "Vui lòng nhập ghi chú cho sản phẩm khác";
                        return false;
                    }
                }
                var orderItem = {
                    productId: product,
                    quantity: quantity,
                    price: price,
                    note: note
                };
                arrayProduct.push(orderItem);
            }
        });
        if (error) {
            swal("Kiểm tra lại thông tin!", error, "error");
            return;
        }
        if (!arrayProduct.length) {
            swal("Kiểm tra lại thông tin!", "Vui lòng chọn ít nhất 1 sản phẩm", "error");
            return;
        }
        console.log(arrayProduct);
        var discountPrice = Number($('#discount_vnd').val().replace(/[^0-9.-]+/g, ""));
        $.ajax({
            type: "POST",
            data: {
                table: tableNumber,
                product_data: arrayProduct,
                discount_rate: $('#discount').val(),
                discount_vnd: discountPrice
            },
            url: "{{route('save_order.admin')}}",
            success: function(data) {
                if (data.statusCode < 0) {
                    swal("Có lỗi xảy ra!", data.message, "error");
                    return;
                }
                swal("Thành công!", data.message, "success");
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