<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopRight;
use App\Models\Permission;
use App\Models\SoftwareProduct;
use App\Models\Role;
use Illuminate\Http\Request;

class ShopRightController extends Controller {

    public function index(Request $request) {

        $shop_id = $request->shop_id ;

        $permissions = Permission::whereNull('parent_id')->with('children')->get() ;
        $shoprights = ShopRight::where(['shop_id'=>$request->shop_id,'product_id'=>$request->product_id])->pluck('permission_id')
        ->toArray() ;

        $product = SoftwareProduct::where(['id'=>$request->product_id])->first() ;

        if(!$shoprights) {

            $shoprights = Role::with('permissions')
            ->where('id', $product->role_id)->first()->permissions->pluck('id')->toArray() ;

        }

        return view('admin.shoprights.index', compact('shop_id' ,'product','shoprights', 'permissions'));

    }

    /**
     * Update the specified resource in storage.
     */

    public function store(Request $request) {

        $data = $request->validate([
            'permissions' => 'array|nullable',
        ]);

        $product_id = $request->input('product_id') ;
        $shop_id = $request->input('shop_id') ;
        $permissions = $request->input('permissions') ;

        ShopRight::where(['shop_id' => $shop_id, 'product_id' => $product_id])->delete() ;

        foreach ($permissions as $permission_id) {
            ShopRight::create([
                'product_id' => $product_id,
                'permission_id' => $permission_id,
                'shop_id' => $shop_id,
            ]);
        } 

        return redirect()->back()->with('success', 'Role updated successfully.');

    }

    private function syncPermissionsWithChildren(SoftwareProduct $product, array $permissions) {

        // $shopright->permissions()->detach() ;

        foreach ($permissions as $permissionId) {

            $permission = Permission::find($permissionId);

            if ($permission) {

                $product->givePermissionTo($permission) ;

                if ($permission->children) {
                    foreach ($permission->children as $child) {
                        $product->givePermissionTo($child);
                    }
                }
            }
        }
    }

}
