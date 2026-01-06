<?php

namespace App\Http\Controllers\Admin ;

use App\Http\Controllers\Controller ;
use App\Models\WebInformation ;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Validator;

class WebInformationController extends Controller {

    public function index() {

        $webinformation = WebInformation::first() ;

        return view('admin.webinformation.index', compact('webinformation')) ;

    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, WebInformation $webinformation){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'web_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'mobile_no' => ['required', 'string', 'digits:10', 'regex:/^[0-9]+$/'],
            'whatsapp_no' => ['required', 'string', 'digits:10', 'regex:/^[0-9]+$/'],
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'map' => 'nullable|string',
        ] , [ 'web_logo.required'=>'Logo is required']) ;

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        if ($request->hasFile('web_logo')) {

            $file = $request->file('web_logo') ;
            $folder = 'assets/images/logo' ;
            $uniqueFileName = generateUniqueImageName($file, $folder) ;
            $fileName = $folder.'/'.$uniqueFileName ;
            $request->merge(['logo' => $fileName]) ;

            if ($webinformation->logo && file_exists(public_path($webinformation->logo))) {
                unlink(public_path($webinformation->logo)) ;
            }

        }else {

            $request->merge(['logo' => $webinformation->logo ]) ;

        }

        $webInformation = $webinformation->update([

            'name' => $request->input('name') ,
            'mobile_no' => $request->input('mobile_no') ,
            'whatsapp_no' => $request->input('whatsapp_no') ,
            'email' => $request->input('email') ,
            'logo' => $request->logo ,
            'address' => $request->input('address') ,
            'map' => $request->input('map')

        ]) ;

        if($webInformation) {

            if ($request->hasFile('web_logo')) {
                $file->move(public_path($folder), $uniqueFileName) ;
            }

            return response()->json(['success' => 'Data Updated successfully','errors' =>'']) ;

        }else{

            return response()->json(['errors' => $validator->errors(),], 425) ;

        }

    }

}
