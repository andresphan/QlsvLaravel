@extends('layout')
@section('title')
	Thêm sinh viên - Quản Lý Sinh Viên
@endsection

@section('content')
<h1>Thêm sinh viên</h1>
<form action="{{route("students.store")}}" method="POST">
    @csrf
    <div class="container">
        @include('error')
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label>Tên</label>
                    <input type="text" class="form-control {{$errors->has("name") ? "is-invalid": ""}}" placeholder="Tên của bạn" name="name" value="{{old("name")}}">
                </div>
                <div class="form-group">
                    <label>Birthday</label>
                    <input type="date" class="form-control {{$errors->has("birthday") ? "is-invalid": ""}}" placeholder="Ngày sinh của bạn" name="birthday" value="{{old("birthday")}}">
                </div>
                <div class="form-group">
                    <label>Chọn Giới tính</label>
                    <select class="form-control {{$errors->has("gender") ? "is-invalid": ""}}" id="gender" name="gender">
                        <option value="" >Vui lòng chọn giới tính</option>
                        <option value="0" {{old("gender") == 0 ? "selected": ""}}>Nam</option>
                        <option value="1" {{old("gender") == 1 ? "selected": ""}}>Nữ</option>
                        <option value="2" {{old("gender") == 2 ? "selected": ""}}>Khác</option>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-success" type="submit">Lưu</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection