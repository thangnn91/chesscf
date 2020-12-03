@extends('layouts._adminlayout')

@section('content')
<style>
    .highcharts-figure,
    .highcharts-data-table table {
        display: block;
        width: 100%;
        margin: 1em auto;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #EBEBEB;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }
</style>
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Trang chủ</h3>
        </div>
    </div>
</div>
<div class="m-content">
    <!--Begin::Section-->
    <div class="row">
        <div class="col-xl-6">

            <!--begin:: Widgets/Top Products-->
            <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                <div class="m-portlet__head">
                    <div class="col-md-8 col-sm-4">
                        <div class="m-portlet__head-caption m-portlet__head-caption_custom">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Biểu đồ khoản chi theo cá nhân
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="m-portlet__head-tools">
                            <select id="expense_month" style="width: 100%" class="form-control m-bootstrap-select m_selectpicker">
                                <option value="01">Tháng 1</option>
                                <option value="02">Tháng 2</option>
                                <option value="03">Tháng 3</option>
                                <option value="04">Tháng 4</option>
                                <option value="05">Tháng 5</option>
                                <option value="06">Tháng 6</option>
                                <option value="07">Tháng 7</option>
                                <option value="08">Tháng 8</option>
                                <option value="09">Tháng 9</option>
                                <option value="10">Tháng 10</option>
                                <option value="11">Tháng 11</option>
                                <option value="12">Tháng 12</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="m-portlet__body">

                    <!--begin::Widget5-->
                    <div class="m-widget4">
                        <div>
                            <div>
                                <figure class="highcharts-figure">
                                    <div id="container"></div>
                                </figure>
                            </div>
                        </div>
                    </div>

                    <!--end::Widget 5-->
                </div>
            </div>

            <!--end:: Widgets/Top Products-->
        </div>
        <div class="col-xl-6">
            <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                <div class="m-portlet__head">
                    <div class="col-md-8 col-sm-4">
                        <div class="m-portlet__head-caption m-portlet__head-caption_custom">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Biểu đồ khoản chi theo danh mục
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="m-portlet__head-tools">
                            <select id="expense_month2" style="width: 100%" class="form-control m-bootstrap-select m_selectpicker">
                                <option value="01">Tháng 1</option>
                                <option value="02">Tháng 2</option>
                                <option value="03">Tháng 3</option>
                                <option value="04">Tháng 4</option>
                                <option value="05">Tháng 5</option>
                                <option value="06">Tháng 6</option>
                                <option value="07">Tháng 7</option>
                                <option value="08">Tháng 8</option>
                                <option value="09">Tháng 9</option>
                                <option value="10">Tháng 10</option>
                                <option value="11">Tháng 11</option>
                                <option value="12">Tháng 12</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <!--begin::Widget5-->
                    <div class="m-widget4">
                        <div>
                            <div>
                                <figure class="highcharts-figure">
                                    <div id="container2"></div>
                                </figure>
                            </div>
                        </div>
                    </div>
                    <!--end::Widget 5-->
                </div>
            </div>
        </div>
    </div>
    <!--End::Section-->
</div>
<script type="text/javascript">
    var chart1, chart2;
    $(function() {
        var currentMonth = (new Date().getMonth() + 1);
        currentMonth = currentMonth < 10 ? ("0" + currentMonth) : currentMonth.toString();
        $('#expense_month, #expense_month2').val(currentMonth);
        $(".m_selectpicker").selectpicker();
        var groupData = JSON.parse('{!! json_encode($grouped, TRUE) !!}');
        var titleChart = 'Khoản chi theo cá nhân trong tháng ' + currentMonth;
        groupData = Object.values(convertIntObj(groupData));

        var groupData2 = JSON.parse('{!! json_encode($grouped2, TRUE) !!}');
        var titleChart2 = 'Khoản chi theo danh mục trong tháng ' + currentMonth;
        groupData2 = Object.values(convertIntObj(groupData2));

        chart1 = Highcharts.chart('container', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                events: {
                    load: function(event) {
                        var total = 0;
                        for (var i = 0, len = this.series[0].yData.length; i < len; i++) {
                            total += this.series[0].yData[i];
                        }
                        var text = this.renderer.text(
                            'Tổng chi: ' + formatMoney(total) + 'đ',
                            this.plotLeft,
                            this.plotTop - 20
                        ).attr({
                            zIndex: 5
                        }).add();
                    }
                }
            },
            title: {
                text: titleChart
            },
            tooltip: {
                pointFormatter: function() {
                    return formatMoney(this.y) + '<sup>đ</sup>';
                }
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            return '<b>' + this.key + '</b>' + ': ' + formatMoney(this.y) + '<sup>đ</sup>';
                        }
                    }
                }
            },
            series: [{
                name: 'Brands',
                colorByPoint: true,
                data: groupData
            }],
            credits: {
                enabled: false
            }

        });

        chart2 = Highcharts.chart('container2', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                events: {
                    load: function(event) {
                        var total = 0;
                        for (var i = 0, len = this.series[0].yData.length; i < len; i++) {
                            total += this.series[0].yData[i];
                        }
                        var text = this.renderer.text(
                            'Tổng chi: ' + formatMoney(total) + 'đ',
                            this.plotLeft,
                            this.plotTop - 20
                        ).attr({
                            zIndex: 5
                        }).add();
                    }
                }
            },
            title: {
                text: titleChart2
            },
            tooltip: {
                pointFormatter: function() {
                    return formatMoney(this.y) + '<sup>đ</sup>';
                }
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            return '<b>' + this.key + '</b>' + ': ' + formatMoney(this.y) + '<sup>đ</sup>';
                        }
                    }
                }
            },
            series: [{
                name: 'Brands',
                colorByPoint: true,
                data: groupData2
            }],
            credits: {
                enabled: false
            }
        });
    });

    $('#expense_month').on('change', function() {
        var month = $(this).val();
        $.ajax({
            type: "GET",
            data: {
                type: 1,
                month: month
            },
            url: "{{route('report.admin')}}",
            success: function(data) {
                var groupData = Object.values(convertIntObj(data.Data));
                chart1.series[0].setData(groupData);
                chart1.setTitle({
                    text: "Khoản chi theo cá nhân trong tháng " + month
                });
            },
            error: function(xhr, ajaxOptions, thrownError) {
                swal("Có lỗi xảy ra!", "Tạo đơn hàng thất bại", "error");
            }
        });
    });
    $('#expense_month2').on('change', function() {
        var month = $(this).val();
        $.ajax({
            type: "GET",
            data: {
                type: 2,
                month: month
            },
            url: "{{route('report.admin')}}",
            success: function(data) {
                var groupData = Object.values(convertIntObj(data.Data));
                chart2.series[0].setData(groupData);
                chart2.setTitle({
                    text: "Khoản chi theo danh mục trong tháng " + month
                });
            },
            error: function(xhr, ajaxOptions, thrownError) {
                swal("Có lỗi xảy ra!", "Tạo đơn hàng thất bại", "error");
            }
        });
    });
</script>
@endsection

@section('scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
@endsection