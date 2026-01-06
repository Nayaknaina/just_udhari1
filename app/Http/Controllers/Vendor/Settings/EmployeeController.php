<?php

namespace App\Http\Controllers\Vendor\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\ShopBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller {

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1) ;

        $query = User::with('shop')->whereNotIn('user_type',[0])->orderBy('id', 'desc') ;
        Shopwhere($query) ;

        if($request->employee_name) { $query->where('name', 'like', '%' . $request->employee_name . '%'); }

        $users = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.settings.employees.disp', compact('users'))->render() ;
            return response()->json(['html' => $html]) ;

        }

        return view('vendors.settings.employees.index',compact('users')) ;

    }

    public function create() {

        $roles = Role::where('shop_id',app('userd')->shop_id)->get();
        $query = ShopBranch::where('shop_id',app('userd')->shop_id) ;
            MyBranchwhere($query) ;
        $branches = $query->get() ;

        return view('vendors.settings.employees.create',['roles'=>$roles,'branches'=>$branches]) ;

    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'name'=>'required',
            'branch_id'=>'required',
            'email' => 'required|email|unique:users',
            'password'=>'required|string|min:4',
            'mpin' => 'required|digits:4',
            'roles'=>'required',
            'mobile_no' => ['required', 'string', 'digits:10', 'regex:/^[0-9]+$/', 'unique:users,mobile_no'],
        ],['branch_id'=>'Shop Branch Name is required']
        ) ;

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }

        $user = User::create([

            'name'=>$request->name,
            'email'=>$request->email,
            'password'=> bcrypt($request->password),
            'mpin'=> bcrypt($request->mpin),
            'team_leader'=> $request->team_leader,
            'mobile_no'=> $request->mobile_no,
            'branch_id'=> $request->branch_id ,
            'shop_id'=> app('userd')->shop_id ,

        ]) ;

        // dd($user) ;

        $roleIds = $request->roles;

        $user->syncRoles($request->roles) ;

        if($user) {
            return response()->json(['success' => 'Data Saved successfully']);
        }else{
            return response()->json(['errors' => 'Data failed to saved!'], 425) ;
        }

    }

    public function edit(User $employee) {

        $role = Role::where('shop_id',app('userd')->shop_id)->get();

        $query = ShopBranch::where('shop_id',app('userd')->shop_id) ;
            MyBranchwhere($query) ;
        $branches = $query->get() ;

       return view('vendors.settings.employees.edit',['user'=>$employee,'roles' => $role,'branches'=>$branches]) ;

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function update(Request $request, User $employee) {

        // Merge all validation rules into one
        $rules = [
            'name' => 'required',
            'branch_id' => 'required',
            'email' => 'required|email|unique:users,email,' . $employee->id . ',id',
            'roles' => 'required',
            'mobile_no' => 'required|string|digits:10|regex:/^[0-9]+$/|unique:users,mobile_no,' . $employee->id . ',id',
        ];

        // Conditionally add password validation
        if ($request->password != null) {
            $rules['password'] = 'required|string|min:4';
        }

        // Conditionally add mpin validation
        if ($request->mpin != null) {
            $rules['mpin'] = 'required|string|min:4';
        }

        // Custom error messages
        $messages = [
            'branch_id.required' => 'Shop Branch Name is required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }

        $user = $employee->update([

            'name'=>$request->name,
            'email'=>$request->email,
            'password' => $request->password ? bcrypt($request->password) : $employee->password,
            'mpin' => $request->mpin ? bcrypt($request->mpin) : $employee->mpin,
            'team_leader'=> $request->team_leader,
            'mobile_no'=> $request->mobile_no,
            'branch_id'=> $request->branch_id ,
            'shop_id'=> app('userd')->shop_id ,

        ]) ;

        $roleIds = $request->roles;

        $employee->syncRoles($request->roles) ;

        if($user) {
            return response()->json(['success' => 'Data Saved successfully']);
        }else{
            return response()->json(['errors' => 'Data failed to saved!'], 425) ;
        }

     }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
       public function destroy(User $user)
     {
         $user->delete();
         return redirect()->back()->withSuccess('User deleted !!!');
     }

}
