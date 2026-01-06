<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use DateTime ;

use App\Models\ShopRight;
use App\Models\Permission;
use App\Models\SoftwareProduct;

class CheckModulePermission {

    public function handle($request, Closure $next, $permission) {

        $user = Auth::user() ;

        if (!$user->hasPermissionTo($permission)) {

            if ($request->ajax()) {
                return response()->json(['errors' => 'You do not have permission to access this module.'], 403) ;
            }

            session()->flash('permission_denied', 'You do not have permission to access this module.') ;

        }else {

                $current_date = new DateTime() ;
                $expiry_date = [] ;
                $arr_permission = [] ;

                // Collecting data
                foreach (@$user->subscriptions as $subscription) {

                    $title = $subscription->product->title ;
                    $expiry = $subscription->expiry_date ;
                    $product_id = $subscription->product->id;

                    $shopRights = ShopRight::where('shop_id', $user->shop_id)
                    ->where('product_id', $product_id)->with('permission')->get();

                    if(!$shopRights) { 

                        foreach ($subscription->product->roles->permissions as $gt_permission) {
                            array_push($arr_permission, [
                            'title' => $title,
                            'expiry_date' => $expiry,
                            'permission_name' => $gt_permission->name
                            ]);
                        }

                    }else{

                        foreach ($shopRights as $shopRight) {
                            $permissionName = $shopRight->permission->name ;
                            array_push($arr_permission, [
                                'title' => $title, // Assuming $title is defined elsewhere in your code
                                'expiry_date' => $expiry, // Assuming $expiry is defined elsewhere in your code
                                'permission_name' => $permissionName
                            ]);
                        }
                    }
                }

                $given_permission_name = $permission ; // Replace with the actual permission name

                $filtered_subscriptions = array_filter($arr_permission, function ($item) use ($given_permission_name) {

                    $expiry_date = new DateTime($item['expiry_date']);
                    return $item['permission_name'] === $given_permission_name ;

                }) ;

                if(empty($filtered_subscriptions)) {

                    if ($request->ajax()) {
                        return response()->json(['errors' => 'Don`t have a Subscription for this module'], 403) ;
                    }

                    session()->flash('subscription_expire', 'Don`t have a Subscription for this module') ;

                } else{

                    $filtered_expiry = array_filter($filtered_subscriptions, function ($item) use ($current_date) {
                        $expiry_date = new DateTime($item['expiry_date']);
                        return $expiry_date >= $current_date ;
                    });

                    if(empty($filtered_expiry)) {

                        if ($request->ajax()) {
                            return response()->json(['errors' => 'Your Subscription for this module is Expired'], 403) ;
                        }

                        session()->flash('subscription_expire', 'Your Subscription for this module is Expired') ;

                    }
                }

        }

        return $next($request) ;

    }
}
