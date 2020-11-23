<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // return DB::table('categories')->orderBy('name' , 'ASC')->get();//بدي احيب البيانات
       //return Category::get(['name']);
      // DB::table('categories')-> في حال بدي استخدم
    /*
      $categoris= Category::leftJoin('categories as parents' , 'parents.id' , '=' , 'categories.parent_id')
       ->select([
           'categories.*',
           'parents.name as parent_name'
       ])->latest()->paginate(5);

        Category::leftJoin('products' , 'products.category_id' , '=' , 'categories.id')
       ->select([
           'categories.id',
           'categories.name',
           DB::raw('COUNT(store_products.id) as product_count')
       ])->groupBy([
        'categories.id',
        'categories.name',
       ])->get();
       */
      $categoris = Category::with('parent')->withCount('products')->latest()->paginate(5);
        return view('admin.categoris.index' ,[
           'categoris' =>$categoris

           //DB::table('categories')->where('parent_id' , 1)->get()
       ]);
    }
    //get() / collection kind if Iteretable return arry

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('Category.create');
        return view('admin.categoris.create');
        /*
        return Category::create([
            'name' => 'xdres',
            'parent_id' => 2,
            'status' => 'draft',
           
        ]);*/

       /* DB::table('categories')->insert([
            'name' => 'fares',
            'parent_id' => 1,
            'status' => 'draft',
           
        ]);*/
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('Category.create');

        $this->check_validate($request);
        $category= Category::create([
            'name' =>$request->name,
            'parent_id'=>$request->parent_id,
            'status' =>$request->status,
        ]);
        return redirect()->
        route('category.index')
        ->with('success' , "Category \"{$category->name}\" Created!!");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        /*
        return
            [   
              'data'=> DB::table('categories')->where('id' , '=' ,$id)->first(),
            ];
            */
            return view('admin.categoris.show' ,
        [
            'category' =>$category
        ]);

    }
 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categoris.edit',[
            'categoris' =>$category
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
    {
        //Category::where('id' , $id)->update($request->all());
        // $this->check_validate($request);
         $validator = Category::getValidator($request->all() , $id);
        $validator->validate();
        /*
        if($validator->fails()){
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }*/
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return redirect()
        ->route('category.index')
        ->with('success' , "Category \"{$category->name}\" Updated!!");
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      //  Category::where('id' ,$id)->delete();

        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()
        ->back()
        ->with('success' , "Category \"{$category->name}\" Deleted!!");
    }

    protected function check_validate(Request $request){
        $request->validate([
            'name' =>'required|string|max:255|min:3|unique:categories,name',
            'parent_id' => 'nullable|int|exists:categories,id',
            'status' => 'required|in:published,draft'
        ]);
    }
}
