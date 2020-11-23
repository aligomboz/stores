@extends('layouts.admin')
@section('content')
<title>@section('title' , 'Product')</title>
@include('admin._alert')
<div class="d-flex">
<h1 class="h3 mb-4 text-gray-800">@lang('Products'){{$locale}}</h1>
    <div class="ml-auto">
        <a class="btn btn-sm btn-outline-success" href="{{route('products.create')}}">Create New</a>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>price</th>
            <th>img</th>
            <th>Created_AT</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{$product->name}}</td>
            {{--<td>{{$product->category_name}}</td> هاد تاع الجوين--}}
            <td>{{$product->category->name}}</td>
            <td>{{$product->price}}</td>
            <td><img src="{{asset('/images/products/'.$product->img)}}" alt="img" height="70"></td>
            <td>{{$product->created_at}}</td>
            <td>{!!$product->description!!}</td>
            <td>
               {{--اختصار @if (Gate::allows('products.edit'))--}}
               @can('update' , $product)
                 <a href="{{route('products.edit' , $product->id)}}" class="btn btn-sm btn-outline-success">Edit</a>
                @endcan
               @can('delete' , $product)
                <form class="d-inline-block" action="{{route('products.destroy' ,$product->id)}}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-sm btn-danger delete">Delete</button>
                </form>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>

</table>
{{$products->links()}}

@endsection
