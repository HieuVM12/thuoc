@extends('layouts.admin')
@section('style')
<style>
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
                <div class="card-header">{{ __('Thêm mã giảm giá') }}
                    <a href="{{url('/admin/voucher/')}}" class="btn btn-primary btn-md btn-block float-end"><i
                            class="mdi mdi-arrow-left-bold"></i>Quay lại</a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{url('admin/voucher/store')}}">
                        @csrf
                        <div class="row mb-3">
                            <label for="tieu_de" class="col-md-4 col-form-label text-md-end">{{ __('Tiêu đề') }}</label>

                            <div class="col-md-6">
                                <input id="tieu_de" type="text"
                                    class="form-control @error('tieu_de') is-invalid @enderror" name="tieu_de"
                                    value="{{ old('tieu_de') }}" required autocomplete="tieu_de" autofocus>

                                @error('tieu_de')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="ma_giam_gia" class="col-md-4 col-form-label text-md-end">{{ __('Mã giảm giá')
                                }}</label>

                            <div class="col-md-6">
                                <input id="ma_giam_gia" type="text"
                                    class="form-control @error('ma_giam_gia') is-invalid @enderror" name="ma_giam_gia"
                                    value="{{ old('ma_giam_gia') }}" required autocomplete="ma_giam_gia" autofocus>

                                @error('ma_giam_gia')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="muc_tien" class="col-md-4 col-form-label text-md-end">{{ __('Mức tiền')
                                }}</label>

                            <div class="col-md-6">
                                <input id="muc_tien" type="number"
                                    class="form-control @error('muc_tien') is-invalid @enderror" name="muc_tien"
                                    value="{{ old('muc_tien') }}" required autocomplete="muc_tien" autofocus>

                                @error('muc_tien')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="tong_hoa_don" class="col-md-4 col-form-label text-md-end">{{ __('Tổng hóa đơn')
                                }}</label>

                            <div class="col-md-6">
                                <input id="tong_hoa_don" type="number"
                                    class="form-control @error('tong_hoa_don') is-invalid @enderror" name="tong_hoa_don"
                                    value="{{ old('tong_hoa_don') }}" required autocomplete="tong_hoa_don" autofocus>

                                @error('tong_hoa_don')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="noi_dung" class="col-md-4 col-form-label text-md-end">{{ __('Nội dung')
                                }}</label>

                            <div class="col-md-6">
                                <textarea name="noi_dung" id="noi_dung" rows="4">{{old('noi_dung')}}</textarea>

                                @error('noi_dung')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="ngay_bat_dau" class="col-md-4 col-form-label text-md-end">{{ __('Ngày bắt đầu')
                                }}</label>

                            <div class="col-md-6">
                                <input id="ngay_bat_dau" type="datetime-local"
                                    class="form-control @error('ngay_bat_dau') is-invalid @enderror" name="ngay_bat_dau"
                                    value="{{ old('ngay_bat_dau') }}" required autocomplete="ngay_bat_dau" autofocus>

                                @error('ngay_bat_dau')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="ngay_ket_thuc" class="col-md-4 col-form-label text-md-end">{{ __('Ngày kết
                                thúc') }}</label>

                            <div class="col-md-6">
                                <input id="ngay_ket_thuc" type="datetime-local"
                                    class="form-control @error('ngay_ket_thuc') is-invalid @enderror"
                                    name="ngay_ket_thuc" value="{{ old('ngay_ket_thuc') }}" required
                                    autocomplete="ngay_ket_thuc" autofocus>

                                @error('ngay_ket_thuc')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="loai_voucher" class="col-md-4 col-form-label text-md-end">{{ __('Loại voucher')
                                }}</label>

                            <div class="col-md-6">
                                <select name="loai_voucher" class="form-select" id="">
                                    <option value="0">Dùng 1 lần</option>
                                    <option value="1">Dùng nhiều lần</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="doi_tuong_gui" class="col-md-4 col-form-label text-md-end">{{ __('Đối tượng
                                gửi') }}</label>

                            <div class="col-md-6">
                                <select name="doi_tuong_gui[]" class="form-select js-example-basic-multiple" id=""
                                    multiple="multiple">
                                    <option value="0">Toàn bộ thành viên</option>
                                    <option value="1">Hạng vàng</option>
                                </select>
                                @error('doi_tuong_gui')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Thêm') }}
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
    $('.js-example-basic-multiple').select2();
</script>
<script>
    ClassicEditor
        .create( document.querySelector( '#noi_dung' ),{
            ckfinder:
            {
                uploadUrl: '{{route('image.upload',['_token'=>csrf_token()])}}',
            }
        } )
        .catch( error => {
            console.error( error );
        });
</script>
@endsection
