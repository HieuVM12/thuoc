@extends('layouts.admin')
@section('style')
<style>
    .select2-container--default .select2-selection--single {
        height: 42px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 6px 7px;
        /* Adjust padding to match Bootstrap form-control */
        font-size: 15px;
        /* Adjust font size to match Bootstrap form-control */
        color: #495057;
        /* Match Bootstrap form-control text color */
    }
</style>
@endsection
@section('content')
<div class="col-md-12">
    @if (session('message'))
    <div class="alert alert-success">{{session('message')}}</div>
    @endif
    <div class="card">
        <div class="card-header">
            <h4>Quản lý sản phẩm thuốc
                <a href="{{url('admin/product/create')}}" class="btn btn-primary btn-md btn-block float-end">
                    <i class="mdi mdi-hospital"></i>
                    Thêm sản phẩm
                </a>
            </h4>
        </div>
        <div class="card-header">
            <div class="row">
                {{-- <div class="col-md-6">
                    <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input type="file" name="file" class="form-control">
                        </div>
                        <button class="btn btn-success" type="submit">
                            Import User Data
                        </button>
                    </form>
                </div> --}}
                <div class="col-md-6">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Xuất Excel
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item " href="javascript:void(0);" id="exportButton">Xuất Excel Tất
                                    Cả</a></li>
                            <li>
                                <form action="{{route('exportProductSelected')}}" method="POST"
                                    enctype="multipart/form-data" id="form2">
                                    @csrf
                                    <button class="dropdown-item" type="submit" id="exportButton2">Xuất Excel Sản Phẩm Chọn</button>
                                </form>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <form action="{{url('admin/product/search')}}" method="GET">
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
                <div class="row">
                    <div class="col-md-8">
                        <select name="search" class="form-select" id="product_search">
                            <option value="0">Chọn sản phẩm</option>
                            @foreach ($all_products as $product_search)
                            <option value="{{$product_search->id}}" @if (isset($searchInput)) {{
                                $searchInput==$product_search->id ? 'selected' : '' }}
                                @endif>{{$product_search->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary btn-fw">Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll"></th>
                        <th>ID</th>
                        <th>Mã sản phẩm</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm thuốc</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $key=>$product)
                    <tr>
                        <td><input type="checkbox" name="ids[{{$product->id}}]" value="{{$product->id}}"
                                data-product-id="{{$product->id}}"></td>
                        <td>{{$key+1}}</td>
                        <td>{{$product->code}}</td>
                        <td>
                            @if (isset($product->image_products[0]))
                            <img src="{{asset($product->image_products[0]->img)}}" alt="">
                            @endif
                        </td>
                        <td>{{$product->name}}</td>
                        <td>{{$product->status=='1'?'Hiển thị':'Ẩn tạm thời'}}</td>
                        <td>
                            <a href="{{url('admin/product/edit/'.$product->id)}}"
                                class="btn btn-outline-secondary btn-icon-text">
                                Sửa
                                <i class="mdi mdi-file-check btn-icon-append"></i>
                            </a>
                            <a href="{{url('admin/product/delete/'.$product->id)}}"
                                onclick="return confirm('Bạn có chắc muốn xóa sản phẩm thuốc này?')"
                                class="btn btn-outline-danger btn-icon-text">
                                Xóa
                                <i class="mdi mdi-trash-can btn-icon-append"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">Không có sản phẩm nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding: 15px">
                {{$products->links()}}
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    const checkAll = document.getElementById("checkAll");
    const items = document.querySelectorAll("input[type='checkbox'][name^='ids']");

    checkAll.addEventListener("change", function () {
        items.forEach(item => {
            item.checked = checkAll.checked;
        });
    });
</script>
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
<script>
    $('#product_search').select2();
</script>
<script>
    document.getElementById("exportButton").addEventListener("click", function() {
        window.location.href = "{{ route('exportProduct') }}";
    });
</script>
<script>
    document.getElementById("exportButton2").addEventListener("click", function() {
        var selectedCheckboxes = document.querySelectorAll('input[type="checkbox"][name^="ids"]:checked');
        var form2 = document.getElementById('form2');
        var inputsInForm2 = form2.querySelectorAll('input[name^="ids"]');
        // Xóa tất cả các input checkbox hiện có trong form2
        inputsInForm2.forEach(function(input) {
            input.remove();
        });
        // Duyệt qua các input checkbox đã chọn trong form1 và tạo input ẩn tương ứng trong form2
        selectedCheckboxes.forEach(function(selectedCheckbox) {
            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = selectedCheckbox.name;
            hiddenInput.value = selectedCheckbox.value;
            hiddenInput.checked = true; // Đặt giá trị checked
            form2.appendChild(hiddenInput);
        });
    });
</script>
@endsection
