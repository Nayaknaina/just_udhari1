<?php

namespace App\Http\Controllers\Vendor\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\EcommProductImage;
use Illuminate\Http\Request;

class EcommProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request , $id) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = EcommProductImage::where('product_id',$id)->orderBy('id', 'desc') ;
        $images = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.ecommerce.products.images.disp', compact('images'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.ecommerce.products.images.index',compact('images'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EcommProductImage $images)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EcommProductImage $images)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(EcommProductImage $image){

        $image->delete() ;
        return redirect()->route('images.index')->with('success', 'Delete successfully.');

    }
}
