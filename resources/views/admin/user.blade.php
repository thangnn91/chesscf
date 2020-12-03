@extends('layouts._adminlayout')

@section('content')
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">Người dùng</h3>
        </div>
    </div>
</div>

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <a href="javascript:;" data-toggle="modal" data-target="#m_modal_1" class="btn btn-primary"><i class="fa fa-plus mr-2"></i> Add</a>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_2">
                <thead>
                    <tr>
                        <th>Tài khoản</th>
                        <th>IsAdmin</th>
                        <th>Trạng thái</th>
                        <th>Nhóm tài khoản</th>
                        <th width="90">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tmp as $itm)
                    <tr id="{{$itm->user_id}}">
                        <td>{{$itm->username}}</td>
                        <td>
                            @if($itm->admin == 1)
                            <i class="la la-check-circle-o"></i>
                            @else
                            <i class="la la-times-circle-o"></i>
                            @endif
                        </td>
                        <td>
                            @if($itm->active == 1)
                            Hoạt động
                            @else
                            Không hoạt động
                            @endif
                        </td>
                        <td>
                            {{$itm->group_name}}
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="main_form" method="POST" action='{{ route("save_user.admin") }}' class="m-form m-form--fit m-form--label-align-right form-ajax">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Thêm/sửa tài khoản</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group">
                            <label for="user_name">Tên tài khoản</label>
                            <input type="text" class="form-control m-input" name="user_name" id="user_name" placeholder="Username">
                        </div>
                        <div class="form-group m-form__group">
                            <label for="password">Mật khẩu</label>
                            <input type="password" class="form-control m-input" name="password" id="password" placeholder="Mật khẩu">
                        </div>
                        <div class="form-group m-form__group">
                            <label for="re_password">Nhập lại mật khẩu</label>
                            <input type="password" class="form-control m-input" name="re_password" id="re_password" placeholder="Mật khẩu">
                        </div>
                        <div class="m-form__group form-group">
                            <div class="m-checkbox-list">

                                <label class="m-checkbox m-checkbox--solid">
                                    <input id="user_status" name="user_status" type="checkbox" checked="checked"> Trạng thái
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="user_type">Loại tài khoản</label>&nbsp;&nbsp;
                            <input id="user_type" name="user_type" data-on-text="Admin" data-off-text="User" data-switch="true" type="checkbox" data-on-color="success" data-off-color="warning">
                        </div>
                        <div class="form-group m-form__group">
                            <div>
                                <label for="m_select2_3">Nhóm tài khoản</label>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <select class="form-control m-select2" name="m_select2_3[]" id="m_select2_3" data-placeholder="Chọn nhóm tài khoản" multiple="multiple">
                                        @foreach($groups as $itm)
                                        <option value="{{$itm->id}}">{{$itm->name}}</option>
                                        @endforeach
                                    </select>
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
    var BootstrapSwitch = {
        init: function() {
            $("[data-switch=true]").bootstrapSwitch()
        }
    };
    jQuery(document).ready(function() {
        BootstrapSwitch.init();
        $("#m_select2_3").select2({
            placeholder: "Chọn nhóm người dùng",
            width: '100%',
            placeholder: "Chọn nhóm tài khoản"
        });
        $('#m_modal_1').on('hide.bs.modal', function(e) {
            setTimeout(function() {
                $('#m_modal_1 #user_id,#m_modal_1 #user_name, #m_modal_1 #username, #m_modal_1 #password, #m_modal_1 #re_password').val('');
                $('#m_modal_1 #user_name').prop('disabled', false);
                $("#m_modal_1 #user_type").bootstrapSwitch('state', false);
                $("#m_select2_3").val([]).trigger('change');
            }, 300);
        });

        jQuery.validator.addMethod('re_password_check', function(value, element, params) {
            var password = $('#m_modal_1 #password').val();
            return (password && $('#m_modal_1 #re_password').val() !== password);
        }, "Vui lòng nhập mật khẩu");

        $("#main_form").validate({
            ignore: [],
            rules: {
                user_name: {
                    required: true
                },
                m_select2_3: {
                    required: true
                },
                password: {
                    required: function(element) {
                        return $('#m_modal_1 #user_id').val().length === 0;
                    }
                },
                re_password: {
                    required: function(element) {
                        return $('#m_modal_1 #user_id').val().length === 0;
                    },
                    equalTo: "#password"
                }
            },
            messages: {
                user_name: {
                    required: "Vui lòng nhập tên tài khoản"
                },
                m_select2_3: "Vui lòng chọn nhóm tài khoản",
                password: "Vui lòng nhập mật khẩu",
                re_password: {
                    required: "Vui lòng nhập lại mật khẩu",
                    equalTo: "Mật khẩu nhập lại không đúng"
                },
            }
        });
    });
    var users = '{!! json_encode($tmp, TRUE) !!}';
    var groups = '{!! json_encode($groups, TRUE) !!}';
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

    $('#save_group').on('click', function() {
        let group_name = $('#group_name').val();
        if (!group_name) {
            $('#group_name').siblings('.error_msg').text('Vui lòng nhập tên nhóm người dùng').show();
            $('#group_name').focus();
            return;
        }
        let group_code = $('#group_code').val();
        if (!group_code) {
            $('#group_code').siblings('.error_msg').text('Vui lòng nhập mã nhóm người dùng').show();
            $('#group_code').focus();
            return;
        }
        $.ajax({
            type: "POST",
            url: "{{route('save_group.admin')}}",
            data: {
                id: $('#group_id').val(),
                name: group_name,
                code: group_code
            },
            success: function(data) {
                if (data === "1") {
                    swal("Thành công!", "Lưu nhóm thành công.", "success");
                    $('#m_modal_1 input').val('');
                    $('#m_modal_1').modal('hide');
                    window.location.reload();
                } else {
                    swal("Thất bại!", "Hệ thống đang bận, vui lòng thử lại sau.", "error");
                }
            }
        });
    });


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