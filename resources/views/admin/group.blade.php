@extends('layouts._adminlayout')

@section('content')
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">Nhóm người dùng</h3>
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
                        <th>Tên nhóm</th>
                        <th>Mã nhóm</th>
                        <th>Trạng thái</th>
                        <th width="90">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groups as $itm)
                    <tr id="{{$itm->id}}">
                        <td>{{$itm->name}}</td>
                        <td>{{$itm->code}}</td>
                        <td>Hoạt động</td>
                        <td>
                            <a href="javascript:;" class="btn btn-success btn-sm mr-2 edit_group" title="Sửa/Xem chi tiết">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm" title="Xóa" onclick="deleteGroup('{{$itm->id}}', '{{$itm->name}}')">
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
<div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="Create group" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm/sửa nhóm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="m-form m-form--fit m-form--label-align-right">
                    <input type="hidden" id="group_id">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group">
                            <label for="group_name">Tên nhóm</label>
                            <input type="text" class="form-control m-input" name="group_name" id="group_name" aria-describedby="emailHelp" placeholder="Nhập vào tên nhóm người dùng">
                            <span class="error_msg"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="group_code">Mã nhóm</label>
                            <input type="text" class="form-control m-input" name="group_code" id="group_code" placeholder="Mã nhóm người dùng">
                            <span class="error_msg"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="save_group" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var groups = '{!! json_encode($groups, TRUE) !!}';
    jQuery(document).ready(function() {
        $('#m_modal_1').on('hidden.bs.modal', function(e) {
            setTimeout(function() {
                $('#m_modal_1 input').val('');
            }, 300);
        });
    });
    $(function() {
        $("#m_table_2").DataTable({
            scrollY: "50vh",
            scrollX: !0,
            scrollCollapse: !0,
            order: [
                [1, 'asc']
            ],
            columnDefs: [{
                orderable: false,
                targets: [0, 6, 7]
            }]
        });

    });

    $('.edit_group').on('click', function() {
        var id = $(this).parents('tr').attr('id');
        var item = JSON.parse(groups).find(x => x.id == id);
        $('#m_modal_1 #group_name').val(item.name);
        $('#m_modal_1 #group_code').val(item.code);
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