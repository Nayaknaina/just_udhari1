<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\TableStructure;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */

      // Display a listing of the tables
    public function index()
    {
        $tables = TableStructure::all();

        return view('vendors.schemes.tables.index', compact('tables'));
    }

    // Show the form for creating a new table
    public function create()
    {
        return view('vendors.schemes.tables.create');
    }

    // Store a newly created table in the database
    public function store(Request $request)
    {
        $request->validate([
            'structure' => 'required|json',
            'rows' => 'required|json',
        ]);

        TableStructure::create([
            'structure' => $request->structure,
            'rows' => $request->rows,
        ]);

        return redirect()->route('vendors.schemes.tables.index')->with('success', 'Table created successfully.');
    }

    // Display the specified table
    public function show($id){

        $table = TableStructure::findOrFail($id);
        return view('vendors.schemes.tables.show', compact('table'));

    }

    // Show the form for editing the specified table
    public function edit($id)
    {
        $table = TableStructure::findOrFail($id);
        $structure = json_decode($table->structure, true);
        $rows = json_decode($table->rows, true);

        return view('vendors.schemes.tables.edit', compact('table', 'structure', 'rows'));
    }

    // Update the specified table in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'structure' => 'required|json',
            'rows' => 'required|json',
        ]);

        $table = TableStructure::findOrFail($id);
        $table->update([
            'structure' => $request->structure,
            'rows' => $request->rows,
        ]);

        return redirect()->route('vendors.schemes.tables.index')->with('success', 'Table updated successfully.');
    }

    // Remove the specified table from the database
    public function destroy($id)
    {
        $table = TableStructure::findOrFail($id);
        $table->delete();

        return redirect()->route('vendors.schemes.tables.index')->with('success', 'Table deleted successfully.');
    }
}
