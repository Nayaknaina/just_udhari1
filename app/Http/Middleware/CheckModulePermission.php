<?php

namespace App\Http\Middleware;

use Closure ;
use Illuminate\Support\Facades\Auth ;
use DateTime ;
use App\Models\ShopRight ;
use App\Models\Permission ;
use App\Models\SoftwareProduct ;

class CheckModulePermission {

    public function handle($request, Closure $next, $permission) {

        $right_check = user_rights_check($request , $permission) ;

        $ecommerce_denied = $right_check['ecommerce_denied'] ;
        $permission_denied = $right_check['permission_denied'] ;
        $subscription_expire = $right_check['subscription_expire'] ;

        $ur_roles = [] ;

        foreach(Auth::user()->roles as $urole){

           array_push($ur_roles , $urole->name ) ;

        }

        $searchTerm = "Shop Owner";

        $rl_exists = array_filter($ur_roles, function($element) use ($searchTerm) {

            return strpos($element, $searchTerm) !== false ;

        }) ;

        if (!$rl_exists) {

            return response()->view('errors.errors-403', [], 403) ;

        }else{

            session()->flash('ecommerce_denied', $ecommerce_denied) ;
            session()->flash('permission_denied', $permission_denied) ;
            session()->flash('subscription_expire', $subscription_expire) ;

        }

        return $next($request) ;

    }
}
