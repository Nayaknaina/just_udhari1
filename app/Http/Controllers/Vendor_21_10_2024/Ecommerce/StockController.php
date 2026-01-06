<?php

namespace App\Http\Controllers\Vendor\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Ecommerce\EcommProduct;
use App\Models\Ecommerce\EcommProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Http\UploadedFile ;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StockController extends Controller {

    /**
     * Display a listing of the resource.
     */

    function __construct() {

        $this->middleware('module.permission:Ecommerce Portal', ['only' => ['index']]);
        $this->middleware('module.permission:Ecommerce Product', ['only' => ['index','show']]);

    }

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = Stock::where('ecom_product',1)->orderBy('id', 'desc') ;

        if($request->Stock_name) { $query->where('Stock_name', 'like', '%' . $request->Stock_name . '%'); }

        Shopwhere($query) ;

        $stocks = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.ecommerce.stocks.disp', compact('stocks'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.ecommerce.stocks.index',compact('stocks'));

    }

    public function create() {

        return view('vendors.ecommerce.stocks.create') ;

    }

    public function edit(Stock $ecomstock) {

        return view('vendors.ecommerce.stocks.edit', compact('ecomstock')) ;

    }

    public function store(Request $request) {

        $slug = Str::slug($request->name).generateRandomAlphanumeric() ;

        $request->merge(['url' => $slug]) ;

        $validator = Validator::make($request->all(), [

            'name' => 'required',
            'rate' => 'required|numeric',
            'url' => ['required',Rule::unique('ecomm_products')->where(function ($query) use ($request) {
                return $query->where('shop_id', auth()->user()->shop_id );
            }) ],
            'description' => 'required',
            'sr_images' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'more_images' => 'required',
            'more_images.*' => 'image|mimes:jpeg,png,jpg|max:1024',

        ], [
            'name.required' => 'Product Name is required' ,
            'url.required' => 'Product Url is required' ,
            'sr_images.required' => 'Thumbnail Image is required' ,
            'more_images.required' => 'Product Images is required'
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

            $request->merge(['thumbnail_image' => '']) ;

        }

        $product = EcommProduct::create([

            'name' => $request->name,
            'rate' => $request->rate,
            'url' => $slug,
            'thumbnail_image' => $request->thumbnail_image,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'stock_id' => $request->stock_id,
            'branch_id' =>auth()->user()->branch_id ,
            'shop_id' =>auth()->user()->shop_id ,

        ]) ;

        $stock = Stock::find($request->stock_id) ;
        $stock->ecom_product = 0 ;
        $stock->save() ;

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

}
