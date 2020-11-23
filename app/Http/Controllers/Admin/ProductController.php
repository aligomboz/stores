<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Policies\ProductPolicy;
use App\Product;
use App\ProductImg;
use App\Tag;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Throwable;

class ProductController extends Controller
{
    /* في حال لو بدي اعملهم كلهم
    public function __construct()
    {
        $this->authorize(Product::class , 'product');
    }
    */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny' , Product::class);
      //  App::setLocale('ar');
        
        /*
        $products = Product::join('categories' , 'categories.id' ,'=' ,'products.category_id')
        ->select([
            'products.*',
            'categories.name as category_name'
        ])->latest()->paginate(5);
        */
        $products = Product::with('category')->paginate(5);//onlyTrashed
        $cart = request()->cookie('cart');
        return View::Make('admin.products.index' , [
            'products' =>$products,
            'locale' => App::getLocale(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create' , Product::class);
        return View::Make('admin.products.create');
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
        catch (Throwable $e){
           // DB::rollBack();
           return redirect()->route('products.index')->with('alert-error' , $e->getMessage());
            throw $e;
        }

        
        return redirect()->route('products.index')
        ->with('success' , "Product ({$products->name}) Created !!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gallery = ProductImg::findOrFail($id);
        $gallery->delete();
        Storage::disk('products')->delete($gallery->imgPath);
        return redirect()->back();
        /*
        $products = Product::findOrFail($id);
        return View::make('admin.products.show',[
            'products' => $products
        ]);*/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /*
        if(!Gate::allows('products.edit')){
            abort(403);
        };*/
       // Gate::authorize('products.edit');
       $products = Product::findOrFail($id);
//في حال كان عندي مودل باستخدم الاوبجكت تبعو
        $this->authorize('update' ,$products);

        $products = Product::findOrFail($id);
        $gallery = ProductImg::where('product_id' , $id)->get();
        return View::make('admin.products.edit',[
            'products' => $products,
            'gallery' => $gallery,
        ]);  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   /*
        if(!Gate::allows('products.edit')){
            abort(403);
        };
        */
       // Gate::authorize('products.edit');
        $products = Product::find($id); 
      //  dd($request->description);

        $this->authorize('update',$products);
        $this->getValidate($request);

        $this->authorize('update' ,$products);
        /*
        $data = [
            'name'=>$request->name,
            'price'=>$request->price,
            'category_id'=>$request->category_id,
            'description'=>$request->description,
        ];*/
        $data = [];
        $data= $request->except('img');

        if($request->hasFile('img') && $request->file('img')->isValid()){
            $imag = $request->file('img');
           if($products->img && Storage::disk('products')->exists($products->img)){ //1- في عندي سورة 
                                                                                    //2- افحص هل مسار السوورة موجود برج 

            $img_path = $imag->storeAs('/', basename($products->img) , 'products');
            }else{
                $img_path = $imag->store('/' , 'products');
            }
            $data['img'] = $img_path;
            
        }
        DB::beginTransaction();
        try{
            // return $data;
          $products->update($data);
        //   return $hazem;
            $this->Gallery($request , $products);
            $this->GetTags($request ,$products);

            DB::commit();
       }
        catch(Throwable $e){
        DB::rollBack();
        return redirect()->route('products.index')->with('alert-error' , $e->getMessage());
            throw $e;
        }

        return Redirect::route('products.index')
        ->with('success' , __('Product ({:product}) Edit !!', ['product' =>$products->name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request ,$id)
    {
        /*
        if(!Gate::allows('products.delete')){
            abort(403);
        };*/
          /*
        $respons = Gate::inspect('products.delete' , $products);//inspectبترجع الولاو و ديناي
        if(!$respons->allowed()){
            return abort(403 , $respons->message());
           // return Response::deny();//في حال بدي ارجع فولس 
           // return Response::allow();//في جال بدي ارجع ترو
        };//
        */
         $products = Product::find($id);
    //     $this->authorize('delete' ,$products);
    //     //Gate::authorize('products.delete' , $products);
      
    //     $gallery = ProductImg::where('product_id' , $id)->get();
    //    // $gallery->delete();//بسبب الاب انحذف لا يتم وضعها
             $products->delete();
   
    //     if($products->img){
    //      //   unlink(public_path('products/'.$products->img));//php
    //         Storage::disk('products')->delete($products->img);//laravel تحذف مسار السورة 
    //     }
    //     foreach($gallery as $gal){
    //         Storage::disk('products')->delete($gal->imgPath);
    //     }
        return Redirect::route('products.index')
       ->with('success' , "Product ({$products->name}) Deleted !!");

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
            /* هاد خزنت مباشرة في الجدول الوسيط 
            DB::table('products_tags')->insert([
                'product_id'=>$products->id,
                'tag_id'=>$tagModel->id
            ]);
            */
            $products_tags[] = $tagModel->id; 
        }
        $products->tags()->sync($products_tags);
    }/*
    protected function deleteGallery($id){
        $gallery = ProductImg::findOrFail($id);
        $gallery->delete();
        Storage::disk('products')->delete($gallery->imgPath);

        return redirect()->route('products.edit');
    }*/
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

    public function trash()
    {
        return view('admin.products.trash', [
            'products' => Product::onlyTrashed()->paginate(),
        ]);
    }

    public function restore(Request $request, $id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return Redirect::route('products.index')
            ->with('alert.success', "Product ({$product->name}) restored!");
    }

    public function forceDelete($id)
    {
        try{
        $products = Product::onlyTrashed()->findOrFail($id);
             $this->authorize('delete' ,$products);
             //Gate::authorize('products.delete' , $products);
          
             $gallery = ProductImg::where('product_id' , $id)->get();
            // $gallery->delete();//بسبب الاب انحذف لا يتم وضعها
                
                 $products->forceDelete();
       
             if($products->img){
              //   unlink(public_path('products/'.$products->img));//php
                 Storage::disk('products')->delete($products->img);//laravel تحذف مسار السورة 
             }
             foreach($gallery as $gal){
                 Storage::disk('products')->delete($gal->imgPath);
            }
            
             return Redirect::route('products.index')
            ->with('success' , "Product ({$products->name}) Deleted !!");
        }
        catch(QueryException $e){
            
        }
        
    }
}
