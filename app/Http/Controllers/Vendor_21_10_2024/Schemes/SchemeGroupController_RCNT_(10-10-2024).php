<?php

namespace App\Http\Controllers\Vendor\Schemes;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\SchemeGroup;
use App\Models\ShopScheme;
use App\Models\TableStructure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchemeGroupController extends Controller
{


    /**
     * Display a listing of the resource.
     */

     public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = SchemeGroup::with('schemes')->orderBy('id', 'desc') ;

        $groups = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.schemes.groups.disp', compact('groups'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.schemes.groups.index',compact('groups'));

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create() {

        $query1 = ShopScheme::with('schemes') ;
        Shopwhere($query1) ;
        $schemes = $query1->orderBy('id', 'desc')->get();
        return view('vendors.schemes.groups.create',compact('schemes')) ;

    }

    public function show(SchemeGroup $group) {

        return view('vendors.schemes.groups.show') ;

    }

    /**
     * Store a newly created resource in storage.
     */

    // public function store(Request $request) {

    //     $validator = Validator::make($request->all(), [
    //         'scheme_id' => 'required',
    //         'group_name' => [
    //             'required',
    //             Rule::unique('scheme_groups')->where(function ($query) use ($request) {
    //                 return $query->where('scheme_id', $request->scheme_id)
    //                             ->where('shop_id', auth()->user()->shop_id);
    //             }),
    //         ],
    //         'start_date' => 'required',
    //         'end_date' => 'required',
    //         'group_limit' => 'required',
    //     ],[
    //         'scheme_id' => 'Please Select Scheme',
    //         'group_name.required' => 'Group Name is required',
    //         'group_name.unique' => 'The group name has already been taken for this scheme.',

    //     ]);

    //     if ($validator->fails()) {

    //         return response()->json(['errors' => $validator->errors(),], 422) ;

    //     }

    //     $SchemeGroup = SchemeGroup::create([
    //             'scheme_id' => $request->scheme_id,
    //             'group_name' => $request->group_name,
    //             'start_date' => $request->start_date,
    //             'end_date' => $request->end_date,
    //             'group_limit' => $request->group_limit,
    //             'branch_id' =>auth()->user()->branch_id,
    //             'shop_id' =>auth()->user()->shop_id,
    //         ]) ;

    //     if($SchemeGroup) {
    //         return response()->json(['success' => 'Data Saved successfully']);
    //     }else{
    //         return response()->json(['errors' =>'Data Save Failed'], 425) ;
    //     }

    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'scheme_id' => 'required',
            'group_name' => [
                'required',
                Rule::unique('scheme_groups')->where(function ($query) use ($request) {
                    return $query->where('scheme_id', $request->scheme_id)
                                ->where('shop_id', auth()->user()->shop_id);
                }),
            ],
            'group_limit' => 'required',
        ],[
            'scheme_id' => 'Please Select Scheme',
            'group_name.required' => 'Group Name is required',
            'group_name.unique' => 'The group name has already been taken for this scheme.',
            'group_limit.required'=>'Group Limit Required.'
        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $SchemeGroup = SchemeGroup::create([
                'scheme_id' => $request->scheme_id,
                'group_name' => $request->group_name,
                'group_limit' => $request->group_limit,
                'branch_id' =>auth()->user()->branch_id,
                'shop_id' =>auth()->user()->shop_id,
            ]) ;

        if($SchemeGroup) {
            return response()->json(['success' => 'Data Saved successfully']);
        }else{
            return response()->json(['errors' =>'Data Save Failed'], 425) ;
        }

    }

    public function showtable($id){

        // Fetch the saved table structure and rows from the database by ID
        $tableStructure = TableStructure::findOrFail($id);

        // Decode JSON data into PHP arrays
        $structure = json_decode($tableStructure->structure, true);
        $rows = json_decode($tableStructure->rows, true);

        // Return a view with the table structure and data
        return view('vendors.schemes.groups.table', compact('structure', 'rows'));

    }

    public function table(Request $request) {
        // Validate the request
        $request->validate([
            'table_data' => 'required|json',
        ]);

        // Decode the JSON data
        $tableData = json_decode($request->table_data, true);

        // Process table structure
        $tableStructure = $tableData['structure'];
        $tableRows = $tableData['rows'];

        // Example: Save the structure and rows to the database
        $table = new TableStructure();
        $table->structure = json_encode($tableStructure);
        $table->rows = json_encode($tableRows);
        $table->save();

        return response()->json(['message' => 'Table structure and data saved successfully!']);

    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(SchemeGroup $group) {

        $query1 = ShopScheme::with('schemes') ;
        Shopwhere($query1) ;
        $schemes = $query1->orderBy('id', 'desc')->get();

        return view('vendors.schemes.groups.edit', compact('schemes','group')) ;

    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, SchemeGroup $group) {

        // $validator = Validator::make($request->all(), [
        //     'scheme_id' => 'required',
        //     'group_name' => [
        //         'required',
        //         Rule::unique('scheme_groups')->where(function ($query) use ($request) {
        //             return $query->where('scheme_id', $request->scheme_id)
        //                         ->where('shop_id', auth()->user()->shop_id);
        //         }),
        //     ],
        //     'group_limit' => 'required',
        // ],[
        //     'scheme_id' => 'Please Select Scheme',
        //     'group_name.required' => 'Group Name is required',
        //     'group_name.unique' => 'The group name has already been taken for this scheme.',
        //     'group_limit.required'=>'Group Limit Required.'
        // ]);
        $validator = Validator::make($request->all(), [
            'scheme_id' => 'required',
            'group_name' => 'required',
            'group_limit' => 'required',
        ],[
            'scheme_id.required' => 'Please Select Scheme',
            'group_name.required' => 'Group Name is required',
            'group_limit.required'=>'Group Limit Required.'
        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $SchemeGroup = $group->update([
                'scheme_id' => $request->scheme_id,
                'group_name' => $request->group_name,
                'group_limit'=>$request->group_limit,
                'branch_id' =>auth()->user()->branch_id,
                'shop_id' =>auth()->user()->shop_id,
        ]);

        if($SchemeGroup) {
            return response()->json(['success' => 'Data Updated successfully']);
        }else{
            return response()->json(['errors' =>'Data Save Failed'], 425) ;
        }

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(SchemeGroup $group) {

        $group->delete() ;
        return redirect()->route('groups.index')->with('success', 'Delete successfully.');

    }

}
