<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission ;
use App\Models\SubscriptionModule ;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PermissionController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        // $this->middleware('role_or_permission:Permission show|Permission create|Permission edit|Permission delete', ['only' => ['index','show']]);
        // $this->middleware('role_or_permission:Permission create', ['only' => ['create','store']]);
        // $this->middleware('role_or_permission:Permission edit', ['only' => ['edit','update']]);
        // $this->middleware('role_or_permission:Permission delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {

        $permission= Permission::whereNull('parent_id')->latest()->get();

        return view('admin.permission.index',['permissions'=>$permission]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create() {

        $permissions = Permission::whereNull('parent_id')->get();
        return view('admin.permission.create' , compact('permissions') ) ;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request) {

         $request->validate([
             'name' => ['required', 'string', 'unique:permissions,name'],
             'parent_id' => 'nullable',
         ]);

         Permission::create($request->all()) ;

         return redirect()->route('permissions.index')->with('success', 'Permission created successfully.') ;

     }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Permission $permission){

        $permissions = Permission::whereNull('parent_id')->get();

       return view('admin.permission.edit',compact('permission','permissions'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Permission $permission){

        $permission->update($request->all()) ;

        return redirect()->back()->withSuccess('Permission updated !!!') ;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->back()->withSuccess('Permission deleted !!!');
    }
}
