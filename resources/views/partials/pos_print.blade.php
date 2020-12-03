<html>

<head></head>


<style>
    body.receipt .sheet {
        width: 58mm;
        height: 100mm
    }

    .btn-print {
        margin-right: 15px;
        margin-top: 5px;
        margin-bottom: 5px;
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 30px;
        text-align: center;
        text-decoration: none;
        font-size: 12px;
        cursor: pointer;
        float: right;
    }

    #invoice-POS {
        box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
        padding: 2mm;
        margin: 0 auto;
        width: 58mm;
        background: #FFF;
    }

    #invoice-POS ::selection {
        background: #f31544;
        color: #FFF;
    }

    #invoice-POS ::moz-selection {
        background: #f31544;
        color: #FFF;
    }

    #invoice-POS h1 {
        font-size: 1.5em;
        color: #222;
    }

    #invoice-POS h2 {
        font-size: 1em;
    }

    #invoice-POS h3 {
        font-size: 1.2em;
        font-weight: 300;
        line-height: 2em;
    }

    #invoice-POS p {
        font-size: .87em;
        line-height: 1.2em;
    }

    #invoice-POS #top,
    #invoice-POS #mid,
    #invoice-POS #bot {
        /* Targets all id with 'col-' */
        border-bottom: 1px solid #EEE;
    }

    #invoice-POS #top {
        min-height: 100px;
    }

    #invoice-POS #mid {
        min-height: 80px;
    }

    #invoice-POS #bot {
        min-height: 50px;
    }

    #invoice-POS #top .logo {
        height: 60px;
        width: 60px;
        background: url("{{asset('newtheme/images/logo.png')}}") no-repeat;
        background-size: 60px 60px;
    }

    #invoice-POS .clientlogo {
        float: left;
        height: 60px;
        width: 60px;
        background: url("{{asset('newtheme/images/logo.png')}}") no-repeat;
        background-size: 60px 60px;
        border-radius: 50px;
    }

    #invoice-POS .info {
        display: block;
        margin-left: 0;
    }

    #invoice-POS .title {
        float: right;
    }

    #invoice-POS .title p {
        text-align: right;
    }

    #invoice-POS table {
        width: 100%;
        border-collapse: collapse;
    }

    #invoice-POS table td {
        padding: 2px;
    }

    #invoice-POS .tabletitle {
        font-size: 1em;
        background: #EEE;
    }

    #invoice-POS .service {
        border-bottom: 1px solid #EEE;
    }

    #invoice-POS .item {
        width: 24mm;
    }

    #invoice-POS .itemtext {
        font-size: .9em;
    }

    #invoice-POS #legalcopy {
        margin-top: 5mm;
    }

    @media print {
        .btn-print {
            display: none;
        }

        header {
            display: none;
        }

        html,
        body.receipt {
            border: 1px solid white;
            height: 99%;
            page-break-after: avoid;
            page-break-before: avoid;
        }

        .print+.print {
            page-break-before: always;
        }
    }

    @page {
        size: auto;
        margin: 0mm;
    }
</style>

<body class="receipt">
    <div id="invoice-POS">
        {!! csrf_field() !!}
        <center id="top">
            <div class="logo"></div>
            <div class="info">
                <h2>Chess art coffee</h2>
            </div>
            <!--End Info-->
        </center>
        <!--End InvoiceTop-->

        <div id="mid">
            <div class="info">
                <br>
                <h2>Liên hệ</h2>
                <p>
                    Địa chỉ : 152 Vạn Hạnh, Long Biên, HN</br>
                    Email : chesscoffeeart@gmail.com</br>
                    Phone : 096 321 1591</br>
                </p>
            </div>
        </div>
        <!--End Invoice Mid-->

        <div id="bot">

            <div id="table">
                <table>
                    <thead>
                        <tr class="tabletitle">
                            <td class="item">
                                <h2>Sản phẩm</h2>
                            </td>
                            <td class="Hours">
                                <h2>Số lượng</h2>
                            </td>
                            <td class="Rate">
                                <h2>Thành tiền</h2>
                            </td>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($products as $itm)
                        <tr class="service">
                            <td class="tableitem">
                                <p class="itemtext">{{$itm->name}}</p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext">{{$itm->quantity}}</p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext">{{number_format($itm->total_amount)}}<sup>đ</sup></p>
                            </td>
                        </tr>
                        @endforeach
                        @if($discount)
                        <tr class="tabletitle">
                            <td colspan="2" class="Rate">
                                <h2>Chiết khấu</h2>
                            </td>
                            <td class="payment">
                                <h2>{{$discount}}</h2>
                            </td>
                        </tr>
                        @endif
                        <tr class="tabletitle">
                            <td colspan="2" class="Rate">
                                <h2>Tổng tiền</h2>
                            </td>
                            <td class="payment">
                                <h2>{{number_format($grand_amount)}}<sup>đ</sup></h2>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!--End Table-->

            <div id="legalcopy">
                <p class="legal">
                    <strong>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</strong>
                </p>
            </div>

        </div>
        <!--End InvoiceBot-->
    </div>
    <div>
        <button type="button" onclick="print_invoice({{$order->order_status}});" class="btn-print">Print</button>
    </div>
    <script type="text/javascript">
        function print_invoice(status) {
            if (status == 0) {
                var xmlHttp = new XMLHttpRequest();
                xmlHttp.onreadystatechange = function() {
                    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                        if (JSON.parse(xmlHttp.response).statusCode > 0)
                            window.print();
                    }
                }
                xmlHttp.open("PUT", "{{route('pay_order.admin')}}");
                xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xmlHttp.setRequestHeader('X-CSRF-TOKEN', document.getElementsByName("_token")[0].value);
                var params = 'id={{$order->id}}';
                xmlHttp.send(params);
            } else
                window.print();
        }
    </script>
</body>

</html>