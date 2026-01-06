<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class StockController extends Controller
{

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {

        $perPage = $request->input('entries') ;
        $currentPage = $request->input('page', 1);

        $query = Stock::orderBy('id', 'desc') ;

        if($request->Stock_name) { $query->where('Stock_name', 'like', '%' . $request->Stock_name . '%'); }

        Shopwhere($query) ;

        $stocks = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($request->ajax()) {

            $html = view('vendors.stocks.disp', compact('stocks'))->render();
            return response()->json(['html' => $html]);

        }

        return view('vendors.stocks.index',compact('stocks'));

    }

    public function destroy(Stock $stock) {

        $stock->delete() ;
        return redirect()->route('stocks.index')->with('success', 'Delete successfully.');

    }

}
