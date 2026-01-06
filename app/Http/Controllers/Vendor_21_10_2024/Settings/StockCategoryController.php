<?php

namespace App\Http\Controllers\Vendor\Settings;

use App\Http\Controllers\Controller;
use App\Models\StockCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


class StockCategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ; 
        $currentPage = $request->input('page', 1);

        $query = StockCategory::orderBy('id', 'desc') ;

        if($request->shop_name) { $query->where('shop_name', 'like', '%' . $request->shop_name . '%'); }

        $stockcategories = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.stockcategories.disp', compact('stockcategories'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.stockcategories.index',compact('stockcategories')); 

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

        $categories = StockCategory::where('status',0)->get() ;

        return view('vendors.stockcategories.create',compact('categories')) ;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        
        $validator = Validator::make($request->all(), [

            'category_name' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $stockcategory = StockCategory::create([

            'name' => $request->category_name,
            'parent' => $request->parent,
            'branch_id' =>auth()->user()->branch_id,
            'shop_id' =>auth()->user()->shop_id,

        ]);

        if($stockcategory) { 

            return response()->json(['success' => 'Data Saved successfully']);

        }else{

            return response()->json(['success' => 'Data Saved successfully']);

        }
            
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(StockCategory $stockcategory) {
 
        $categories = StockCategory::where('status',0)->whereNot('id',$stockcategory->id)->get() ;

        return view('vendors.stockcategories.edit', compact('stockcategory','categories')) ;

    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, StockCategory $stockcategory) {
     
        $validator = Validator::make($request->all(), [

            'category_name' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $stockcategory = $stockcategory->update([

            'name' => $request->category_name,
            'parent' => $request->parent,
            'branch_id' =>auth()->user()->branch_id,
            'shop_id' =>auth()->user()->shop_id,

        ]);

        if($stockcategory) { 

            return response()->json(['success' => 'Data Updated successfully']);

        }else{

            return response()->json(['success' => 'Data Updated Failed']);

        }
            
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(StockCategory $stockcategory) {

        $stockcategory->delete() ;
        return redirect()->route('stockcategories.index')->with('success', 'Delete successfully.');

    }
}
