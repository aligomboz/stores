@extends('layouts.front')
@section('content')
@if (session()->has('success'))
<div class="alert alert-danger">
    {{session()->get('success')}}
</div>
@endif
<div class="ps-content pt-80 pb-80">
    <div class="ps-container">
        <div class="ps-cart-listing">
            <table class="table ps-cart__table">
                <thead>
                    <tr>
                        <th>All Products</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $total = 0
                    @endphp
                    @foreach ($cart as $item)
                    <tr>
                        <td><a class="ps-product__preview" href="{{route('show.product' , [$item->product_id])}}"><img
                                    class="mr-15" width="66" src="{{asset('/images/products/'.$item->product->img)}}" alt="">
                                {{$item->product->name}}</a></td>
                        <td>{{$item->price}}</td>
                        <td>
                            <div class="form-group--number">
                                <button class="minus"><span>-</span></button>
                                <input class="form-control" type="text" value="{{$item->quntity}}">
                                <button class="plus"><span>+</span></button>
                            </div>
                        </td>
                        <td>{{$item->price * $item->quntity}}</td>
                        <td>
                            <div class="ps-remove"></div>
                        </td>
                    </tr>
                    @php
                    $total +=$item->price * $item->quntity
                    @endphp
                    @endforeach

                </tbody>
            </table>
            <div class="ps-cart__actions">
                <div class="ps-cart__promotion">
                    <div class="form-group">
                        <div class="ps-form--icon"><i class="fa fa-angle-right"></i>
                            <input class="form-control" type="text" placeholder="Promo Code">
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="ps-btn ps-btn--gray">Continue Shopping</button>
                    </div>
                </div>
                <div class="ps-cart__total">
                <h3>Total Price: <span> {{$total }}</span></h3><a class="ps-btn" href="{{route('checkout')}}">Process to
                        checkout<i class="ps-icon-next"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
