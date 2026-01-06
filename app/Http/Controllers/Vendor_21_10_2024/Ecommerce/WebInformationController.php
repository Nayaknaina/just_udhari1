<?php

namespace App\Http\Controllers\Vendor\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\EcommWebInformation;
use App\Models\ShopBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator ;

class WebInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {

        $inf = EcommWebInformation::where('shop_id',app('userd')->shop_id)->first() ;
        $branch = ShopBranch::where('shop_id',app('userd')->shop_id)->first() ;
        // dd($data) ;
        return view("vendors.ecommerce.sitecontent.ecommwebinformations" , compact('inf','branch') );

    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'web_title' => 'required' ,
            'web_logo' => 'required' ,
            'email' => 'required' ,
            'email_2' => 'nullable' ,
            'mobile_no' => 'required' ,
            'mobile_no_2' => 'nullable' ,
            'map_iframe' => 'nullable' ,
            'address' => 'required' ,
            // 'meta_title' => 'required' ,
            // 'meta_description' => 'required' ,

        ]) ;

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        if ($request->hasFile('web_logo')) {

            $file = $request->file('web_logo') ;
            $folder = 'assets/ecomm/logos' ;
            $uniqueFileName = generateUniqueImageName($file, $folder) ;
            $request->merge(['logo' => $uniqueFileName]) ;

        }

        $webInformation = EcommWebInformation::create([

                'web_title' => $request->web_title ,
                'logo' => $request->logo ,
                'email' => $request->email ,
                'email_2' => $request->email_2 ,
                'mobile_no' => $request->mobile_no ,
                'mobile_no_2' => $request->mobile_no_2 ,
                'map' => $request->map_iframe ,
                'address' => $request->address ,
                'meta_title' => $request->meta_title ,
                'meta_description' => $request->meta_description ,
                'branch_id' => app('userd')->branch_id ,
                'shop_id' => app('userd')->shop_id ,

        ]) ;

        if($webInformation) {

            $file->move(public_path($folder), $uniqueFileName) ;

            return response()->json(['success' => 'Data Submitted successfully','errors' =>'']) ;

        }else{

            return response()->json(['errors' => 'Data Submitted failed'], 425) ;

        }

    }

    public function update(Request $request, EcommWebInformation $ecommwebinformation) {

        // dd($ecommwebinformation) ;

        $validator = Validator::make($request->all(), [

            'web_title' => 'required' ,
            // 'web_logo' => 'required' ,
            'email' => 'required' ,
            'email_2' => 'nullable' ,
            'mobile_no' => 'required' ,
            'mobile_no_2' => 'nullable' ,
            'map_iframe' => 'nullable' ,
            'address' => 'required' ,

        ]) ;

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        if ($request->hasFile('web_logo')) {

            $file = $request->file('web_logo') ;
            $folder = 'assets/ecomm/logos' ;
            $uniqueFileName = generateUniqueImageName($file, $folder) ;
            $request->merge(['logo' => $uniqueFileName]) ;
            $file->move(public_path($folder), $uniqueFileName) ;

        }

        $logo = $request->logo ? $request->logo : $ecommwebinformation->logo ;

        $webinformations = $ecommwebinformation->update([

                'web_title' => $request->web_title ,
                'logo' => $logo ,
                'email' => $request->email ,
                'email_2' => $request->email_2 ,
                'mobile_no' => $request->mobile_no ,
                'mobile_no_2' => $request->mobile_no_2 ,
                'map' => $request->map_iframe ,
                'address' => $request->address ,
                'meta_title' => $request->meta_title ,
                'meta_description' => $request->meta_description ,
                'branch_id' => app('userd')->branch_id ,
                'shop_id' => app('userd')->shop_id ,

        ]) ;

        if($webinformations) {
            return response()->json(['success' => 'Data Updated successfully','errors' =>'']) ;
        }else{
            return response()->json(['errors' => 'Data Updated failed'], 425) ;
        }

    }

}
