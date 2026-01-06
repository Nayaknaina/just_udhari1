<?php

namespace App\Http\Controllers\Vendor\Ecommerce;

use App\Http\Controllers\Controller ;
use App\Models\Cataloge ;
use App\Models\Category ;
use App\Models\CatalogeImage ;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Validator ;
use Illuminate\Support\Facades\View ;

class CatalogeController extends Controller
{

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ; 
        $currentPage = $request->input('page', 1);

        $query = Cataloge::orderBy('id', 'desc') ;

        if($request->shop_name) { $query->where('shop_name', 'like', '%' . $request->shop_name . '%'); }

        $catalogues = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.ecommerce.catalogues.disp', compact('catalogues'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.ecommerce.catalogues.index',compact('catalogues')); 

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create() {

        $metals = Category::where('category_level',1)->get() ; 
        $collections = Category::where('category_level',2)->get() ; 
        $categories = Category::where('category_level',3)->get() ; 

        return view('vendors.ecommerce.catalogues.create' , compact('metals' , 'collections' , 'categories') ) ;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'name' => 'required',
            'ct_images' => 'required',
            'weight' => 'required|max_decimal_places:3' , 
            // 'price' => 'required' , 
            'metals' => 'required' , 
            'collections' => 'required' , 
            'categories' => 'required' , 
            'short_order' => 'required' , 

        ], [

            'name.required' => 'Enter Cataloge Name ',
            'ct_images.required' => 'Enter Cataloge Image ',
            'weight.max_decimal_places' => 'The weight may not have more than 3 decimal places.',

        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        if ($request->hasFile('ct_images')) {

            $ct_images = time() . rand() . '.' . $request->ct_images->extension() ;
            $request->ct_images->move(public_path('ecom/cataloge'), $ct_images) ;
            $request->merge(['images' => $ct_images]) ;

        }

        $catalogue = Cataloge::create([

            'name' => $request->name ,
            'images' => $request->images ,
            'weight' => $request->weight ,
            'categoty_id' => $request->categories ,
            'short_order' => $request->short_order ,
            'branch_id' =>auth()->user()->branch_id ,
            'shop_id' =>auth()->user()->shop_id ,

        ]) ;

        if ($request->hasFile('mr_images')) {

            foreach ($request->file('mr_images') as $image) {

                $mr_image = time() . rand() . '.' . $image->extension() ;
                $image->move(public_path('ecom/cataloge'), $mr_image) ;
                $request->merge(['images' => $mr_image]) ;

                CatalogeImage::create([

                    'cataloge_id' => $catalogue->id,
                    'images' => $request->images,

                ]);
                
            }

        }

        $catalogue->categories()->attach($request->metals) ;
        $catalogue->categories()->attach($request->collections) ;
        $catalogue->categories()->attach($request->categories) ;

        if($catalogue) { 

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

    public function edit(Cataloge $catalogue) {

        $metals = Category::where('category_level',1)->get() ; 
        $collections = Category::where('category_level',2)->get() ; 
        $categories = Category::where('category_level',3)->get() ; 

        return view('vendors.ecommerce.catalogues.edit' , compact('catalogue','metals' , 'collections' , 'categories') ) ;

    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Cataloge $catalogue) {
     
        $validator = Validator::make($request->all(), [

            'name' => 'required',
            // 'ct_images' => 'required',
            'weight' => 'required|max_decimal_places:3' , 
            // 'price' => 'required' , 
            'metals' => 'required' , 
            'collections' => 'required' , 
            'categories' => 'required' , 
            'short_order' => 'required' , 

        ], [

            'name.required' => 'Enter Cataloge Name ',
            // 'ct_images.required' => 'Enter Cataloge Image ',
            'weight.max_decimal_places' => 'The weight may not have more than 3 decimal places.',

        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        if ($request->hasFile('ct_images')) {

            $ct_images = time() . rand() . '.' . $request->ct_images->extension() ;
            $request->ct_images->move(public_path('ecom/cataloge'), $ct_images) ;
            $request->merge(['images' => $ct_images]) ;

        }else {

            $request->merge(['images' => $catalogue->images]) ;

        }

        $cataloge = $catalogue->update([

            'name' => $request->name ,
            'images' => $request->images ,
            'weight' => $request->weight ,
            'categoty_id' => $request->categories ,
            'short_order' => $request->short_order 

        ]) ;

        if ($request->hasFile('mr_images')) {

            foreach ($request->file('mr_images') as $image) {

                $mr_image = time() . rand() . '.' . $image->extension() ;
                $image->move(public_path('ecom/cataloge'), $mr_image) ;
                $request->merge(['images' => $mr_image]) ;

                CatalogeImage::create([

                    'cataloge_id' => $catalogue->id,
                    'images' => $request->images,

                ]);
                
            }

        }

        $catalogue->categories()->attach($request->metals) ;
        $catalogue->categories()->attach($request->collections) ;
        $catalogue->categories()->attach($request->categories) ;

        if($cataloge) { 

            return response()->json(['success' => 'Data Saved successfully']);

        }else{

            return response()->json(['success' => 'Data Saved successfully']);

        }
            

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Cataloge $catalogue) {

        $catalogue->delete() ;

        return redirect()->route('catalogues.index')->with('success', 'Delete successfully.');

    }
}
