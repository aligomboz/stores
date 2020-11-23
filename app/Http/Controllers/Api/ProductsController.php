<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductImg;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProductsController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:api')->except('index' , 'show');
        $this->middleware('auth:sanctum')->except('index' , 'show');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request = request();
        $category_id = $request->input('category_id');
        $keyword = $request->input('q');

        $products = Product::when($category_id , function($query , $category_id){
            return $query->where('category_id' , $category_id);

        })->when($keyword , function($query , $keyword){
            return $query->where('name' , 'LIKE' , "%$keyword%")
                         ->orWhere('description' , 'LIKE' , "%$keyword%");
                         
        })->with('category')->paginate();
        return $products;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        //  dd($request->post('tags'));
        $this->getValidate($request);
        $img_path = null;
        if($request->hasFile('img') && $request->file('img')->isValid()){
            $img = $request->file('img');
            $img_path= $img->store('/' , 'products');  //خزن في المجلد الرئيسي الي اسمو برودكت
        }
        $data = $request->all();
        $data['img'] = $img_path;
        $data['description'] = strip_tags($data['description'],'<p><h1><h2><img>'); //هادالكود حماية
        try{
            DB::beginTransaction();
            $products = Product::create($data);
            $this->Gallery($request , $products);
            $this->GetTags($request ,$products);
            DB::commit();
            }
        catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'code' => 422,
                'message' => $e->getMessage(),
            ], 422);
        }

        return $products;
    }
    protected function Gallery(Request $request , $products){
        if($request->hasFile('gallery')){ //هاد برجع اراي اوف اوبجيكت
            $imgs=$request->file('gallery');
            foreach($imgs as $img){
                if($img->isValid()){
                $img_path = $img->store('gallery' , 'products');
                ProductImg::create([
                    'product_id' => $products->id,
                    'imgPath' => $img_path
                ]);
                }
            }
        }
    }
    protected function GetTags(Request $request , $products){
        $products_tags = [];
        $tags = explode(',' , $request->post('tags'));
        foreach($tags as $tag){
            $tag= trim($tag);
            $tagModel = Tag::firstOrCreate([
                'name' =>$tag,
            ]);
            $products_tags[] = $tagModel->id; 
        }
        $products->tags()->sync($products_tags);
    }
    protected function getValidate (Request $request){
        return $request->validate([
            'name' => 'required|string|max:100|min:5',
            'category_id' => 'required|int|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'img'=>'image',
            'gallery.*' =>'image',
            'description' =>'required',
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'code' => 404,
                'message' => 'Product not found.'
            ], 404);
        }
        //return $product->image_url;
        return $product->load('category', 'imges', 'tags');
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
        if($request->user()->tokenCan('products.create')){
            return 'UPDATEPRODUCTS';
        }
        return 'NOT AUTORISED';
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
