<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request) {

        $validator = Validator::make($request->all(), [

            'mobile_no' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {

            return response()->json(['error' => 'Unauthorized'],401) ;

        }

        $token = $user->createToken('Personal Access Token')->plainTextToken;
        $token_type = 'bearer' ;
        return response()->json(['token' => $token , 'token_type' => $token_type , 'user' => $user]) ;

    }
}
