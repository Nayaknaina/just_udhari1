<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CheckMpin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    /*public function handle(Request $request, Closure $next,$title=false): Response
    {
        // Already validated
        // if (Session::get('mpin_validated', false)) {
        //     session()->forget('mpin_validated');
        //     return $next($request);
        // }

        // If AJAX and has MPIN input, validate it
        if ($request->isMethod('post') && $request->has('mpin')) {
            if(Hash::check($request->input('mpin'), Auth::user()->mpin)){
                //Session::put('mpin_validated', true);
                if($request->redirect=="true"){
                    return response()->json(['success' => true]);
                }else{
                    return $next($request);
                }
            }
            return response()->json(['error' => 'Invalid MPIN']);
        }
        // First-time access, show popup
        return response()->view('components.mpinprompt',compact('request','title'));
    }*/
	
	public function handle(Request $request, Closure $next,$title=false): Response
    {

        // If AJAX and has MPIN input, validate it
        if ($request->isMethod('post') && $request->has('mpin')) {
            if(Hash::check($request->input('mpin'), Auth::user()->mpin)){
                if($request->redirect=="true"){
                    Session::put('mpin_validated', true);
                    return response()->json(['success' => true]);
                }else{
                    return tap($next($request), function () {
                        session()->forget('mpin_validated');
                    });
                }
            }
            return response()->json(['error' => 'Invalid MPIN']);
        }
        //return response()->view('components.mpinprompt',compact('request','title'));
        if(!Session::get('mpin_validated', false)){
            return response()->view('components.mpinprompt',compact('request','title'));
        }else{
            return tap($next($request), function () {
                session()->forget('mpin_validated');
            });
        }
        // First-time access, show popup
    }
}
