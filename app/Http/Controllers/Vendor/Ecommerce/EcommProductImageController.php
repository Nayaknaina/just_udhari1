<?php

namespace App\Http\Controllers\Vendor\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\EcommProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'gall_image'=>"required|image|mimes:jpeg,png,jpg|max:1024"
            ],[
                "gall_image.required"=>"First Select the Image to Upload !",
                "gall_image.image"=>"File Should be a Valid Image !",
                "gall_image.mimes"=>"Image should be in Valid Formate !",
                "gall_image.max"=>"Image Size exceeded 1 MB limit !",
            ]
        );
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errorss(),],422);
        }else{
            $file = $request->file('gall_image') ;
            $folder = 'ecom/products' ;
            $uniqueFileName = generateUniqueImageName($file, 'ecom/products');
            if($file->move(public_path($folder), $uniqueFileName)){
                EcommProductImage::create([
                    'images' => $uniqueFileName,
                    'product_id' => $request->prdct_id,
                ]);
                return response()->json(["success"=>"Image Succesfully Uploading !"]);
            }else{
                return response()->json(["errors"=>"Image Uploading failed !"]);
            }
            // $file->move(public_path($folder), $uniqueFileName) ;
            // $request->merge(['thumbnail_image' => $uniqueFileName]) ;
            // $new_img = true;
        }
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

    public function destroy(String $id){
        $ecommproductimage = EcommProductImage::find($id);
        //dd($ecommproductimage);
        $old_image = $ecommproductimage->images;
        if($ecommproductimage->delete()){
            @unlink("ecom/products/{$old_image}");
            //return response()->json(['success'=>'Gallery Image Deleted !']);
            //return redirect()->route('images.index')->with('success', 'Delete successfully.');
        }else{
            return response()->json(['error'=>'Gallery Image Deletion Failed !']);
        }

    }
}
