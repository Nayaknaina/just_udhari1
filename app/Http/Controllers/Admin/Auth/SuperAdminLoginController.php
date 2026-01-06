<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\Facades\Validator ;


class SuperAdminLoginController extends Controller {

    use AuthenticatesUsers; 

    protected $redirectTo = '/ss_manager/home';

    public function showLoginForm() {

        return view('superadmin.auth.login') ;

    }

    public function __construct() {

        $this->middleware('guest')->except('logout');
        $this->middleware('IsSuperadmin')->only('logout');

    }

    public function logout(Request $request){

        Auth::guard('superadmin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/') ;

    }
}
