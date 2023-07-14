@extends('layout')
@section('title')
	Thêm sinh viên - Quản Lý Sinh Viên
@endsection

@section('content')
<h1>Thêm sinh viên</h1>
<form action="{{route("students.import")}}" class="container-fluid" style="max-width: 500px" enctype="multipart/form-data" method="POST">
	@csrf
	<h1>Upload file excel</h1>
	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text">Upload</span>
		</div>
		<div class="custom-file">
			<input type="file" class="custom-file-input" id="excel" name="excel">
			<label class="custom-file-label" for="excel">Choose file</label>
		</div>
	</div>
	<div class="form-group" id="filename">
		
	</div>
	<div class="form-group text-right">
		<button type="submit" class="btn btn-primary">Submit</button>
	</div>
</form>
@endsection