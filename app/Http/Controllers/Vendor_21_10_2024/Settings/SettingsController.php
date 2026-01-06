<?php

namespace App\Http\Controllers\Vendor\Settings;

use App\Http\Controllers\Controller;
use App\Models\ShopBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller {

    /**
     * Display a listing of the resource.
     */

    public function index(){

       $shopbranch  = ShopBranch:: where('id',app('userd')->branch_id)->first() ;
        return view('vendors.settings.shopprofiles.index', compact('shopbranch')) ;

    }

    public function update(Request $request, ShopBranch $setting) {

        $validator = Validator::make($request->all(), [

            'shop_name' => 'required',
            'mobile_no' => ['required', 'string', 'digits:10', 'regex:/^[0-9]+$/'],
            'address' => 'required',
            'state' => 'required',
            'district' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $shopbranch = $setting->update([

            'branch_name' => $request->shop_name, 
            'mobile_no' => $request->mobile_no,
            'email' => $request->email,
            'address' => $request->address,
            'state' => $request->state ,
            'district' => $request->district,

        ]);

        if($shopbranch) {
            return response()->json(['success' => 'Data updated successfully']);
        }else{
            return response()->json(['errors' => 'Data update failed'], 425) ;
        }


    }

    public function destroy(Shop $shop) {
        


    }

}
