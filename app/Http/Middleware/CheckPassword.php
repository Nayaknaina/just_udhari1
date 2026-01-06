<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;use Illuminate\Support\Facades\Session;


class CheckPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next) {

        $password = $request->input('password');

        // Check if password is provided and matches the authenticated user's password
        if (!$password || !Hash::check($password, Auth::user()->mpin)) {
            return response()->json(['errors' => ['password' => ['Incorrect password']]], 422);
        }

        return $next($request) ;

    }
}
