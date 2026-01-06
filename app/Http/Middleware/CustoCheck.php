<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class CustoCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		if(Auth::guard('custo')->check() && Auth::guard('custo')->user()->shop_id == app('shop')->shop_id){
            return $next($request);
        }else{
            if($request->ajax()){
                echo json_encode(['status'=>false,'msg'=>"Please Login First !"]);
                exit();
            }else{
                return redirect()->intended(app('ecommbaseurl').'login')->with('error',"Please Log-In First !");
            }
        }
    }
}
