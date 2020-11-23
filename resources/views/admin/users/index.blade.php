@extends('layouts.admin')
@section('content')
<table class="table table-bordered" id="users">
    <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Address</th>
            <th scope="col">City</th>
            <th scope="col">Country</th>
            <th scope="col">Email</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            
            <td>
                <a href="" class="btn btn-sm btn-outline-primary ">Edit</a>
                <a href="" class="btn btn-sm btn-outline-danger ">Delete</a>

            </td>
        </tr>

    </tbody>
</table>
@endsection