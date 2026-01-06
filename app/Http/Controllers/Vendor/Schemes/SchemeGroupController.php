<?php

namespace App\Http\Controllers\Vendor\Schemes;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\SchemeGroup;
use App\Models\ShopScheme;
use App\Models\ShopBranch;
use App\Models\TableStructure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchemeGroupController extends Controller
{

	function __construct() {

        // $this->middleware('module.permission:Inventory Stock', ['only' => ['index','show']]);
        // $this->middleware('action_permission:Inventory Stock', ['only' => ['create','store']]);
        // $this->middleware('action_permission:Inventory Stock', ['only' => ['edit','update']]);
        // $this->middleware('action_permission:Supplier Delete', ['only' => ['delete','destroy']]);
        $this->middleware('check.password', ['only' => ['destroy']]) ;

    }
    /**
     * Display a listing of the resource.
     */


    /*public function index(Request $request) {
		
        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);
        $data_query = ShopScheme::with(['mygroups'=>function($query) use ($request){
            return (isset($request->group) && $request->group!="")?$query->where('group_name','like','%'.$request->group.'%'):$query;
        },'mygroups.members'])->where('shop_id',app('userd')->shop_id)->where('ss_status',1);
        
        if(isset($request->scheme) && $request->scheme!=""){
            $data_query->where('scheme_head','like','%'.$request->scheme.'%')->orwhere('scheme_sub_head','like','%'.$request->scheme.'%');
        }
        $scheme_groups = $data_query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.schemes.groups.disp', compact('scheme_groups'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.schemes.groups.index',compact('scheme_groups'));

    }*/

	public function index(Request $request) {
        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);
        $data_query = SchemeGroup::with('members')->where('shop_id',auth()->user()->shop_id);
		
		$data_query->whereHas('schemes', function($query) use ($request) {
			$query->where('ss_status', 1);
			if(isset($request->scheme) && $request->scheme!=""){
				$query->where('scheme_head', 'like', $request->scheme . '%');
			}	  
		});
		
        $scheme_groups = $data_query->paginate($perPage, ['*'], 'page', $currentPage);
		
        if ($request->ajax()) {
            $html = view('vendors.schemes.groups.disp', compact('scheme_groups'))->render();
            return response()->json(['html' => $html]);
        }

        return view('vendors.schemes.groups.index',compact('scheme_groups'));
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
        //return redirect()->route('groups.index')->with('success', 'Delete successfully.');
		return response()->json(['success' => 'Deleted successfully.']) ;
    }
	
}
