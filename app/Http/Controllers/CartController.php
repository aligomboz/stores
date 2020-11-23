<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();
        //بدي اجيب السلة لشخص واحد لهيك وضع الشرط
        $cart = Cart::with('product')
        ->where('id' , $this->getCartId())
/*
        ->when($user_id , function($query , $user_id){
            $query->where('user_id' , $user_id)->orWhereNull('user_id');
        })
*/
        ->get();
        return view('cart' , [
            'cart'=>$cart,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|int|exists:products,id',
            'quntity' => 'int|min:1',
        ]);

        $product = Product::findOrFail($request->post('product_id'));
        $quntity = $request->post('quntity' , 1); //ازا كان مش موجود حط قيمة 1
/*
        $cart =  Cart::where([
            'user_id'=>Auth::id(),
            'product_id' => $product->id,
        ])->first();
        if($cart){
           // $cart->increment('quntity' , $quntity); //زودلي
           Cart::where([
            'user_id'=>Auth::id(),
            'product_id' => $product->id,
        ])->increment('quntity' , $quntity);
        }else {
            Cart::Create([
                'user_id'=>Auth::id(),
                'product_id' => $product->id,
            
                'price' =>$product->price, 
                'quntity'=> $quntity,
            ]);
        }
        */
        
        Cart::updateOrCreate([
            'id'=>$this->getCartId(),
            'user_id'=>Auth::id(), // بس الي بدي اضيفو 
            'product_id' => $product->id,
        ] , [
            'price' =>$product->price, 
            'quntity'=> DB::raw("quntity + $quntity"),//الي بدي اضيفو و يتغير
        ]);
        return redirect()->route('cart')
        ->with('success' , __('Product :name added to cart !!') , ['name' =>$product->name]);
    }

    protected function getCartId(){
        $request = request();
        $id = $request->cookie('cart_id'); 
        if(!$id){
            //id ينشا 
            $uuid = Uuid::uuid1();
            $id = $uuid->toString();
            Cookie::queue(Cookie::make('cart_id' , $id , 43800)); //cookieهاد ينشا 
        }
        return $id;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
