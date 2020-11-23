@extends('layouts.front')
@section('content')
<form id="addUserForm">
    @csrf
    <div class="form-group row">
        <label for="name" class="col-4 col-form-label">Name</label>
        <div class="col-8">
            <input id="name" name="firstName" placeholder="name" class="form-control here" type="text"
                value="{{old('name')}}" />
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label for="title" class="col-4 col-form-label">City</label>
        <div class="col-8">
            <input id="title" name="city" placeholder="city" class="form-control here" type="text"
                value="{{old('city')}}" />
            @error('city')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label for="title" class="col-4 col-form-label">Address</label>
        <div class="col-8">
            <input id="title" name="address" placeholder="address" class="form-control here" type="text"
                value="{{old('address')}}" />
            @error('address')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label for="title" class="col-4 col-form-label">Country</label>
        <div class="col-8">
            <input id="title" name="country" placeholder="country" class="form-control here" type="text"
                value="{{old('country')}}" />
            @error('country')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label for="name" class="col-4 col-form-label">Email</label>
        <div class="col-8">
            <input id="subTitle" name="email" placeholder="email" class="form-control here" type="email"
                value="{{old('email')}}" />
            @error('email')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label for="time" class="col-4 col-form-label">Phone</label>
        <div class="col-8">
            <input id="price" name="phone" placeholder="phone" class="form-control here" type="number"
                value="{{old('phone')}}" />
            @error('phone')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="time" class="col-4 col-form-label">Password</label>
        <div class="col-8">
            <input id="price" name="password" placeholder="password" class="form-control here" type="password" />
            @error('password')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="modal-footer">
        <button id="add" type="button" class="btn btn-primary">
            Save
        </button>
        <button type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>

    </div>
</form>
@endsection
