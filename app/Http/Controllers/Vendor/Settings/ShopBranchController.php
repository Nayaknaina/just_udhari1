<?php

namespace App\Http\Controllers\Vendor\Settings;

use App\Http\Controllers\Controller;
use App\Models\ShopBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


class ShopBranchController extends Controller
{

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = ShopBranch::whereNotIn('branch_type',['0'])->orderBy('id', 'desc') ;

        Shopwhere($query) ;

        if($request->branch_name) { $query->where('branch_name', 'like', '%' . $request->branch_name . '%'); }

        $shopbranches = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.settings.shopbranches.disp', compact('shopbranches'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.settings.shopbranches.index',compact('shopbranches'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

        return view('vendors.settings.shopbranches.create') ;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'branch_name' => 'required',
            'incharge_name' => 'required',
            'mobile_no' => ['required', 'string', 'digits:10', 'regex:/^[0-9]+$/'],
            'address' => 'required',
            'state' => 'required',
            'district' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $shopbranch = ShopBranch::create([

            'branch_name' => $request->branch_name,
            'name' => $request->incharge_name,
            'mobile_no' => $request->mobile_no,
            'address' => $request->address,
            'state' => $request->state ,
            'district' => $request->district,
            'branch_type' =>1,
            'shop_id' =>auth()->user()->shop_id,

        ]);

        if($shopbranch) {
            return response()->json(['success' => 'Data Saved successfully']);
        }else{
            return response()->json(['errors' => 'Data Save failed'], 425) ;
        }

    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(ShopBranch $shopbranch) {

        return view('vendors.settings.shopbranches.edit', compact('shopbranch')) ;

    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, ShopBranch $shopbranch) {

        $validator = Validator::make($request->all(), [

            'branch_name' => 'required',
            'incharge_name' => 'required',
            'mobile_no' => ['required', 'string', 'digits:10', 'regex:/^[0-9]+$/'],
            'address' => 'required',
            'state' => 'required',
            'district' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $shopbranch = $shopbranch->update([

            'branch_name' => $request->branch_name,
            'name' => $request->incharge_name,
            'mobile_no' => $request->mobile_no,
            'address' => $request->address,
            'state' => $request->state ,
            'district' => $request->district,

        ]);

        if($shopbranch) {
            return response()->json(['success' => 'Data Saved successfully']);
        }else{
            return response()->json(['errors' => 'Data Save failed'], 425) ;
        }

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(ShopBranch $shopbranch) {

        $shopbranch->delete() ;
        return redirect()->route('shopbranches.index')->with('success', 'Delete successfully.');

    }
}
