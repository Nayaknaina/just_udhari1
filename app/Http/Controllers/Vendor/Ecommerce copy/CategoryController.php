<?php

namespace App\Http\Controllers\Vendor\Ecommerce ;

use App\Http\Controllers\Controller ;
use App\Models\Category ;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Validator ;
use Illuminate\Support\Facades\View ;
use Illuminate\Support\Str ;
use App\Rules\UniqueSlug;

class CategoryController extends Controller {

    public function show(Request $request , $id) {

        $perPage = $request->input('entries') ; 
        $currentPage = $request->input('page', 1);

        $query = Category::where('category_level',$id)->orderBy('id', 'desc') ;

        if($request->name) { $query->where('name', 'like', '%' . $request->name . '%'); }

        $categories = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.settings.categories.disp', compact('categories'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.settings.categories.index',compact('categories','id')); 

    }

}
