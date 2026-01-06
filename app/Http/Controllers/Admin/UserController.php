<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User ;
use App\Models\Shop ;
use App\Models\ShopBranch ;
use App\Models\Scheme ;
use App\Models\ShopScheme ;
use App\Models\Ecommerce\EcommWebInformation ;
use App\Models\SoftwareProduct ;
use App\Models\SoftwareProductSubscription ;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1) ;

        $query = User::with('shop','schemes')->where('user_type',0)->orderBy('id', 'desc') ;

        if($request->title) { $query->where('title', 'like', '%' . $request->title . '%'); }

        $users = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('admin.users.disp', compact('users'))->render() ;
            return response()->json(['html' => $html]) ;

        }

        return view('admin.users.index',compact('users')) ;

    }

    public function edit(User $user) {

        $subscribedProducts = SoftwareProductSubscription::where('shop_id', $user->shop_id)
        ->get(['product_id', 'expiry_date'])
        ->keyBy('product_id') ;

        $products = SoftwareProduct::all();

       $products = SoftwareProduct::all() ;
       return view('admin.users.edit',['user'=>$user,'products' => $products,'subscribedProducts'=>$subscribedProducts]) ;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, User $user) {

        $validator = Validator::make($request->all(), [

            'products' => 'required|array|min:1',
            'expiry_date' => 'array',

        ]) ;

        $products = $request->input('products') ;
        $expiry_date = $request->input('expiry_date') ;
        $shopId = $request->input('shop_id');

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }

        $shopProducts = [] ;

        foreach ($products as $product) {

            if (!isset($expiry_date[$product])) {
                return response()->json(['errors' => ['expiry_date' => 'Expiry date is missing selected Products']], 422) ;
            }

            $shopProducts[] = [
                'shop_id' => $shopId,
                'product_id' => $product,
                'expiry_date' => $expiry_date[$product],
            ] ;
        }

        foreach ($shopProducts as $shopProduct) {
            SoftwareProductSubscription::updateOrCreate(
                ['shop_id' => $shopProduct['shop_id'], 'product_id' => $shopProduct['product_id']],
                ['expiry_date' => $shopProduct['expiry_date']]
            );
        }

        // Find subscriptions to delete (subscriptions that exist but are not in the request)
        $subscriptionsToDelete = SoftwareProductSubscription::where('shop_id', $request->shop_id)
        ->whereNotIn('product_id', $products)->get() ;

        // Delete subscriptions that are not in the request
        if ($subscriptionsToDelete->isNotEmpty()) {
            $subscriptionsToDelete->each->delete();
        }

        // Retrieve roles for selected products and sync them with the user
        $roles = SoftwareProduct::whereIn('id', $products)->pluck('role_id')->toArray() ;
        $user->syncRoles($roles) ;

        return response()->json(['success' => 'Data Saved successfully','errors' => '']) ;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

      public function destroy(User $user){

        $user->delete() ;
        return redirect()->back()->withSuccess('User deleted !!!') ;

      }

      public function ecommsetups($id) {

        $branch_data = ShopBranch::find($id) ;
        $data = EcommWebInformation::where('shop_id',$branch_data->shop_id)->first() ;

        return view('admin.ecommerce.setups',compact('branch_data','data')) ;

      }

      public function ecommsetups_update(Request $request) {

        $branch_data = ShopBranch::where('id',$request->branch_id)->first() ;
        $data = EcommWebInformation::where('shop_id',$branch_data->shop_id)->first() ;

        // Define validation rules
        $rules = [
            'web_domain' => ['required', 'unique:shop_branches,domain_name'] ,
            'web_title' => 'required',
            'email' => 'required',
            'email_2' => 'nullable',
            'mobile_no' => 'required',
            'mobile_no_2' => 'nullable',
            'map_iframe' => 'nullable',
            'address' => 'required',
        ] ;

        // Add 'web_logo' as required if no existing data is found
        if (!$data) {
            $rules['web_logo'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
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

        $logo = $request->logo ? $request->logo : ($data ? $data->logo : null);

        $brch = $branch_data->update(['domain_name' => $request->web_domain]) ;

        $webInformationData = [
            'web_title' => $request->web_title,
            'logo' => $logo,
            'email' => $request->email,
            'email_2' => $request->email_2,
            'mobile_no' => $request->mobile_no,
            'mobile_no_2' => $request->mobile_no_2,
            'map' => $request->map_iframe,
            'address' => $request->address,
            'branch_id' => $branch_data->id,
            'shop_id' => $branch_data->shop_id,
        ];

        $webInformation = EcommWebInformation::updateOrCreate(
            ['shop_id' => $branch_data->shop_id],
            $webInformationData
        ) ;

        if($webInformation) {
            return response()->json(['success' => 'Data Submitted successfully','errors' =>'']) ;
        }else{
            return response()->json(['errors' => 'Data Submitted failed'], 425) ;
        }

      }


      public function schemes($id) {

        //$user = User::find($id) ;
		$user = User::with('schemes')->find($id) ;
        $schemes = Scheme::all() ;
        
        return view('admin.users.schemes.assign',compact('schemes','user')) ;

      }

      public function scheme_assign(Request $request) {

        $validator = Validator::make($request->all(), [

            'schemes' => 'required|array|min:1',

        ]) ;

        $schemes = $request->input('schemes') ;
        
        $shopId = $request->input('shop_id');
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }
        
        $num =0;
        foreach ($schemes as $scheme) {
            
            //echo $scheme;

            //$t_scheme = Scheme::find($scheme)->first() ;
            $t_scheme = Scheme::where('id',$scheme)->first() ;


            $s_scheme = ShopScheme::where(['shop_id'=>$shopId,'scheme_id'=>$scheme])->exists() ;

            $scheme_id = $scheme ;
            
            if(!$s_scheme) {

                ShopScheme::create([
					'url_part'=>str()->random(),
                    'scheme_head' => $t_scheme->scheme_head,
                    'scheme_sub_head' => $t_scheme->scheme_sub_head,
                    'scheme_detail_one' => $t_scheme->scheme_detail_one,
                    'total_amt' => $t_scheme->scheme_amount,
                    'scheme_validity' => $t_scheme->scheme_validity,
                    'lucky_draw'=> "{$t_scheme->lucky_draw}",
                    'emi_range_start' => $t_scheme->scheme_emi,
                    'emi_range_end' => $t_scheme->scheme_emi,
                    // 'emi_date' => $t_scheme->emi_date,
                    'scheme_interest' => $t_scheme->interest_type,
                    'interest_type' => $t_scheme->scheme_interest_scale,
                    'interest_rate' => $t_scheme->scheme_interest,
                    'interest_amt' => $t_scheme->scheme_interest_value,
					'scheme_date_fix'=>$t_scheme->start_date_fix,
                    'shop_id' => $shopId,
                    'scheme_id' => $scheme_id,
                ]);

            }
            $num++;
        }

        return response()->json(['success' => 'Data Saved successfully','errors' => '']) ;

    }

}



