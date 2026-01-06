<?php

namespace App\Http\Controllers\Vendor\Settings;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator ;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DesignationController extends Controller {

    public function index(Request $request) {
        
        $role_suffix = role_prefix() ; 

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = Role::orderBy('id', 'desc') ;
        Shopwhere($query) ;

        if($request->title) { $query->where('title', 'like', '%' . $request->title . '%'); }

        $roles = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.settings.designations..disp', compact('roles','role_suffix'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.settings.designations..index',compact('roles','role_suffix'));

    }

    public function create() {

        $user = auth()->user(); // Get the authenticated user
        $roles = $user->roles ;

        $role_suffix = role_prefix() ; 
        // $role_suffix = $shop->role_suffix.'_' ;

         $permissions = collect() ;
         $childrenPermissions = collect() ;

         foreach ($roles as $role) {
             // Fetch and merge parent permissions
             $rolePermissions = $role->permissions()->whereNull('parent_id')->get();
             $permissions = $permissions->merge($rolePermissions);

             // Fetch and merge child permissions
             $roleChildren = $role->permissions()->whereNotNull('parent_id')->get();
             $childrenPermissions = $childrenPermissions->merge($roleChildren);
         }

         // Get unique parent permissions
         $uniquePermissions = $permissions->unique('id');

         // Get unique child permissions
         $uniqueChildren = $childrenPermissions->unique('id');

         // Assign children to their respective parent permissions
         foreach ($uniquePermissions as $permission) {
             $permission->children = $uniqueChildren->filter(function ($child) use ($permission) {
                 return $child->parent_id == $permission->id;
             });
         }

        return view('vendors.settings.designations.create', ['permissions' => $uniquePermissions,'role_suffix'=>$role_suffix]) ;

    }

    public function store(Request $request) {

        $role_suffix = $request->role_suffix ; 
        $role_name = $role_suffix.$request->name ; 

        $request->merge(['role_name' => $role_name]) ;

        $validator = Validator::make($request->all(), [
			"name"=>"required",
            'role_name' => 'required|unique:roles,name',
        ]) ;

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }

        $role = Role::create([
            'name'=>$role_name , 
            'guard_name'=>'web' , 
            'branch_id' =>auth()->user()->branch_id,
            'shop_id' =>auth()->user()->shop_id,
        ]) ;

        $this->syncPermissionsWithChildren($role, $request->permissions ?? []) ;

        if($role) {
            return response()->json(['success' => 'Data Submitted successfully','errors' =>'']) ;
        }else{
            return response()->json(['errors' => $validator->errors(),], 425) ;
        }

    }

    public function edit(Role $designation) {

        $user = auth()->user(); // Get the authenticated user
        $roles = $user->roles ;

        $role_suffix = role_prefix() ; 
        // $role_suffix = $shop->role_suffix.'_' ;

         $permissions = collect() ;
         $childrenPermissions = collect() ;

         foreach ($roles as $role) {
             // Fetch and merge parent permissions
             $rolePermissions = $role->permissions()->whereNull('parent_id')->get();
             $permissions = $permissions->merge($rolePermissions);

             // Fetch and merge child permissions
             $roleChildren = $role->permissions()->whereNotNull('parent_id')->get();
             $childrenPermissions = $childrenPermissions->merge($roleChildren);
         }

         // Get unique parent permissions
         $uniquePermissions = $permissions->unique('id');

         // Get unique child permissions
         $uniqueChildren = $childrenPermissions->unique('id');

         // Assign children to their respective parent permissions
         foreach ($uniquePermissions as $permission) {
             $permission->children = $uniqueChildren->filter(function ($child) use ($permission) {
                 return $child->parent_id == $permission->id;
             });
         }

        return view('vendors.settings.designations.edit', ['designation'=>$designation, 'permissions' =>$uniquePermissions,'role_suffix'=>$role_suffix]) ;

    }

    public function update(Request $request, Role $designation) {

        $role_suffix = $request->role_suffix ; 
        $role_name = $role_suffix.$request->name ; 

        $request->merge(['role_name' => $role_name]) ;

        $data = $request->validate([
			"name"=>"required",
            'role_name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($designation->id),
            ],
            'permissions' => 'array|nullable',
            'permissions.*' => 'exists:permissions,id',
        ]) ;

        $designation->update(['name'=>$request->role_name]);
        $this->syncPermissionsWithChildren($designation, $request->permissions ?? []) ;

        return redirect()->back()->with('success', 'Role updated successfully.');

    }

    private function syncPermissionsWithChildren(Role $role, array $permissions) {

        $role->permissions()->detach() ;

        foreach ($permissions as $permissionId) {
            $permission = Permission::find($permissionId);

            if ($permission) {
                // Attach the parent permission
                $role->givePermissionTo($permission) ;
            }
        }
    }

}
