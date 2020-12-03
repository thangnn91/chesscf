@extends('layouts._adminlayout')

@section('content')
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">Cấu hình</h3>
        </div>
    </div>
</div>

<div class="m-content">
    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--mobile">
                <form id="main_form" class="m-form">
                    <div class="m-portlet__body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#m_tabs_1_0">Cấu hình hệ thống</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#" data-target="#m_tabs_1_1">Danh mục
                                    chi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#m_tabs_1_2">Danh mục thu</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="m_tabs_1_0" role="tabpanel">
                                <label for="exampleInputPassword1">Cấu hình hệ thống</label>
                                <div class="form-group m-form__group">
                                    <div class="m-checkbox-list">
                                        <label class="m-checkbox">
                                            <input class="system_config" value="Cho phép xóa đơn hàng" type="checkbox"> Cho phép xóa đơn hàng
                                            <span></span>
                                        </label>
                                        <input id="system_config" type="hidden" name="system_config">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="m_tabs_1_1" role="tabpanel">
                                <div class="form-group m-form__group">
                                    <label for="exampleInputPassword1">Cấu hình danh mục chi</label>
                                    <div class="form-group">
                                        <input type="text" data-role="tagsinput" class="form-control m-input" id="expense" name="expense" placeholder="Ấn enter để lưu">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="m_tabs_1_2" role="tabpanel">
                                <div class="form-group m-form__group">
                                    <label for="exampleInputPassword1">Cấu hình danh mục thu</label>
                                    <div class="form-group">
                                        <input type="text" data-role="tagsinput" class="form-control m-input" id="income" name="income" placeholder="Ấn enter để lưu">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__foot--fit">
                        <div class="m-form__actions">
                            <button onclick="save_config();" type="button" class="btn btn-primary ajax_disabled">Lưu lại</button>
                            <button type="reset" class="btn btn-secondary ajax_disabled">Quay lại</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
    var elem = document.createElement('textarea');
    elem.innerHTML = "{{$configs}}";
    var decoded = elem.value;
    var system_configs = JSON.parse(decoded);
    $(function() {
        var elt = $('#expense');
        elt.tagsinput();
        var elt2 = $('#income');
        elt2.tagsinput();
        if (system_configs.length) {
            var checkedVals = "";
            system_configs.forEach(function(entry) {
                if (entry.type === 2)
                    elt.tagsinput('add', entry.name);
                else if (entry.type === 1)
                    elt2.tagsinput('add', entry.name);
                else if (entry.type === 3) {
                    $("input[value='" + entry.name + "']").prop('checked', true);
                    checkedVals += entry.name + ",";
                }
            });
            $('#system_config').val(checkedVals.slice(0, -1));
        }
    });
    $('#main_form').on('change', '.system_config', function() {
        var checkedVals = $('.system_config:checkbox:checked').map(function() {
            return this.value;
        }).get().join(',');
        $('#system_config').val(checkedVals ? checkedVals : "");
    });

    function save_config() {
        var formData = new FormData(document.getElementById('main_form')); // yourForm: form selector  
        $.ajax({
            type: "POST",
            url: "{{route('save_config.admin')}}", // where you wanna post
            data: formData,
            processData: false,
            contentType: false,
            error: function(jqXHR, textStatus, errorMessage) {
                swal("Thất bại!", "Hệ thống đang bận, vui lòng thử lại sau.", "error");
            },
            success: function(data) {
                swal("Thành công!", "Lưu dữ liệu thành công", "success");
            }
        });
    }
</script>
<style>
    .label {
        display: inline;
        padding: .2em .6em .3em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25em;
    }

    .label-info {
        background-color: #5bc0de;
    }

    .bootstrap-tagsinput {
        width: 100%;
    }
</style>
@endsection