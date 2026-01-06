<?php

namespace App\Http\Controllers\Admin ;

use App\Http\Controllers\Controller ;
use App\Models\WebPage ;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Validator ;
use Illuminate\Support\Facades\View ;
use Illuminate\Support\Str ;
use App\Rules\UniqueSlug;

class WebPageController extends Controller {

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = WebPage::orderBy('id', 'desc') ;

        if($request->shop_name) { $query->where('shop_name', 'like', '%' . $request->shop_name . '%'); }

        $webpages = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('admin.webpages.disp', compact('webpages'))->render();
            return response()->json(['html' => $html]);

        }

        return view('admin.webpages.index',compact('webpages'));

    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'title' => 'required|unique:web_pages,title',
            'description' => 'required',

        ]) ;

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $webpage = WebPage::create([

            'title' => $request->title,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,

        ]) ;

        if($webpage) {
            return response()->json(['success' => 'Data Submitted successfully','errors' =>'']);
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

    public function edit(WebPage $webpage) {

        return view('admin.webpages.edit', compact('webpage')) ;

    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, WebPage $webpage) {

        $validator = Validator::make($request->all(), [

            'title' => 'required|unique:web_pages,title,'.$webpage->id,
            'description' => 'required',

        ]) ;

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $webpage = $webpage->update([

            'title' => $request->title,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,

        ]) ;

        if($webpage) {
            return response()->json(['success' => 'Data Updated successfully','errors' =>'']);
        }else{
            return response()->json(['errors' => $validator->errors(),], 425) ;
        }

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(WebPage $webpage) {

        $webpage->delete() ;
        return redirect()->route('webpages.index',)->with('success', 'Delete successfully.') ;

    }
}
