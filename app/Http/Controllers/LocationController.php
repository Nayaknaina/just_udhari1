<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

class LocationController extends Controller {


    public function get_districts(Request $request) {

        $state = $request->input('state') ;
        $districts = District::where('state_code',$state)->select('name','code')->get() ;

        return response()->json($districts) ;

    }
 

}
