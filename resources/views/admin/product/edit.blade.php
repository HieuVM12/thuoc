@extends('layouts.admin')
@section('style')
<style>
    /* Custom style to make Select2 look more appealing */
    .select2-container--default .select2-selection--single {
        width: 100%;
        height: 40px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 5px 12px;
        /* Adjust padding to match Bootstrap form-control */
        font-size: 14px;
        /* Adjust font size to match Bootstrap form-control */
        color: #495057;
        /* Match Bootstrap form-control text color */
    }

    .ck-editor__editable {
        min-height: 200px;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                <p><strong class="alert-danger">{{ $error }}</strong></p>
                @endforeach
            </div>
            @endif
            <div class="card">
                <div class="card-header">{{ __('Sửa sản phẩm thuốc') }}
                    <a href="{{url('/admin/product/')}}" class="btn btn-primary btn-md btn-block float-end"><i
                            class="mdi mdi-arrow-left-bold"></i>Quay lại</a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{url('admin/product/update/'.$product->id)}}" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                    aria-selected="true">Thông tin sản phẩm</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile" aria-selected="false">Các thông
                                    số</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                    type="button" role="tab" aria-controls="contact"
                                    aria-selected="false">Ảnh sản phẩm</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab-1" data-bs-toggle="tab" data-bs-target="#contact1"
                                    type="button" role="tab" aria-controls="contact1"
                                    aria-selected="false">Hoạt chất</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row mb-3" style="padding-top: 20px">
                                    <label class="col-md-2 col-form-label text-md-end">Mã sản phẩm</label>
                                    <div class="col-md-8">
                                        <input id="code" type="text"
                                            class="form-control @error('code') is-invalid @enderror" name="code"
                                            value="{{ $product->code }}" required autocomplete="code" autofocus>
                                        @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3" style="padding-top: 20px">
                                    <label class="col-md-2 col-form-label text-md-end">Tên sản phẩm</label>
                                    <div class="col-md-8">
                                        <input id="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ $product->name }}" required autocomplete="name" autofocus>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3" style="padding-top: 20px">
                                    <label class="col-md-2 col-form-label text-md-end">Nhóm thuốc</label>
                                    <div class="col-md-8">
                                        <select name="category_id" class="form-control" id="category_id">
                                            @foreach ($categories as $category)
                                            <option value="{{$category->id}}" {{$product->category_id == $category->id ? 'selected':''}}>{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3" style="padding-top: 20px">
                                    <label class="col-md-2 col-form-label text-md-end">Nhà sản xuất</label>
                                    <div class="col-md-8">
                                        <select name="producer_id" class="form-control" id="producer_id">
                                            @foreach ($producers as $producer)
                                            <option value="{{$producer->id}}" {{$product->producer_id == $producer->id ? 'selected':''}}>{{$producer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3" style="padding-top: 20px">
                                    <label class="col-md-2 col-form-label text-md-end">Thông tin</label>
                                    <div class="col-md-8">
                                        <textarea name="information" id="information"
                                            rows="4">{{$product->information}}</textarea>
                                        @error('information')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3" style="padding-top: 20px">
                                    <label class="col-md-2 col-form-label text-md-end">Cách sử dụng</label>
                                    <div class="col-md-8">
                                        <textarea name="usage" id="usage" rows="4">{{$product->usage}}</textarea>
                                        @error('usage')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row mb-3" style="padding-top: 20px">
                                    <label class="col-md-2 col-form-label text-md-end">Đơn vị tính</label>
                                    <div class="col-md-8">
                                        <input id="unit" type="text"
                                            class="form-control @error('unit') is-invalid @enderror" name="unit"
                                            value="{{ $product->unit }}" required autocomplete="unit" autofocus>
                                        @error('unit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3" style="padding-top: 20px">
                                    <label class="col-md-2 col-form-label text-md-end">Số lượng</label>
                                    <div class="col-md-8">
                                        <input id="quantity" type="number"
                                            class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                                            value="{{ $product->quantity }}" required autocomplete="quantity" autofocus>
                                        @error('quantity')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3" style="padding-top: 20px">
                                    <label class="col-md-2 col-form-label text-md-end">Đơn giá</label>
                                    <div class="col-md-8">
                                        <input id="price" type="number"
                                            class="form-control @error('price') is-invalid @enderror" name="price"
                                            value="{{ $product->price }}" required autocomplete="price" autofocus>
                                        @error('price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3" style="padding-top: 20px">
                                    <label class="col-md-2 col-form-label text-md-end">Trạng thái</label>
                                    <div class="col-md-8">
                                        <select name="status" class="form-select" id="status">
                                            <option value="1" {{$product->status=='1' ?'selected':''}}>Hiển thị</option>
                                            <option value="0" {{$product->status=='0' ?'selected':''}}>Ẩn tạm thời</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="row mb-3" style="padding-top: 20px">
                                    <label class="col-md-2 col-form-label text-md-end">Upload ảnh sản phẩm</label>
                                    <div class="col-md-8">
                                        <input type="file" name="image[]" accept="image/*" class="form-control" multiple @if (!$product->image_products)
                                        required
                                        @endif>
                                    </div>
                                </div>
                                <div class="row mb-3" style="padding-top: 20px">
                                    @if ($product->image_products)
                                    @foreach ($product->image_products as $image)
                                    <div class="col-md-2 prod-image">
                                        <img src="{{asset($image->img)}}" class="me-4 border" alt="" width="150px" height="150px">
                                        <button type="button" class="d-block btn btn-danger text-white deleteImageBtn" value="{{$image->id}}">Xóa</button>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane fade" id="contact1" role="tabpanel" aria-labelledby="contact-tab-1">
                                <div class="row mb-3" style="padding-top: 20px">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped" id="ingredientTable">
                                            <thead>
                                                <tr>
                                                    <th>Tên hoạt chất</th>
                                                    <th>Hàm lượng</th>
                                                    <th><button type="button" class="btn btn-primary" onclick="addRow()">Thêm dòng mới</button></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($product->hoat_chat)
                                                @foreach ($product->hoat_chat as $key=>$hoatChat)
                                                <tr>
                                                    <td>
                                                        <select name="ingredients[]" class="form-select ingredient-select-2" style="width: 750px">
                                                            @foreach ($ingredients as $ingredient)
                                                            <option value="{{$ingredient->id}}" {{$hoatChat['id_hoat_chat']==$ingredient->id?'selected':''}}>{{$ingredient->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="ingredient_amount[]" value="{{$hoatChat['ham_luong']}}" class="form-control" required>
                                                    </td>
                                                    <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Xóa</button></td>
                                                </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-2 offset-md-10">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Cập nhật') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $('#category_id').select2();
    $('#producer_id').select2();
    $('.ingredient-select-2').select2();
</script>
<script>
    ClassicEditor
        .create( document.querySelector( '#information' ),{
            ckfinder:
            {
                uploadUrl: '{{route('image.upload',['_token'=>csrf_token()])}}',
            }
        } )
        .catch( error => {
            console.error( error );
        });
    ClassicEditor
        .create( document.querySelector( '#usage' ),{
            ckfinder:
            {
                uploadUrl: '{{route('image.upload',['_token'=>csrf_token()])}}',
            }
        } )
        .catch( error => {
            console.error( error );
        });
</script>
<script>
    function addRow() {
        var newRow = '<tr>' +
            '<td>' +
            '<select name="ingredients[]" class="form-select ingredient-select-2">' +
            '@foreach ($ingredients as $ingredient)' +
            '<option value="{{$ingredient->id}}">{{$ingredient->name}}</option>' +
            '@endforeach' +
            '</select>' +
            '</td>' +
            '<td><input type="text" name="ingredient_amount[]" class="form-control" required></td>' +
            '<td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Xóa</button></td>' +
            '</tr>';
        $('#ingredientTable tbody').append(newRow);
        $('.ingredient-select-2').select2();
    }

    function removeRow(button) {
        // Get the row to be removed
        var row = $(button).closest('tr');
        // Remove the row
        row.remove();
    }
</script>
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click','.deleteImageBtn',function(){
            var imageId = $(this).val();
            var thisClick = $(this);
            if(confirm('Bạn có chắc muốn xóa ảnh này?')){
                $.ajax({
                    type:"GET",
                    url:"/admin/product/image/delete/"+imageId,
                    success: function(response){
                        thisClick.closest('.prod-image').remove();
                        alert(response.message);
                    }
                });
            }
        })
    });
</script>
@endsection
