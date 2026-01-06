<?php

namespace App\Http\Controllers\Vendor\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\EcommProduct;
use App\Models\Ecommerce\EcommProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Http\UploadedFile ;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EcommProductController extends Controller {

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = EcommProduct::orderBy('id', 'desc') ;

        if($request->EcommProduct_name) { $query->where('EcommProduct_name', 'like', '%' . $request->EcommProduct_name . '%'); }

        Shopwhere($query) ;
        $products = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.ecommerce.products.disp', compact('products'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.ecommerce.products.index',compact('products'));

    }

    public function create() {

        return view('vendors.ecommerce.products.create') ;

    }

    public function edit(EcommProduct $ecomproduct) {

        return view('vendors.ecommerce.products.edit', compact('ecomproduct')) ;

    }

    public function update(Request $request , EcommProduct $ecomproduct) {

        $slug = Str::slug($request->name).generateRandomAlphanumeric() ;

        $request->merge(['url' => $slug]) ;

        $validator = Validator::make($request->all(), [

            'name' => 'required',
            'url' => ['required',Rule::unique('ecomm_products')->where(function ($query) use ($request) {
                return $query->where('shop_id', auth()->user()->shop_id );
            }) ],
            'description' => 'required',
            'sr_images' => 'image|mimes:jpeg,png,jpg|max:1024',
            // 'more_images' => 'required',
            'more_images.*' => 'image|mimes:jpeg,png,jpg|max:1024',

        ], [
            'name.required' => 'Product Name is required' ,
            'url.required' => 'Product Url is required' ,
            'sr_images.required' => 'Thumbnail Image is required' ,
            'more_images.required' => 'Ptoduct Images is required'
        ]) ;

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        if ($request->hasFile('sr_images')) {

            $file = $request->file('sr_images') ;
            $folder = 'ecom/products' ;
            $uniqueFileName = generateUniqueImageName($file, 'ecom/products');
            $file->move(public_path($folder), $uniqueFileName) ;
            $request->merge(['thumbnail_image' => $uniqueFileName]) ;

        }else {

            $request->merge(['thumbnail_image' => $ecomproduct->thumbnail_image]) ;

        }

        $product = $ecomproduct->update([

            'name' => $request->name,
            'url' => $slug,
            'thumbnail_image' => $request->thumbnail_image,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'branch_id' =>auth()->user()->branch_id ,
            'shop_id' =>auth()->user()->shop_id ,

        ]) ;

        if ($request->hasFile('more_images')) {

            foreach ($request->file('more_images') as $key => $image) {

                $file = $image ;
                $folder = 'ecom/products' ;
                $uniqueFileName1 = generateUniqueImageName($file, $folder) ;
                $file->move(public_path($folder), $uniqueFileName1) ;

                $request->merge(['images' => $uniqueFileName1]) ;

                EcommProductImage::create([

                    'images' => $request->images,
                    'product_id' => $product->id,

                ]);

            }

        }

        if($product) {

            return response()->json(['success' => 'Updated Successfully']);

        }else{

            return response()->json(['errors' => 'Updated Failes'], 425);

        }

    }

    public function show(EcommProduct $ecomproduct) {

        return view('vendors.ecommerce.products.show', compact('ecomproduct')) ;

    }

}
