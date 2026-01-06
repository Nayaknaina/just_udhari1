<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator ;
use App\Models\Permission; // Use the custom Permission model

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = Role::whereNull('shop_id')->orderBy('id', 'desc') ;

        if($request->title) { $query->where('title', 'like', '%' . $request->title . '%'); }

        $roles = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('admin.roles..disp', compact('roles'))->render();
            return response()->json(['html' => $html]);

        }

        return view('admin.roles..index',compact('roles'));

    }

    public function create() {

        $permissions = Permission::whereNull('parent_id')->with('children')->get();
        return view('admin.roles.create',['permissions'=>$permissions]) ;

    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]) ;

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }

        $role = Role::create(['name'=>$request->name]);
        $role->syncPermissions($request->permissions);

        if($role) {
            return response()->json(['success' => 'Data Submitted successfully','errors' =>'']) ;
        }else{
            return response()->json(['errors' => $validator->errors(),], 425) ;
        }

    }

    public function edit(Role $role) {

        $permissions = Permission::whereNull('parent_id')->with('children')->get();

        return view('admin.roles.edit', compact('role', 'permissions'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Role $role) {

        // Validate the incoming request data
        $data = $request->validate([
            'permissions' => 'array|nullable',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Sync the permissions
        $this->syncPermissionsWithChildren($role, $data['permissions'] ?? []) ;

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Role updated successfully.');

    }

    private function syncPermissionsWithChildren(Role $role, array $permissions) {

        // First, clear all existing permissions

        $role->permissions()->detach();

        foreach ($permissions as $permissionId) {
            $permission = Permission::find($permissionId);

            if ($permission) {
                // Attach the parent permission
                $role->givePermissionTo($permission);

                // Attach all child permissions if it's a parent permission
                if ($permission->children) {
                    foreach ($permission->children as $child) {
                        $role->givePermissionTo($child);
                    }
                }
            }
        }
    }

    private function ModulePermissionsWithChildren(Role $role, array $permissions) {

        // First, clear all existing permissions

        $role->permissions()->detach();

        foreach ($permissions as $permissionId) {
            $permission = Permission::find($permissionId);

            if ($permission) {
                // Attach the parent permission
                $role->givePermissionTo($permission);

                // Attach all child permissions if it's a parent permission
                if ($permission->children) {
                    foreach ($permission->children as $child) {
                        $role->givePermissionTo($child);
                    }
                }
            }
        }
    }

}
