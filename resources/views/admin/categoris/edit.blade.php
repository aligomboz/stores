@extends('layouts.admin')
@section('content')
<title>@section('title' , 'create') </title>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<h1 class="h3 mb-4 text-gray-800">Edit Category</h1>
<form action="{{route('category.update' , $categoris->id)}}" method="POST">
@method('put')
@include('admin.categoris._form')
</form>
@endsection