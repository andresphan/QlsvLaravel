@extends('layout')
@section('title')
	Quản Lý Sinh Viên
@endsection

@section('content')
<h1>Danh sách môn học</h1>
<a href="{{route("subjects.create")}}" class="btn btn-info">Add</a>
<form action="{{route("subjects.index")}}" method="GET">
	<label class="form-inline justify-content-end">Tìm kiếm: <input type="search" name="search" class="form-control" value="{{$search}}">
	<button class="btn btn-danger">Tìm</button>
	</label>
</form>
<table class="table table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>Mã MH</th>
			<th>Tên</th>
			<th>Số tín chỉ</th>
			<th colspan="2">Tùy Chọn</th>
		</tr>
	</thead>
	<tbody>
		@php
			$order = 0;
		@endphp
		@foreach ($subjects as $subject)
		@php
			$order++;
		@endphp
		<tr>
			<td>{{$order}}</td>
			<td>{{$subject->id}}</td>
			<td>{{$subject->name}}</td>
			<td>{{$subject->number_of_credit}}</td>
			<td><a href="{{route("subjects.edit", ["subject" => $subject->id])}}">Sửa</a></td>
			<td>
				<button class="btn btn-danger delete" url="{{route("subjects.destroy", ["subject" => $subject->id])}}">Xóa</button>
				
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
<div class="d-flex justify-content-end">
	{{$subjects->links()}}
</div>
<div>
	<span>Số lượng: {{$subjects->count()}}/{{$subjects->total()}}</span>
</div>
@endsection