@extends('layouts._adminlayout')

@section('content')
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">Lớp học</h3>
        </div>
    </div>
</div>

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <a href="javascript:;" data-toggle="modal" data-target="#m_modal_1" class="btn btn-primary"><i class="fa fa-plus mr-2"></i> Tạo lớp</a>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_2">
                <thead>
                    <tr>
                        <th>Mã lớp học</th>
                        <th>Level</th>
                        <th>Lịch học</th>
                        <th>SL học sinh</th>
                        <th>Trạng thái</th>
                        <th width="90">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($class as $itm)
                    <tr id="{{$itm->code}}">
                        <td>{{$itm->code}}</td>
                        <td>{{$itm->name}}</td>
                        <td>{{$itm->time_range}}</td>
                        <td>{{$itm->total}}</td>
                        <td>
                            @if($itm->status == 0)
                            Chờ tuyển sinh
                            @elseif($itm->status == 1)
                            Đang tuyển sinh
                            @elseif($itm->status == 2)
                            Đang hoạt động
                            @elseif($itm->status == 3)
                            Hoàn thành
                            @endif
                        </td>
                        <td>
                            <a href="javascript:;" class="btn btn-success btn-sm mr-2 edit_user" title="Sửa/Xem chi tiết">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm" title="Xóa" onclick="">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="Create user" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="main_form" method="POST" action='{{ route("save_class.admin") }}' class="m-form m-form--fit m-form--label-align-right form-ajax">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Thêm/sửa lớp học</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group">
                            <label for="user_name">Mã lớp học</label>
                            <div class="input-group">
                                <input id="class_code" name="class_code" type="text" class="form-control" placeholder="Nhập mã lớp">
                                <div class="input-group-append">
                                    <button onclick="genCode()" class="btn btn-secondary" type="button">Auto</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="password">Lịch học</label>
                            <select class="form-control m-input m-input--square" id="class_schedule" name="class_schedule">
                                <option value="">Chọn lịch học</option>
                                @foreach($schedules as $itm)
                                <option value="{{$itm->id}}">{{$itm->name . ' ' . $itm->time_range}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="m-form__group form-group">
                            <label for="">Trạng thái</label>
                            <div class="m-radio-inline">
                                <label class="m-radio">
                                    <input type="radio" checked name="status" value="0">Khởi tạo
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" name="status" value="1">Tuyển sinh
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" name="status" value="2">Đang hoạt động
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" name="status" value="3">Kết thúc
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-form__group">
                        <label>Ngày khai giảng</label>
                        <div class="col-lg-4 col-md-9 col-sm-12 pdl-0">
                            <div class="input-group date">
                                <input type="text" class="form-control m-input" readonly placeholder="Select date & time" name="start_date" id="start_date" />
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="save_user" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        $("#main_form").validate({
            ignore: [],
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            rules: {
                class_code: {
                    required: true
                },
                class_schedule: {
                    required: true
                }
            },
            messages: {
                class_code: {
                    required: "Vui lòng nhập mã lớp học"
                },
                class_schedule: "Vui lòng chọn lịch học"
            }
        });
        $("#start_date").datetimepicker({
            format: "dd/mm/yyyy",
            todayHighlight: !0,
            autoclose: !0,
            startView: 2,
            minView: 2,
            forceParse: 0,
            pickerPosition: "bottom-left"
        });
    });
    $(function() {
        $("#m_table_2").DataTable({
            responsive: !0,
            dom: "<'row'<'col-sm-12'tr>>\n\t\t\t<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
            language: {
                lengthMenu: "Display _MENU_"
            },
            order: [
                [1, "desc"]
            ],
        });

    });

    $('.edit_user').on('click', function() {
        var id = $(this).parents('tr').attr('id');
        var raw = JSON.parse(users);
        const item = Object.keys(raw)
            .filter(key => key == id)
            .reduce((obj, key) => {
                obj[key] = raw[key];
                return obj[key];
            }, {});
        console.log(item);
        $('#m_modal_1 #user_id').val(item.user_id);
        $('#m_modal_1 #user_name').val(item.username).prop('disabled', true);
        $('#m_modal_1 #user_status').prop('checked', item.active === 1 ? true : false);
        $("#m_modal_1 #user_type").bootstrapSwitch('state', item.admin === 1 ? true : false);
        var group_id = item.group_id.toString().split(',');
        $("#m_select2_3").val(group_id).trigger('change');
        $('#m_modal_1').modal('show');
    });

    $('#class_schedule').on('change', function() {
        var scheduleId = $('#class_schedule').val();
        var code = 'CV' + (new Date().getMonth() + 1) + new Date().getFullYear();
        if (scheduleId) {
            if (scheduleId == 1)
                code += 'NM';
            else if (scheduleId == 2)
                code += 'CB';
            else if (scheduleId == 3)
                code += 'NC';
            else if (scheduleId == 4)
                code += 'CLB';
        }
        $('#class_code').val(code);
    });

    function genCode() {
        var scheduleId = $('#class_schedule').val();
        var code = 'CV' + (new Date().getMonth() + 1) + new Date().getFullYear();
        if (scheduleId) {
            if (scheduleId == 1)
                code += 'NM';
            else if (scheduleId == 2)
                code += 'CB';
            else if (scheduleId == 3)
                code += 'NC';
            else if (scheduleId == 4)
                code += 'CLB';
        }
        $('#class_code').val(code);
    }

    function deleteGroup(_id, _name) {
        _id = Number(_id);
        swal({
                title: "Xóa nhóm người dùng",
                text: "Bạn có chắc chắn muốn xóa nhóm [" + _name + "] không?",
                type: "warning",
                showCancelButton: true,
            })
            .then((res) => {
                if (res.value) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{route('delete_group.admin')}}",
                        data: {
                            id: _id
                        },
                        success: function(data) {
                            if (data === "1") {
                                swal("Thành công!", "Xóa nhóm thành công.", "success");
                                $('#' + _id).remove();
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