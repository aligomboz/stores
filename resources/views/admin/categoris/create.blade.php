@extends('layouts.admin')
@section('content')
<title>@section('title' , 'create') </title>
<h1 class="h3 mb-4 text-gray-800">Create Category</h1>
<form action="{{route('category.store')}}" method="POST">
@include('admin.categoris._form' ,
[
    'categoris' => new App\Category
])
</form>
@endsection
