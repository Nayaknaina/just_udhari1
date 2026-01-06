<?php

namespace App\Http\Controllers\Vendor\Auth;

use App\Http\Controllers\Controller ;
use Illuminate\Http\Request ;

use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\Facades\Validator ;

class LoginController extends Controller {

    public function logout(Request $request) {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/') ;

    }
}
