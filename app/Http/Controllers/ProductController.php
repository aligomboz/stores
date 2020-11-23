<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Product $product){
        //dd($product);
       // $product = Product::findOrFail($id);
        return view('products.show' , [
            'product' =>$product
        ]);
    }
}
