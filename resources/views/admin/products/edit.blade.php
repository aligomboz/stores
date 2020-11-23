@extends('layouts.admin')
@section('content')
<title>@section('title' , 'create') </title>
<h1 class="h3 mb-4 text-gray-800">Update Products</h1>
<form action="{{route('products.update' , $products->id)}}" method="POST" enctype="multipart/form-data">
   @method('put')
    @include('admin.products._form')
</form>

@endsection

