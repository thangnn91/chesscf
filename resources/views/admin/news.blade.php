@extends('layouts._adminlayout')

@section('content')
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">Quản lý tin bài</h3>
        </div>
    </div>
</div>

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <a href="{{route('add_news.admin')}}" class="btn btn-primary"><i class="fa fa-plus mr-2"></i> Thêm mới</a>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <form method="GET" action='{{ route("news.admin") }}' class="m-form m-form--fit m--margin-bottom-20">
                <div class="row m--margin-bottom-20">
                    <div class="col-lg-6 m--margin-bottom-10-tablet-and-mobile">
                        <label>Ngày tạo:</label>
                        <div class="input-daterange input-group" id="m_datepicker">
                            <input value="{{isset($data_back) && isset($data_back['start']) ? $data_back['start'] : ''}}" autocomplete="false" type="text" class="form-control m-input" id="start" name="start" placeholder="Từ ngày" data-col-index="5">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                            </div>
                            <input value="{{isset($data_back) && isset($data_back['end']) ? $data_back['end'] : ''}}" autocomplete="false" type="text" class="form-control m-input" id="end" name="end" placeholder="Đến ngày" data-col-index="5">
                        </div>
                    </div>
                    <div class="col-lg-4 m--margin-bottom-10-tablet-and-mobile">
                        <label>Tiêu đề:</label>
                        <input type="title" id="title" name="title" class="form-control m-input" placeholder="Tiêu đề..." data-col-index="4">
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
                        <th>Tiêu đề</th>
                        <th>Banner</th>
                        <th>Nội dung</th>
                        <th>Trạng thái tin</th>
                        <th width="90">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($news as $itm)
                    <tr id="{{$itm->id}}">
                        <td>{{$itm->title}}</td>
                        <td>
                            @if(!($itm->banner == '' || $itm->banner == null))
                            <img width="150" src="{{asset('userfiles') .'/'. json_decode($itm->banner)[0]->key}}" />
                            @endif
                        </td>
                        <td>
                            <a href="javascript:;" onclick='showDetail({{$itm->id}});'>Xem nội dung</a>
                        </td>
                        <td>
                            @if($itm->is_hot == 1)
                            <span class="m-badge m-badge--warning m-badge--wide">Hot</span>
                            @endif

                            @if($itm->is_new == 1)
                            <span class="m-badge m-badge--info m-badge--wide">New</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{route('edit_news.admin').'/'.$itm->id}}" class="btn btn-success btn-sm mr-2 edit_user" title="Sửa/Xem chi tiết">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm" title="Xóa" onclick="deleteItem('{{$itm->id}}', '{{$itm->title}}')">
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
<div class="modal fade" id="m_modal_modalcontent" tabindex="-1" role="dialog" aria-labelledby="Chi tiết đơn hàng" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-print" role="document">
        <div style="display: inline-table; padding: 40px;" class="modal-content">
        </div>

    </div>
</div>
<script>
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

    function deleteItem(_id, _name) {
        _id = Number(_id);
        swal({
                title: "Xóa bài viết",
                text: "Bạn có chắc chắn muốn xóa bài viết [" + _name + "] không?",
                type: "warning",
                showCancelButton: true,
            })
            .then((res) => {
                if (res.value) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{route('delete_news.admin')}}",
                        data: {
                            id: _id
                        },
                        success: function(data) {
                            if (data === "1") {
                                swal("Thành công!", "Xóa bài viết thành công.", "success");
                                $('#' + _id).remove();
                            } else {
                                swal("Thất bại!", "Hệ thống đang bận, vui lòng thử lại sau.", "error");
                            }
                        }
                    });
                }
            });

    }

    function showDetail(id) {
        $.ajax({
            type: "GET",
            data: {
                id: id
            },
            url: "{{route('news_content.admin')}}",
            success: function(data) {
                if (data.statusCode > 0) {
                    $('#m_modal_modalcontent .modal-content').html(data.content);
                    $('#m_modal_modalcontent').modal('show');
                    return;
                }
                swal("Thất bại!", data.message, "error");
            },
            error: function(xhr, ajaxOptions, thrownError) {
                swal("Có lỗi xảy ra!", "Tạo đơn hàng thất bại", "error");
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