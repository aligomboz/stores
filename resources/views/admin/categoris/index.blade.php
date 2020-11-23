@extends('layouts.admin')
@section('content')
<title>@section('title' , 'categoris')</title>
@include('admin._alert')
<div class="d-flex">
    <h1 class="h3 mb-4 text-gray-800">Categoris</h1>
    <div class="ml-auto">
        <a class="btn btn-sm btn-outline-success" href="{{asset('admin/category/create')}}">Create New</a>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Parent</th>
            <th>Status</th>
            <th>Created_AT</th>
            <th>Count Products</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($categoris as $category)
        <tr>
            <td>{{$category->name}}</td>
            {{--<td>{{$category->parent_name}}</td>--}}

            <td>{{$category->parent->name}}</td>

            <td>{{$category->status}}</td>
            <td>{{$category->created_at}}</td>
            <td>{{$category->products_count}}</td>
            <td>
                <a href="{{route('category.show' , $category->id)}}" class="btn btn-sm btn-outline-success">Show</a>

                <a href="{{route('category.edit' , $category->id)}}" class="btn btn-sm btn-outline-success">Edit</a>
                <form class="d-inline-block" action="{{route('category.destroy' , $category->id)}}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-sm btn-danger delete">Delete</button>
                </form>
            </td>

        </tr>
        @endforeach
    </tbody>

</table>
{{$categoris->links()}}

@endsection
