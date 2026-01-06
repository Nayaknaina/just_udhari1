<?php

namespace App\Http\Controllers\Vendor\Ecommerce;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Http\UploadedFile ;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Category;
use App\Models\Ecommerce\EcommProduct;
use App\Models\Ecommerce\EcommProductImage;

class StockController extends Controller {

    /**
     * Display a listing of the resource.
     */

    function __construct() {

        //$this->middleware('module.permission:Ecommerce Portal', ['only' => ['index']]);
        //$this->middleware('module.permission:Ecommerce Product', ['only' => ['index','show']]);

    }

    public function index_(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);
        
        $query = Stock::where('ecom_product','0')->where('item_type','genuine') ;
        Shopwhere($query) ;
		$query->orderBy('id', 'desc');
        if($request->stock_name) { $query->where('product_name', 'like', '%' . $request->Stock_name . '%'); }
        if($request->type){
                $cat_name = ucfirst($request->type);
                $cat_row = Category::where('name',$cat_name)->first();
            // $type_arr = explode('_',$request->type);
            // if($type_arr[0]!='artificial'){
            //     $cat_id = Category::where('name',$type_arr[1])->pluck('id');
            //     $query->where('category_id',$cat_id);
            // }
            //$query->where('item_type',$type_arr[0]);
            if($cat_row){
                $query->where('category_id',$cat_row->id);

            }
		}

        $stocks = $query->paginate($perPage, ['*'], 'page', $currentPage);
        
        if ($request->ajax()) {

            $html = view('vendors.ecommerce.stocks.disp', compact('stocks'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $stocks,'type'=>1])->render();
            return response()->json(['html' => $html,'paging'=>$paging]);

        }

        return view('vendors.ecommerce.stocks.index',compact('stocks'));

    }

	public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);
        
        $query = Stock::where('ecom_product','0')->where('available','>',0)->whereIn('item_type',['genuine','artificial'])->orderBy('id', 'desc') ;

        if($request->stock_name) { $query->where('product_name', 'like', '%' . $request->Stock_name . '%'); }
        if($request->type){
            if($request->type=='artificial'){
                $query->where('item_type',$request->type);
            }else{
                $cat_name = ucfirst($request->type);
                $cat_row = Category::where('name',$cat_name)->first();
                if($cat_row){
                    $query->where('category_id',$cat_row->id);
    
                }
            }
        }
        Shopwhere($query) ;

        $stocks = $query->paginate($perPage, ['*'], 'page', $currentPage);
        
        if ($request->ajax()) {

            $html = view('vendors.ecommerce.stocks.disp', compact('stocks'))->render();
            $paging = view('layouts.theme.datatable.pagination', ['paginator' => $stocks,'type'=>1])->render();
            return response()->json(['html' => $html,'paging'=>$paging]);

        }

        return view('vendors.ecommerce.stocks.index',compact('stocks'));

    }

    public function create() {

        return view('vendors.ecommerce.stocks.create') ;

    }

    public function edit(Stock $ecomstock) {

        return view('vendors.ecommerce.stocks.edit', compact('ecomstock')) ;

    }

    /*public function store(Request $request) {
		
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
            'rate.required'=>'Product Rate required !',
            'rate.numeric'=>'Product Rate must be Numeric !',
            'description.required'=>'Description Required !',
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
            'strike_rate'=>$request->strike,
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
		$stock->ecom_product = '1' ;
		$stock->update() ;
		
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
	
	/*public function store(Request $request) {
        
		
		
        $slug = Str::slug($request->name).generateRandomAlphanumeric() ;

        $request->merge(['url' => $slug]) ;

        $rules['name'] = 'required';
        $rules['url'] = ['required',Rule::unique('ecomm_products')->where(function ($query) use ($request) {
            return $query->where('shop_id', auth()->user()->shop_id );
        }) ];
        $rules['description'] = 'required';
        $rules['sr_images'] = 'required|image|mimes:jpeg,png,jpg|max:1024';
        $rules['more_images'] = 'required';
        $rules['more_images.*'] = 'image|mimes:jpeg,png,jpg|max:1024';
        $rules['rate_apply'] = 'required';
        $rules['rate'] = 'required|numeric';
        $rules['strike'] = 'nullable|numeric';

		$msgs['strike.numeric'] = 'Strike Rate must be numeric !';
        $msgs['rate.required'] = 'Product Rate is required !';
        $msgs['rate.numeric'] = 'Rate must be numeric !';
        $msgs['name.required'] = 'Product Name is required !';
        $msgs['description.required'] = 'Product Description required !';
        $msgs['url.required'] = 'Product Url is required !' ;
        $msgs['sr_images.required'] = 'Thumbnail Image is required !';
        $msgs['more_images.required'] = 'Product Images is required !';
        $msgs['rate_apply.required'] = 'Please Provide Applicable Rate !';

        $validator = Validator::make($request->all(),$rules,$msgs);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }
		//print_r($request->all());
		//exit();
        if ($request->hasFile('sr_images')) {

            $file = $request->file('sr_images') ;
            $folder = 'ecom/products' ;
            $uniqueFileName = generateUniqueImageName($file, 'ecom/products');
            $file->move(public_path($folder), $uniqueFileName) ;
            $request->merge(['thumbnail_image' => $uniqueFileName]) ;

        }else {

            $request->merge(['thumbnail_image' => '']) ;

        }

        $input_array = [
            'name' => $request->name,
            // 'rate' => $request->rate,
            // 'strike_rate'=>$request->strike,
            'url' => $slug,
            'thumbnail_image' => $request->thumbnail_image,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'stock_id' => $request->stock_id,
			'strike_rate'=>$request->strike,
            'branch_id' =>auth()->user()->branch_id ,
            'shop_id' =>auth()->user()->shop_id ,
        ];
        $stock = Stock::find($request->stock_id) ;
        if($request->rate=='yes'){
            $input_array['rate'] = $request->rate;
        }else{
            $input_array['rate'] = $stock->rate;
        }
        $product = EcommProduct::create($input_array) ;

        $stock->ecom_product = 1 ;
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

    }*/
	
	public function store(Request $request) {
        
        $slug = Str::slug($request->name).generateRandomAlphanumeric() ;

        $request->merge(['url' => $slug]) ;

        $rules['name'] = 'required';
        $rules['url'] = ['required',Rule::unique('ecomm_products')->where(function ($query) use ($request) {
            return $query->where('shop_id', auth()->user()->shop_id );
        }) ];
        $rules['description'] = 'required';
        $rules['sr_images'] = 'required|image|mimes:jpeg,png,jpg|max:1024';
        $rules['more_images'] = 'required';
        $rules['more_images.*'] = 'image|mimes:jpeg,png,jpg|max:1024';
        $rules['rate_apply'] = 'required';
        $rules['rate'] = 'required|numeric';
        $rules['strike'] = 'nullable|numeric';

        $msgs['strike.numeric'] = 'Strike Rate must be numeric !';
        $msgs['rate.required'] = 'Sell Price is required !';
        $msgs['rate.numeric'] = 'Sell Price must be numeric !';
        $msgs['name.required'] = 'Product Name is required !';
        $msgs['description.required'] = 'Product Description required !';
        $msgs['url.required'] = 'Product Url is required !' ;
        $msgs['sr_images.required'] = 'Thumbnail Image is required !';
        $msgs['more_images.required'] = 'Product Images is required !';
        $msgs['rate_apply.required'] = 'Please Provide Applicable Rate !';

        $validator = Validator::make($request->all(),$rules,$msgs);

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

        $input_array = [
            'name' => $request->name,
            // 'rate' => $request->rate,
            // 'strike_rate'=>$request->strike,
            'url' => $slug,
            'thumbnail_image' => $request->thumbnail_image,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'stock_id' => $request->stock_id,
            'strike_rate'=>$request->strike,
            'branch_id' =>auth()->user()->branch_id ,
            'shop_id' =>auth()->user()->shop_id ,
        ];
        $stock = Stock::find($request->stock_id) ;
        if($request->rate_apply=='yes'){
            $input_array['rate'] = $request->rate;
        }else{
            $input_array['rate'] = $stock->rate;
        }
        $product = EcommProduct::create($input_array) ;

        $stock->ecom_product = 1 ;
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
