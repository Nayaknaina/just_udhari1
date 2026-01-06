<?php

namespace App\Http\Controllers\Admin ;

use App\Http\Controllers\Controller ;
use App\Models\Faq ;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Validator ;
use Illuminate\Support\Facades\View ;
use Illuminate\Support\Str ;
use App\Rules\UniqueSlug;

class FaqController extends Controller {

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = Faq::orderBy('id', 'desc') ;

        if($request->shop_name) { $query->where('shop_name', 'like', '%' . $request->shop_name . '%'); }

        $faqs = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('admin.faqs.disp', compact('faqs'))->render();
            return response()->json(['html' => $html]);

        }

        return view('admin.faqs.index',compact('faqs'));

    }


    public function create() {

        return view('admin.faqs.create') ;

    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'title' => 'required|unique:faqs,title',
            'description' => 'required',

        ]) ;

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $faq = Faq::create([

            'title' => $request->title,
            'description' => $request->description,

        ]) ;

        if($faq) {
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

    public function edit(Faq $faq) {

        return view('admin.faqs.edit', compact('faq')) ;

    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Faq $faq) {

        $validator = Validator::make($request->all(), [

            'title' => 'required|unique:faqs,title,'.$faq->id,
            'description' => 'required',

        ]) ;

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $faq = $faq->update([

            'title' => $request->title,
            'description' => $request->description,

        ]) ;

        if($faq) {
            return response()->json(['success' => 'Data Updated successfully','errors' =>'']);
        }else{
            return response()->json(['errors' => $validator->errors(),], 425) ;
        }

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Faq $faq) {

        $faq->delete() ;
        return redirect()->route('faqs.index')->with('success', 'Delete successfully.') ;

    }
}
