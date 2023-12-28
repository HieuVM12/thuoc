@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" id="validatedForm">
                        @csrf
                        <div class="row mb-3">
                            <label for="unit_code" class="col-md-4 col-form-label text-md-end">{{ __('Unit Code')
                                }}</label>

                            <div class="col-md-6">
                                <input id="unit_code" type="text"
                                    class="form-control @error('unit_code') is-invalid @enderror" name="unit_code"
                                    value="{{ old('unit_code') }}" required autocomplete="unit_code" autofocus>

                                @error('unit_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address')
                                }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" value="{{ old('phone') }}" minlength="10" maxlength="10"
                                    pattern="^[0-9]*$" required autocomplete="phone" autofocus>

                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password')
                                }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror"  name="password"
                                    required autocomplete="new-password">
                                <span class="invalid-feedback" role="alert" id="password-error" style="display: none">
                                    <strong>Mật khẩu phải có ít nhất 8
                                        ký tự, trong đó có ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biết</strong>
                                </span>
                                <span class="invalid-feedback" role="alert" id="password-error-confirm" style="display: none">
                                    <strong>Mật khẩu và mật khẩu nhập lại không trùng khớp</strong>
                                </span>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm
                                Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
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
    document.getElementById("validatedForm").addEventListener("submit", function(event) {
        var password = document.getElementById("password").value;
        var password_confirm = document.getElementById("password-confirm").value;
        var errorDiv = document.getElementById("password-error");
        var errorDivConfirm = document.getElementById("password-error-confirm");
        var passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#\$%\^&\*]).{8,}$/;
        if (password.length < 8 || !password.match(passwordRegex)) {
            document.getElementById("password").value = "";
            document.getElementById("password-confirm").value = "";
            errorDiv.style.display = "block";
            event.preventDefault();
        } else{
            errorDiv.style.display = "none";
        }

        if (password != password_confirm) {
            document.getElementById("password").value = "";
            document.getElementById("password-confirm").value = "";
            errorDivConfirm.style.display = "block";
            event.preventDefault();
        } else{
            errorDivConfirm.style.display = "none";
        }
    });

</script>
@endsection
