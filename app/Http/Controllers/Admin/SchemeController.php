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
    public function index(Request $request){

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = Scheme::orderBy('id', 'desc') ;

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

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'heading' => 'required|string',
            'subheading' => 'required|string',
            'description'=>'required|string',
            'valid'=>'required|numeric',
            'scheme_amt'=>'required|numeric',
            'emi_amt'=>'required|numeric',
            'lucky_draw'=>'required',
            'interest'=>'required',
            'start'=>'required',
            'interest_scale'=>'required_if:interest,Yes',
            'interest_value' => 'required_if:interest,Yes',

        ],[
            'heading.required'=>'Scheme Heading Required !',
            'heading.string'=>'Scheme Heading must be Valid String',
            'subheading.required'=>'Scheme Sub Heading Required !',
            'subheading.string'=>'Scheme Sub Heading must be Valid String',
            'description.required'=>"Please Enter The Scheme Detail !",
            'description.string'=>"Scheme Detail should be Valid String !",
            'valid.required'=>'Please Enter the Validity Duration !',
            'scheme_amt.required'=>'Please Enter EMI Amt',
            'interest.required'=>'Please Select the Interest !',
            'start.required'=>"Please Select the start Date",
            'interest_value.required_if'=>'Please Enter the Interest Value !',
            'interest_scale.required_if'=>'Please Select the Interest Scale (Rs./ %)',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->emi_amt > $request->scheme_amt) {
                $validator->errors()->add('emi_amt', 'The EMI amount cannot be greater than the scheme amount.');
            }
        });

        $validator->after(function ($validator) use ($request) {
            if ($request->input('interest') === 'Yes' && $request->input('lucky_draw') === '0') {
                if (!$request->filled('interest_value')) {
                    $validator->errors()->add('interest_value', 'Please Enter the Interest Value!');
                }
                if (!$request->filled('interest_scale')) {
                    $validator->errors()->add('interest_scale', 'Please Select the Interest Scale (Rs./%)');
                }
            }
        });

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $interest = $request->lucky_draw == '1' ?  'No' :  $request->interest ;

            $input_arr = [
                'scheme_unique'=>uniqid().time(),
                'scheme_head' => $request->heading ,
                'scheme_sub_head' => $request->subheading ,
                'scheme_detail_one' => $request->description ,
                'scheme_validity' => $request->valid ,
                'scheme_amount' => $request->scheme_amt ,
                'scheme_emi' => $request->emi_amt ,
                'lucky_draw'=>"{$request->lucky_draw}",
                'interest_type' => $interest ,
                'start_date_fix'=>$request->start,
                'meta_title' => $request->meta_title ,
                'meta_description' => $request->meta_description ,
            ] ;

            if($interest=='Yes'){

                $interest_value = ($request->interest_scale == 'per' ) ? ($request->interest_value * $request->emi_amt ) / 100 : $request->interest_value ;

                $input_arr['scheme_interest_scale'] = $request->interest_scale ;
                $input_arr['scheme_interest'] = $request->interest_value ;
                $input_arr['scheme_interest_value'] = $interest_value ;

            }

            $scheme = Scheme::create($input_arr) ;

            if($scheme) {
                return response()->json(['success' => 'Scheme Info Saved Successfully']) ;
            }else{
                return response()->json(['errors' => 'Scheme Info Saving Failed'], 425) ;
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

        // print_r($request->toArray());
        // exit();

        $validator = Validator::make($request->all(), [
            'heading' => 'required|string',
            'subheading' => 'required|string',
            'description'=>'required|string',
            'valid'=>'required|numeric',
            'scheme_amt'=>'required|numeric',
            'emi_amt'=>'required|numeric',
            'lucky_draw'=>'required',
            'interest'=>'required',
            'interest_scale'=>'required_if:interest,Yes',
            'interest_value' => 'required_if:interest,Yes',
            'start'=>'required',

        ],[
            'heading.required'=>'Scheme Heading Required !',
            'heading.string'=>'Scheme Heading must be Valid String',
            'subheading.required'=>'Scheme Sub Heading Required !',
            'subheading.string'=>'Scheme Sub Heading must be Valid String',
            'description.required'=>"Please Enter The Scheme Detail !",
            'description.string'=>"Scheme Detail should be Valid String !",
            'valid.required'=>'Please Enter the Validity Duration !',
            'scheme_amt.required'=>'Please Enter EMI Amt',
            'emi_amt.erquired'=>'EMI Amount Required ',
            'lucky_draw.required'=>"Lucky Draw Required",
            'interest.required'=>'Please Select the Interest !',
            'interest_value.required_if'=>'Please Enter the Interest Value !',
            'interest_scale.required_if'=>'Please Select the Interest Scale (Rs./ %) !',
            'start.required'=>'Please select start Date !',
        ]);
        
        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }
            
            $input_arr = [
                'scheme_unique'=>uniqid().time(),
                'scheme_head' => $request->heading ,
                'scheme_sub_head' => $request->subheading ,
                'scheme_detail_one' => $request->description ,
                'scheme_validity' => $request->valid ,
                'lucky_draw'    =>  "{$request->lucky_draw}" ,
                'scheme_amount' => $request->scheme_amt ,
                'scheme_emi' => $request->emi_amt ,
                'interest_type' => $request->interest ,
                'start_date_fix'=>$request->start,
                'meta_title' => $request->meta_title ,
                'meta_description' => $request->meta_description ,
            ] ;

            // print_r($input_arr);
            // exit();

            if($request->interest=='Yes'){

                $interest_value = ($request->interest_scale == 'per' ) ? ($request->interest_value * $request->emi_amt ) / 100 : $request->interest_value ;

                $input_arr['scheme_interest_scale'] = $request->interest_scale ;
                $input_arr['scheme_interest'] = $request->interest_value ;
                $input_arr['scheme_interest_value'] = $interest_value ;

            }else{

                $input_arr['scheme_interest_scale'] = 'amt' ;
                $input_arr['scheme_interest'] = 0 ;
                $input_arr['scheme_interest_value'] = 0 ;

            }
            // print_r($input_arr);
            // exit();
            $schemes = $scheme->update($input_arr);

            if($schemes) {
                return response()->json(['success' => 'Scheme Info Updated Successfully']) ;
            }else{
                return response()->json(['errors' => 'Scheme Info Updated Failed'], 425) ;
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
