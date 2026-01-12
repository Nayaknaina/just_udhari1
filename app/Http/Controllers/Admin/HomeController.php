<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactEnquiry;
use Illuminate\Http\Request ;

class HomeController extends Controller {

    public function index() {

        return view('admin.dashboard.home') ;

    }

       public function contactindex() {

        return view('admin.contact.index') ;

    }

    public function contactData(Request $request) {
        
        $perPage = $request->input('entries', 25); 
        $allowed = [10, 25, 50, 100];
        $perPage = in_array($perPage, $allowed) ? (int)$perPage : 25;

        $currentPage = max(1, (int)$request->input('page', 1));
        $query = ContactEnquiry::orderBy('created_at','desc');
        
      
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone_number', 'like', "%$search%")
                ->orWhere('subject', 'like', "%$search%");
            });
        }
      
        $enquiries = $query->paginate($perPage, ['*'], 'page', $currentPage);

      
        $html = view('admin.contact.contactbody', compact('enquiries'))->render();
        $paging =   view('layouts.theme.datatable.pagination', ['paginator' => $enquiries,'type'=>1])->render();
        return response()->json(['html'=>$html,'paging'=>$paging]);
     
    }

   
    public function contactDestroy($id)
    {
        $enquiry = ContactEnquiry::findOrFail($id);
        $enquiry->delete();

        return response()->json(['success' => true, 'message' => 'Enquiry deleted successfully.']);
    }

    public function markAsRead($id)
    {
      
        $enquiry = ContactEnquiry::findOrFail($id);
 
        $enquiry->update(['is_read' => true]);
      
        return response()->json(['success' => true]);
    }

}
