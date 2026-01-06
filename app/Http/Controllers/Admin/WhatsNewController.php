<?php

namespace App\Http\Controllers\Admin ;

use App\Http\Controllers\Controller ;
use App\Models\WhatsNew ;
use App\Models\Stock ;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Validator ;
use Illuminate\Support\Facades\View ;
use Illuminate\Support\Str ;
use App\Rules\UniqueSlug;
use Illuminate\Support\Facades\DB;
use App\Models\Ecommerce\EcommProduct;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class WhatsNewController extends Controller {

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1) ;

        $query = WhatsNew::orderBy('id', 'desc') ;

        if($request->shop_name) { $query->where('shop_name', 'like', '%' . $request->shop_name . '%') ; }

        $whatsnew = $query->paginate($perPage, ['*'], 'page', $currentPage) ;

        if ($request->ajax()) {

            $html = view('admin.whatsnew.disp', compact('whatsnew'))->render() ;
            return response()->json(['html' => $html]) ;

        }

        return view('admin.whatsnew.index',compact('whatsnew')) ;

    }

    public function create() {

        return view('admin.whatsnew.create') ;

    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'title' => 'required|unique:whats_new,title',
            'image_file' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'description' => 'required',

        ]) ;

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        if ($request->hasFile('image_file')) {

            $file = $request->file('image_file') ;
            $folder = 'assets/images/whatsnew' ;
            $uniqueFileName = generateUniqueImageName($file, $folder) ;
            $request->merge(['image' => $uniqueFileName]) ;

        }

        $whatsnew = WhatsNew::create([

            'title' => $request->title,
            'image' => $request->image,
            'description' => $request->description,

        ]) ;

        if($whatsnew) {

            $file->move(public_path($folder), $uniqueFileName) ;
            return response()->json(['success' => 'Data Submitted successfully','errors' =>'']) ;

        }else{

            return response()->json(['errors' => $validator->errors(),], 425) ;

        }

    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(WhatsNew $whatsnew) {

        return view('admin.whatsnew.edit', compact('whatsnew')) ;

    }

    /**
     * Update the specified resource in storage.
    */

    public function update(Request $request, WhatsNew $whatsnew) {

        $validator = Validator::make($request->all(), [

            'title' => 'required|unique:whats_new,title,'.$whatsnew->id,
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'description' => 'required',

        ]) ;

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        if ($request->hasFile('image_file')) {

            $file = $request->file('image_file') ;
            $folder = 'assets/images/whatsnew' ;
            $uniqueFileName = generateUniqueImageName($file, $folder) ;
            $request->merge(['image' => $uniqueFileName]) ;
            $imagePath = public_path($folder .'/'. $whatsnew->image) ;
            delete_image($imagePath) ;

        }else{

            $request->merge(['image' => $whatsnew->image]) ;

        }

        $whatsnew = $whatsnew->update([

            'title' => $request->title,
            'image' => $request->image,
            'description' => $request->description,

        ]) ;

        if($whatsnew) {
            if ($request->hasFile('image_file')) {
                $file->move(public_path($folder), $uniqueFileName) ;
            }
            return response()->json(['success' => 'Data Submitted successfully','errors' =>'']) ;
        }else{
            return response()->json(['errors' => $validator->errors(),], 425) ;
        }

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(WhatsNew $whatsnew) {

        delete_image('assets/images/whatsnew/'.$whatsnew->image) ;

        $whatsnew->delete() ;
        return redirect()->route('whatsnew.index')->with('success', 'Delete successfully.') ;

    }
}
