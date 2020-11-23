@extends('layouts.admin')
@section('content')
<title>@section('title' , 'create') </title>
<h1 class="h3 mb-4 text-gray-800">Create Products</h1>
<form action="{{route('products.store')}}" method="POST" enctype="multipart/form-data">
    @include('admin.products._form' , [
        'products' =>new App\Product,
        'gallery' =>[],
    ]) {{--بعدت مودل فاضي--}}
</form>
@endsection