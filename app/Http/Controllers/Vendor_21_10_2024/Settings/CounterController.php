<?php

namespace App\Http\Controllers\Vendor\Settings;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


class CounterController extends Controller
{

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ; 
        $currentPage = $request->input('page', 1);

        $query = Counter::orderBy('id', 'desc') ;

        if($request->counter_name) { $query->where('name', 'like', '%' . $request->counter_name . '%'); }

        $counters = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.settings.counters.disp', compact('counters'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.settings.counters.index',compact('counters')); 

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

        return view('vendors.settings.counters.create') ;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        
        $validator = Validator::make($request->all(), [

            'name' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $counter = Counter::create([

            'name' => $request->name,
            'branch_id' =>auth()->user()->branch_id,
            'shop_id' =>auth()->user()->shop_id,

        ]) ;

        if($counter) { 
            return response()->json(['success' => 'Data Saved successfully']);
        }else{
            return response()->json(['errors' =>'Data Save Failed'], 425) ;
        }

    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Counter $counter) {
 
        return view('vendors.settings.counters.edit', compact('counter')) ;

    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Counter $counter) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }

        $counter = $counter->update([
            'name' => $request->name,
        ]);

        if($counter) { 
            return response()->json(['success' => 'Updated Successfully']);
        }else{
            return response()->json(['errors' => 'Updated Failes'], 425);
        }

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Counter $counter) {

        $counter->delete() ;
        return redirect()->route('counters.index')->with('success', 'Delete successfully.');

    }
}
