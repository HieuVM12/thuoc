@extends('layouts.admin')
@section('content')
<div class="col-md-12">
    @if (session('message'))
    <div class="alert alert-success">{{session('message')}}</div>
    @endif
    <div class="card">
        <div class="card-header">
            <h4>Quản lý mã giảm giá
                <a href="{{url('admin/voucher/create')}}" class="btn btn-primary btn-md btn-block float-end">
                    <i class="mdi mdi-heart"></i>
                    Thêm mã giảm giá
                </a>
            </h4>
        </div>
        <form action="{{url('admin/voucher/search')}}" method="GET">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="itemsPerPage" onchange="updateItemsPerPage()"
                                aria-label="Floating label select example">
                                <option value="10" {{ $itemsPerPage==10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $itemsPerPage==20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ $itemsPerPage==50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $itemsPerPage==100 ? 'selected' : '' }}>100</option>
                            </select>
                            <label for="itemsPerPage">Số lượng mỗi trang</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-header">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="search">
                            <i class="mdi mdi-magnify"></i>
                        </span>
                    </div>
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm mã giảm giá" aria-label="search"
                        aria-describedby="search" @if (isset($searchInput)) value="{{$searchInput}}"
                        @endif required>
                    <button type="submit" class="btn btn-outline-primary btn-fw">Tìm kiếm</button>
                </div>
            </div>
        </form>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mã giảm giá</th>
                        <th>Tiêu đề</th>
                        <th>Loại voucher</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vouchers as $key=>$voucher)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$voucher->ma_giam_gia}}</td>
                        <td>{{$voucher->tieu_de}}</td>
                        <td>{{$voucher->loai_voucher==0?'Dùng 1 lần':'Dùng nhiều lần'}}</td>
                        <td>{{$voucher->ngay_bat_dau}}</td>
                        <td>{{$voucher->ngay_ket_thuc}}</td>
                        <td>
                            <a href="{{url('admin/voucher/edit/'.$voucher->id)}}" class="btn btn-outline-secondary btn-icon-text">
                                Sửa
                                <i class="mdi mdi-file-check btn-icon-append"></i>
                            </a>
                            <a href="{{url('admin/voucher/delete/'.$voucher->id)}}" onclick="return confirm('Bạn có chắc muốn xóa nhóm thuốc này?')" class="btn btn-outline-danger btn-icon-text">
                                Xóa
                                <i class="mdi mdi-trash-can btn-icon-append"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3">Không có nhóm thuốc nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding: 15px">
                {{$vouchers->links()}}
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    function updateItemsPerPage() {
        var itemsPerPage = document.getElementById("itemsPerPage").value;
        var currentUrl = window.location.href;
        // Sử dụng URLSearchParams để thay đổi tham số itemsPerPage trong URL
        var searchParams = new URLSearchParams(window.location.search);
        searchParams.set("itemsPerPage", itemsPerPage);
        var newUrl = currentUrl.split('?')[0] + '?' + searchParams.toString();
        window.location.href = newUrl;
    }
</script>
@endsection
