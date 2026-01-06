<?php

namespace App\Http\Controllers ;

use Illuminate\Http\Request ;

class HomeController extends Controller {

    public function index(Request $request ) {
		
        return view('website.home') ;

    }

    public function products(Request $request ) {

        return view('website.products.index') ;

    }

    public function softwaretutorial(Request $request ) {

        return view('website.softwaretutorial') ;

    }

    public function whatsnew(Request $request ) {

        return view('website.whatsnew') ;

    }

    public function faq(Request $request ) {

        return view('website.faq') ;

    }

    public function contact(Request $request ) {

        return view('website.contact') ;

    }
	
	public function information($type=false){
        if(in_array($type,['privacy','term','refund'])){
            return view("website.policy.{$type}") ;
        }else{
            echo '<p style="text-align:center">Invalid Action<br><a href="#" onclick="history.back()">Back ?</a></p>';
        }
    }
	
	public function accountdeleterequest(){
        return view("website.policy.acdelete") ;
    }
}
