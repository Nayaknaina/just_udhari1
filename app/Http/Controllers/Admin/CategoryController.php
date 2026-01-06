<?php

namespace App\Http\Controllers\Admin ;

use App\Http\Controllers\Controller ;
use App\Models\Category ;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Validator ;
use Illuminate\Support\Facades\View ;
use Illuminate\Support\Str ;
use App\Rules\UniqueSlug;

class CategoryController extends Controller {

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = Category::orderBy('id', 'desc') ;

        if($request->shop_name) { $query->where('shop_name', 'like', '%' . $request->shop_name . '%'); }

        $categories = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('admin.categories.disp', compact('categories'))->render();
            return response()->json(['html' => $html]);

        }

        return view('admin.categories.index',compact('categories'));

    }

    public function show(Request $request , $id) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = Category::where('category_level',$id)->orderBy('id', 'desc') ;

        if($request->name) { $query->where('name', 'like', '%' . $request->name . '%'); }

        $categories = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('admin.categories.disp', compact('categories'))->render();
            return response()->json(['html' => $html]);

        }

        return view('admin.categories.index',compact('categories','id'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function add($id) {

        return view('admin.categories.create',compact('id')) ;

    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request) {

        $slug = Str::slug($request->category_name, '-');

        $validator = Validator::make($request->all(), [
            'category_name' => 'required|unique:categories,name',
        ]) ;

        $request->merge(['slug' => $slug]) ;

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }

        $category = Category::create([

            'name' => $request->category_name,
            'category_level' => $request->category_level ,
            'slug' => $slug

        ]) ;

        if($category) {
            return response()->json(['success' => 'Data Saved successfully']);
        }else{
            return response()->json(['errors' => $validator->errors(),], 425) ;
        }

    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Category $productcategory) {

        $categories = Category::where('status',0)->whereNot('id',$productcategory->id)->get() ;

        return view('admin.categories.edit', compact('productcategory','categories')) ;

    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Category $productcategory) {

        $validator = Validator::make($request->all(), [

            'category_name' => 'required|unique:categories,name,'.$productcategory->id,

        ]) ;

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $category = $productcategory->update([

            'name' => $request->category_name,
            'parent' => $request->parent,

        ]);

        if($category) {

            return response()->json(['success' => 'Data Updated successfully','errors' =>'']);

        }else{

            return response()->json(['errors' => $validator->errors(),], 425) ;

        }

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Category $productcategory) {

        $category_level = $productcategory->category_level ; 
        $productcategory->delete() ;

        return redirect()->route('productcategories.show',$category_level)->with('success', 'Delete successfully.') ;

    }
}
