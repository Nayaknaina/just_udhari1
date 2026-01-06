<?php

namespace App\Http\Controllers\Vendor\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\EcommProduct;
use App\Models\Ecommerce\EcommProductImage;
use App\Models\StockCategory ;
use Illuminate\Support\Facades\DB;
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

        if($request->name) { $query->where('name', 'like', '%' . $request->name . '%'); }

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

    /*public function update(Request $request , EcommProduct $ecomproduct) {
		
        $slug = Str::slug($request->name).generateRandomAlphanumeric() ;

        $request->merge(['url' => $slug]) ;

        $validator = Validator::make($request->all(), [

            'name' => 'required',
            'url' => ['required',Rule::unique('ecomm_products')->where(function ($query) use ($request) {
                return $query->where('shop_id', auth()->user()->shop_id );
            }) ],
            'rate'=>'required',
            'description' => 'required',
            'sr_images' => 'image|mimes:jpeg,png,jpg|max:1024',
            // 'more_images' => 'required',
            'more_images.*' => 'image|mimes:jpeg,png,jpg|max:1024',

        ], [
            'rate.required'=>'Rate Required !',
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
            'rate'=>$request->rate,
            'strike_rate'=>$request->strike,
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

    }*/
	
	public function update(Request $request , EcommProduct $ecomproduct) {
        // print_r($request->all());
        // exit();
        $slug = Str::slug($request->name).generateRandomAlphanumeric() ;

        $request->merge(['url' => $slug]) ;

        $rules['name'] = 'required';
        $rules['url'] = ['required',Rule::unique('ecomm_products')->where(function ($query) use ($request) {
            return $query->where('shop_id', auth()->user()->shop_id );
        }) ];
        $rules['description'] = 'required';
        //$rules['more_images'] = 'required';
        $rules['more_images.*'] = 'nullable|image|mimes:jpeg,png,jpg|max:1024';
        $rules['rate_apply'] = 'required';
        $rules['stock_id'] = 'required';
        $rules['strike'] = 'nullable|numeric';
        if($ecomproduct->thumbnail_image==""){
            $rules['sr_images'] = 'required|image|mimes:jpeg,png,jpg|max:1024';
            $msgs['sr_images.required'] = 'Thumbnail Image is required !';
        }

        $msgs['name.required'] = 'Product Name is required !';
        $msgs['description.required'] = 'Product Description required !';
        $msgs['url.required'] = 'Product Url is required !' ;
        //$msgs['more_images.required'] = 'Product Images is required !';
        $msgs['rate_apply.required'] = 'Please Provide Applicable Rate !';
        $msgs['strike.numeric'] = 'Strike Rate must be numeric !'; 

        if($request->rate_apply=='yes'){
            $rules['rate'] = 'required|numeric';
            $msgs['rate.required'] = 'Product Rate is required !';
            $msgs['rate.numeric'] = 'Rate must be numeric !';
        }

        $validator = Validator::make($request->all(),$rules,$msgs);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }elseif($ecomproduct->id == $request->stock_id){
            $old_image = $ecomproduct->thumbnail_image;
            $new_img = false;
            if ($request->hasFile('sr_images')) {
                
                $file = $request->file('sr_images') ;
                $folder = 'ecom/products' ;
                $uniqueFileName = generateUniqueImageName($file, 'ecom/products');
                $file->move(public_path($folder), $uniqueFileName) ;
                $request->merge(['thumbnail_image' => $uniqueFileName]) ;
                $new_img = true;
            }else {
                $request->merge(['thumbnail_image' => $ecomproduct->thumbnail_image]) ;
            }
    
            $input_array = [
                'name' => $request->name,
                'url' => $slug,
                // 'rate'=>$request->rate,
                // 'strike_rate'=>$request->strike,
                'thumbnail_image' => $request->thumbnail_image,
                'description' => $request->description,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'strike_rate' => $request->strike,
                'branch_id' =>auth()->user()->branch_id ,
                'shop_id' =>auth()->user()->shop_id ,

            ];

            if($request->rate_apply=='yes'){
                $input_array['rate'] = $request->rate;
            }else{
                $input_array['rate'] = $ecomproduct->stock->rate;
            }
            $product = $ecomproduct->update($input_array) ;
            if($new_img){
                @unlink(asset('ecom/products/'.$old_image));
            }
            if ($request->hasFile('more_images')) {
    
                foreach ($request->file('more_images') as $key => $image) {
    
                    $file = $image ;
                    $folder = 'ecom/products' ;
                    $uniqueFileName1 = generateUniqueImageName($file, $folder) ;
                    $file->move(public_path($folder), $uniqueFileName1) ;
    
                    $request->merge(['images' => $uniqueFileName1]) ;
    
                    EcommProductImage::create([
    
                        'images' => $request->images,
                        'product_id' => $ecomproduct->id,
    
                    ]);
    
                }
    
            }
    
            if($product) {
    
                return response()->json(['success' => 'Updated Successfully']);
    
            }else{
    
                return response()->json(['errors' => 'Updated Failes'], 425);
    
            }
        }else{
            return response()->json(['errors' => 'Invalid Action !'], 425);
        }


    }

    public function show(EcommProduct $ecomproduct) {

        return view('vendors.ecommerce.products.show', compact('ecomproduct')) ;

    }
	
	/**
     * Remove the specified resource from storage.
     */

   public function destroy(EcommProduct $ecomproduct) {
        DB::beginTransaction();
        try{
        $old_image = $ecomproduct->images;
        $gal_imgs = $ecomproduct->galleryimages()->pluck('images');
        if($gal_imgs->count()>0){
            foreach($gal_imgs as $gk=>$gall){
                @unlink("ecom/products/{$gall}");
            }
        }
        @unlink("ecom/products/{$old_image}");
        //dd($ecomproduct->stock);
        $ecomproduct->stock->ecom_product = "0";
        $ecomproduct->stock->update();
        $ecomproduct->delete() ;
        DB::commit();
        //return redirect()->route('catalogues.index')->with('success', 'Delete successfully.');
        }catch(PDOException $e){
            return response()->json(['error'=>"Deletion Failed ".$e->getMessage()]);
        }

    }
    
    /*public function deletegallery(EcommProductImage $ecommproductimage){
        $old_image = $ecommproductimage->images;
        if($ecommproductimage->delete()){
            @unlink("ecom/cataloge/{$old_image}");
            return response()->json(['success'=>'Gallery Image Deleted !']);
        }else{
            return response()->json(['error'=>'Gallery Image Deletion Failed !']);
        }
    }*/
}
