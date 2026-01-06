<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;

class CustomRoleOrPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next, $permission){

        $right_check = user_rights_check($request , $permission) ;

        $permission_denied = $right_check['permission_denied'] ;
        $subscription_expire = $right_check['subscription_expire'] ;

        if ($request->ajax()) {

            $msg_show = $permission_denied ? $permission_denied : $subscription_expire ; 

            return response()->json(['errors' => $msg_show ], 403) ;

        }

        session()->flash('permission_denied', $permission_denied) ;
        session()->flash('subscription_expire', $subscription_expire) ;

        return $next($request) ;

    }
     
}
