<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SoftwareProduct ;
use App\Models\Role ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

class SoftwareProductController extends Controller {

    /**
     * Display a listing of the resource.
     */

     public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = SoftwareProduct::orderBy('id', 'desc') ;

        if($request->title) { $query->where('title', 'like', '%' . $request->title . '%'); }

        $softwareproducts = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('admin.softwareproducts.disp', compact('softwareproducts'))->render();
            return response()->json(['html' => $html]);

        }

        return view('admin.softwareproducts.index',compact('softwareproducts'));

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create() {

        return view('admin.softwareproducts.create') ;

    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request) {

        $role_name = getInitials($request->title).' Shop Owner' ;

        $request->merge(['role_name' => $role_name]) ;

        $validator = Validator::make($request->all(), [

            'title' => 'required',
            'role_name' => 'required|unique:roles,name',
            'price' => 'required|numeric',
            'thumbnail_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:1024',
            'banner_image1' => 'required|image|mimes:jpeg,png,jpg,webp|max:1024',
            'description' => 'required',
        ],[
            'thumbnail_image.required' => 'Thumbnail image is required',
            'thumbnail_image.image' => 'Thumbnail image must be an image file',
            'thumbnail_image.mimes' => 'Thumbnail image must be a file of type: jpeg, png, jpg, Webp',
            'thumbnail_image.max' => 'Thumbnail image size must not exceed 1MB',
            'banner_image1.required' => 'Banner Image is required',
            'banner_image1.image' => 'Banner Image must be an image file',
            'banner_image1.mimes' => 'Banner Image must be a file of type: jpeg, png, jpg, Webp',
            'banner_image1.max' => 'Banner Image size must not exceed 1MB',
        ]) ;

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $banner_image1 = '' ;
        $thumbnail_image = '' ;
        $imageData = [];
        $uploadedFiles = [];

        $imagePaths = [
            'thumbnail_image' => 'assets/images/thumbnail',
            'banner_image1' => 'assets/images/banner'
        ] ;

        foreach ($imagePaths as $inputName => $folder) {

            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $imageName = generateUniqueImageName($file, $folder);
                $imageData[$inputName.'1'] = $imageName;
                $file->move(public_path($folder), $imageName);

            } else {
                $imageData[$inputName] = '' ;
            }
        }

        $request->merge($imageData) ;

        $role = Role::create(['name'=>$request->role_name]) ;

        $softwareproduct = SoftwareProduct::create([

            'title' => $request->title ,
            'price' => $request->price ,
            'image' => $request->thumbnail_image1 ?? '' ,
            'banner_image' => $request->banner_image11 ?? '' ,
            'description' => $request->description ,
            'role_id' => $role->id ,
            'meta_title' => $request->meta_title ,
            'meta_description' => $request->meta_description ,

        ]) ;

        if($softwareproduct) {

            return response()->json(['success' => 'Saved Successfully']) ;

        }else{

            foreach ($uploadedFiles as $filePath) {
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            return response()->json(['errors' => 'Save Failed'], 425) ;

        }

    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(SoftwareProduct $softwareproduct) {

        return view('admin.softwareproducts.edit', compact('softwareproduct')) ;

    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, SoftwareProduct $softwareproduct) {

        $validator = Validator::make($request->all(), [

            'title' => 'required',
            'price' => 'required|numeric',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'banner_image1' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'description' => 'required',
        ],[
            // 'thumbnail_image.required' => 'Thumbnail image is required',
            'thumbnail_image.image' => 'Thumbnail image must be an image file',
            'thumbnail_image.mimes' => 'Thumbnail image must be a file of type: jpeg, png, jpg, Webp',
            'thumbnail_image.max' => 'Thumbnail image size must not exceed 1MB',
            // 'banner_image1.required' => 'Banner Image is required',
            'banner_image1.image' => 'Banner Image must be an image file',
            'banner_image1.mimes' => 'Banner Image must be a file of type: jpeg, png, jpg, Webp',
            'banner_image1.max' => 'Banner Image size must not exceed 1MB',
        ]) ;

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors(),], 422) ;

        }

        $banner_image1 = '' ;
        $thumbnail_image = '' ;
        $imageData = [] ;
        $uploadedFiles = [] ;

        $old_image = $softwareproduct->image  ;
        $old_banner_image = $softwareproduct->banner_image ;

        $imagePaths = [
            'thumbnail_image' => 'assets/images/thumbnail',
            'banner_image1' => 'assets/images/banner'
        ] ;

        foreach ($imagePaths as $inputName => $folder) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $imageName = generateUniqueImageName($file, $folder);
                $file->move(public_path($folder), $imageName);
                $uploadedFiles[] = public_path($folder . '/' . $imageName);
                $imageData[$inputName.'1'] = $imageName ;

            }else{

                $imageName = ($inputName=='thumbnail_image') ? $old_image :  $old_banner_image ;
                $imageData[$inputName.'1'] = $imageName ;

            }
        }

        $request->merge($imageData) ;

        $product = $softwareproduct->update([

            'title' => $request->title ,
            'price' => $request->price ,
            'image' => $request->thumbnail_image1 ?? $softwareproduct->image,
            'banner_image' => $request->banner_image11 ?? $softwareproduct->banner_image,
            'description' => $request->description ,
            'meta_title' => $request->meta_title ,
            'meta_description' => $request->meta_description ,

        ]) ;

        if($product) {

            foreach ($imagePaths as $inputName => $folder) {
                if ($request->hasFile($inputName)) {
                    $img = ($inputName=='thumbnail_image') ? $old_image :  $old_banner_image ;
                    delete_image($folder.'/'.$img) ;
                }
            }

            return response()->json(['success' => 'Updated Successfully']) ;

        }else{

            foreach ($uploadedFiles as $filePath) {
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            return response()->json(['errors' => 'Updated Failed'], 425) ;

        }

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(SoftwareProduct $softwareproduct) {

        $old_image = $softwareproduct->image  ;
        $old_banner_image = $softwareproduct->banner_image ;

        $imagePaths = [
            'thumbnail_image' => 'assets/images/thumbnail',
            'banner_image1' => 'assets/images/banner'
        ] ;

        $softwareproduct->delete() ;

        foreach ($imagePaths as $inputName => $folder) {
                $img = ($inputName=='thumbnail_image') ? $old_image :  $old_banner_image ;
                delete_image($folder.'/'.$img) ;
        }

        return redirect()->route('softwareproducts.index')->with('success', 'Delete successfully.');

    }

}
