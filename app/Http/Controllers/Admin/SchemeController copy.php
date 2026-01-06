<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Role ;
use App\Models\Scheme ;

class SchemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);
        $query = Scheme::orderBy('scheme_head', 'desc') ;
        if($request->title) { $query->where('v', 'like', '%' . $request->title . '%'); }
            $schemes = $query->paginate($perPage, ['*'], 'page', $currentPage);
        if ($request->ajax()) {

            $html = view('admin.schemes.disp', compact('schemes'))->render();
            return response()->json(['html' => $html]);

        }
        return view('admin.schemes.index');
    }

    // public function schemedetail($id=null){
    //     $schemedetail = Scheme::find($id);
    //     return view('admin.schemes.schemedetail',compact('schemedetail'));
    // }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.schemes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'heading' => 'required|string',
            'subheading' => 'required|string',
            'detail_one'=>'required|string',
            'valid'=>'required|numeric|digits_between:1,2',
            'valid_scale'=>'required',
            'emi'=>'required',
            'interest'=>'required',
            'interest_scale'=>'required_if:interest,1',
            'interest_value' => 'required_if:interest,1',
            //'role_name' => 'required|unique:roles,name',

        ],[
            'heading.required'=>'Scheme Heading Required !',
            'heading.string'=>'Scheme Heading must be Valid String',
            'subheading.required'=>'Scheme Sub Heading Required !',
            'subheading.string'=>'Scheme Sub Heading must be Valid String',
            'detail_one.required'=>"Please Enter The Scheme Detail !",
            'detail_one.string'=>"Scheme Detail should be Valid String !",
            'valid.required'=>'Please Enter the Validity Duration !',
            'valid_scale.required'=>'Please Select the Valiidity Scale ( Day/Month/Year) !',
            'emi.required'=>'Please Enter the EMI Value !',
            'interest.required'=>'Please Select the Interest !',
            'interest_value.required_if'=>'Please Enter the Interest Value !',
            'interest_scale.required_if'=>'Please Select the Interest Scale (Rs./ %)',
        ]);
        //$role_name = getInitials($request->title).' Shop Owner' ;

        //$request->merge(['role_name' => $role_name]) ;


        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }else{
            $tr = [];
            $nowtbody = [];
            $tableone = "";
            $head_one = false;
            foreach($request->tableone['thead'] as $key=>$value){
                if($value != ""){
                    $head_one = true;
                }
            }
            if($head_one){
                $one_head_count = count($request->tableone['thead']);
                $tbodyone = $request->tableone['tbody'];
                $num_one = 1;
                foreach($tbodyone as $key=>$values){
                    array_push($tr,$values);
                    if($num_one == $one_head_count){
                        array_push($nowtbody,$tr);
                        $num_one = 1;
                        $tr = [];
                    }else{
                        $num_one++;
                    }
                }
                $tableone = json_encode(["thead"=>[$request->tableone['thead']],"tbody"=>$nowtbody]);
            }
            $tr = [];
            $nowtbody = [];
            $tabletwo = "";
            $head_two = false;
            foreach($request->tabletwo['thead'] as $key=>$value){
                if($value != ""){
                    $head_two = true;
                }
            }
            if($head_two){
                $two_head_count = count($request->tabletwo['thead']);
                $tbodytwo = $request->tabletwo['tbody'];
                $num_two = 1;
                foreach($tbodytwo as $key=>$values){
                    array_push($tr,$values);
                    if($num_two == $two_head_count){
                        array_push($nowtbody,$tr);
                        $num_two = 1;
                        $tr = [];
                    }else{
                        $num_two++;
                    }
                }
                $tabletwo = json_encode(["thead"=>[$request->tabletwo['thead']],"tbody"=>$nowtbody]);
            }

            // if ($request->hasFile('thumbnail_image')) {

            //     $thumbnail_image = time() . rand() . '.' . $request->thumbnail_image->extension() ;
            //     $request->thumbnail_image->move(public_path('assets/images/thumbnail'), $thumbnail_image) ;

            //     $request->merge(['image' => $thumbnail_image]) ;

            // }

            // if ($request->hasFile('banner_image1')) {

            //     $banner_image1 = time() . rand() . '.' . $request->banner_image1->extension() ;
            //     $request->banner_image1->move(public_path('assets/images/banner'), $banner_image1) ;

            // }else {

            //     $banner_image1 = '' ;

            // }

            //$request->merge(['banner_image' => $banner_image1]) ;

            //$role = Role::create(['name'=>$request->role_name]) ;

            $input_arr = [
                'scheme_unique'=>uniqid().time(),
                'scheme_head' => $request->heading ,
                'scheme_sub_head' => $request->subheading ,
                'scheme_detail_one' => $request->detail_one,
                'scheme_table_one' => $tableone ,
                'scheme_detail_two' => $request->detail_two,
                'scheme_table_two' => $tabletwo ,
                'scheme_validity' => $request->valid ,
                'scheme_validity_scale' => $request->valid_scale ,
                'scheme_emi'=>$request->emi,
                'scheme_interest'=>$request->interest,
                // 'role_id' => $role->id ,
                'meta_title' => $request->meta_title ,
                'meta_description' => $request->meta_description ,
            ];
            if($request->interest==1){
                $input_arr['scheme_interest_value']=$request->interest;
                $input_arr['scheme_interest_scale']=$request->interest_scale;
            }
            $scheme = Scheme::create($input_arr);
            // $softwareproduct = Scheme::create([



            //     'role_id' => $role->id ,
            //     'meta_title' => $request->meta_title ,
            //     'meta_description' => $request->meta_description ,

            // ]) ;

            if($scheme) {

                return response()->json(['success' => 'Scheme Info Saved Successfully']) ;

            }else{

                return response()->json(['errors' => 'Scheme Info Saving Failed'], 425) ;

            }
        }

    }

    /**
     * Display the specified resource.
     */

    public function show(string $id=null) {

        $schemedetail = Scheme::find($id) ;
        return view('admin.schemes.schemedetail',compact('schemedetail')) ;

    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(string $id=null) {

        $scheme = Scheme::find($id) ;
        return view("admin.schemes.edit",compact('scheme')) ;

    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Scheme $scheme){

        $validator = Validator::make($request->all(), [
            'heading' => 'required|string',
            'subheading' => 'required|string',
            'detail_one'=>'required|string',
            'valid'=>'required|numeric|digits_between:1,2',
            'valid_scale'=>'required',
            'emi'=>'required',
            'interest'=>'required',
            'interest_scale'=>'required_if:interest,1',
            'interest_value' => 'required_if:interest,1',
            //'role_name' => 'required|unique:roles,name',

        ],[
            'heading.required'=>'Scheme Heading Required !',
            'heading.string'=>'Scheme Heading must be Valid String',
            'subheading.required'=>'Scheme Sub Heading Required !',
            'subheading.string'=>'Scheme Sub Heading must be Valid String',
            'detail_one.required'=>"Please Enter The Scheme Detail !",
            'detail_one.string'=>"Scheme Detail should be Valid String !",
            'valid.required'=>'Please Enter the Validity Duration !',
            'valid_scale.required'=>'Please Select the Valiidity Scale ( Day/Month/Year) !',
            'emi.required'=>'Please Enter the EMI Value !',
            'interest.required'=>'Please Select the Interest !',
            'interest_value.required_if'=>'Please Enter the Interest Value !',
            'interest_scale.required_if'=>'Please Select the Interest Scale (Rs./ %)',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422) ;
        }else{
            $tr = [];
            $nowtbody = [];
            $tableone = "";
            $head_one = false;
            foreach($request->tableone['thead'] as $key=>$value){
                if($value != ""){
                    $head_one = true;
                }
            }
            if($head_one){
                $one_head_count = count($request->tableone['thead']);
                $tbodyone = $request->tableone['tbody'];
                $num_one = 1;
                foreach($tbodyone as $key=>$values){
                    array_push($tr,$values);
                    if($num_one == $one_head_count){
                        array_push($nowtbody,$tr);
                        $num_one = 1;
                        $tr = [];
                    }else{
                        $num_one++;
                    }
                }
                $tableone = json_encode(["thead"=>[$request->tableone['thead']],"tbody"=>$nowtbody]);
            }
            $tr = [];
            $nowtbody = [];
            $tabletwo = "";
            $head_two = false;
            foreach($request->tabletwo['thead'] as $key=>$value){
                if($value != ""){
                    $head_two = true;
                }
            }
            if($head_two){
                $two_head_count = count($request->tabletwo['thead']);
                $tbodytwo = $request->tabletwo['tbody'];
                $num_two = 1;
                foreach($tbodytwo as $key=>$values){
                    array_push($tr,$values);
                    if($num_two == $two_head_count){
                        array_push($nowtbody,$tr);
                        $num_two = 1;
                        $tr = [];
                    }else{
                        $num_two++;
                    }
                }
                $tabletwo = json_encode(["thead"=>[$request->tabletwo['thead']],"tbody"=>$nowtbody]);
            }
            $input_arr = [
                'scheme_head' => $request->heading ,
                'scheme_sub_head' => $request->subheading ,
                'scheme_detail_one' => $request->detail_one,
                'scheme_table_one' => $tableone ,
                'scheme_detail_two' => $request->detail_two,
                'scheme_table_two' => $tabletwo ,
                'scheme_validity' => $request->valid ,
                'scheme_validity_scale' => $request->valid_scale ,
                'scheme_emi'=>$request->emi,
                'scheme_interest'=>$request->interest,
                // 'role_id' => $role->id ,
                'meta_title' => $request->meta_title ,
                'meta_description' => $request->meta_description ,
            ];
            if($request->interest==1){
                $input_arr['scheme_interest_value']=$request->interest;
                $input_arr['scheme_interest_scale']=$request->interest_scale;
            }
            $scheme = $scheme->update($input_arr);
            if($scheme) {
                return response()->json(['success' => 'Scheme Info Changed Successfully']) ;
            }else{
                return response()->json(['errors' => 'Scheme Info Changing Failed'], 425) ;
            }
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scheme $scheme)
    {
        $scheme->delete();
        return redirect()->route('schemes.index')->with('success', 'Delete successfully.');
    }
}
