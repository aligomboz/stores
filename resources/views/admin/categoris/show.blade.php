@extends('layouts.admin')
@section('content')
<h2>sup-Category</h2>
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Created_AT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($category->children as $cate)
        <tr>
            <td>{{$cate->name}}</td>
            <td>{{$cate->created_at->diffForHumans()}}</td>
        </tr>
        @endforeach
    </tbody>

</table>
<h1>{{$category->name}}</h1>
<h4>Category Products</h4>
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Created_AT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($category->products as $product)
        <tr>
            <td>{{$product->name}}</td>
            <td>{{$product->price}}</td>

            <td>{{$product->created_at->diffForHumans()}}</td>
        </tr>
        @endforeach
    </tbody>

</table>
@endsection