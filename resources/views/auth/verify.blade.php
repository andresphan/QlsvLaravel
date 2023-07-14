@extends('layout')

@section('content')
<div class="container">
    <div class="rvow justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Xác minh địa chỉ email') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Một liên kết xác minh mới đã được gửi đến địa chỉ email của bạn') }}
                        </div>
                    @endif

                    {{ __('Trước khi tiếp tục, vui lòng kiểm tra bạn đã nhận được email xác minh tài khoản chưa') }}
                    {{ __('Nếu bạn chưa nhận được xác minh tài khoản') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click vào đây để yêu cầu gởi lại') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
