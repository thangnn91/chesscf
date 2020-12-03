@extends('layouts._adminlayout')

@section('content')
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">Quản lý sản phẩm</h3>
        </div>
    </div>
</div>

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <a href="{{route('product_item.admin')}}" class="btn btn-primary"><i class="fa fa-plus mr-2"></i> Add</a>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_2">
                <thead>
                    <tr>
                        <th>Ảnh mô tả</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Giá KM</th>
                        <th>Trạng thái</th>
                        <th width="90">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $itm)
                    <tr id="{{$itm->id}}">
                        <td><img width="150" src="{{asset('userfiles').'/'.$itm->avatar}}" /></td>
                        <td>{{$itm->name}}</td>
                        <td>{{$itm->categories_name}}</td>
                        <td>{{number_format($itm->amount)}}</td>
                        <td>{{number_format($itm->price)}}</td>
                        <td>{{$itm->discount_price !== 0 ? number_format($itm->discount_price) :  ''}}</td>
                        <td>
                            @if($itm->is_hot == 1)
                            <span class="m-badge m-badge--warning m-badge--wide">Hot</span>
                            @endif

                            @if($itm->is_new == 1)
                            <span class="m-badge m-badge--info m-badge--wide">New</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{route('product_item.admin').'/'.$itm->id}}" class="btn btn-success btn-sm mr-2" title="Sửa/Xem chi tiết">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm" title="Xóa" onclick="deleteProduct('{{$itm->id}}', '{{$itm->name}}')">
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
<script>
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

    function deleteProduct(_id, _name) {
        _id = Number(_id);
        swal({
                title: "Xóa sản phẩm",
                text: "Bạn có chắc chắn muốn xóa sản phẩm [" + _name + "] không?",
                type: "warning",
                showCancelButton: true,
            })
            .then((res) => {
                if (res.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{route('delete_product.admin')}}",
                        data: {
                            id: _id
                        },
                        success: function(data) {
                            if (data === "1") {
                                swal("Thành công!", "Xóa sản phẩm thành công.", "success");
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