<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {

    public function index() {

        return view('vendors.dashboard.home') ;

    }

    public function comingsoon() {

        return view('vendors.dashboard.comingsoon') ;

    }

    public function subscription_timer() {

        return view('vendors.settings.subscriptions.subscription_timer') ;

    }



}
