<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\Facades\Validator ;


class AdminLoginController extends Controller
{

    use AuthenticatesUsers;

    public function index()
    {
        return view('admin.auth.login');
    }

    // Use ANY ONE ===> the above code OR below code

    //Second method to Redirect with Message ("STATUS") eg: welcome to dashboard

    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors()], 422) ;

        }

        $credentials = $request->only('mobile_no', 'password') ;

        if (auth()->guard('superadmin')->attempt($credentials)) {

            if (Auth::guard('superadmin')->check()) {

                return response()->json(['success' => 'Login successful.'], 200) ;

            }else {

                return response()->json(['errors' => 'Login Failed.'], 200) ;

            }
        }

        return response()->json(['errors' => ['mobile_no' => 'Invalid credentials.']], 425);

    }


}
