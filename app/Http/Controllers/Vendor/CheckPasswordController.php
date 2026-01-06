<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CheckPasswordController extends Controller
{
    public function showPasswordForm()
    {
        return view('vendors.partials.password_modal'); // Blade view for password confirmation
    }

    public function confirmPassword(Request $request) {

        // Server-side validation
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            // Return validation error response
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('password'),
            ]);
        }

        $user = Auth::user() ;

        if (Hash::check($request->password, $user->mpin)) {

            // Password is correct, set the session and clear the flag
            Session::put('password_confirmed', true);
            Session::forget('password_required');

            // Return success response
            return response()->json(['success' => true]) ;

        } else {
            // Password is incorrect, return error response
            return response()->json(['success' => false, 'message' => 'The password is incorrect.']);
        }
    }
}


