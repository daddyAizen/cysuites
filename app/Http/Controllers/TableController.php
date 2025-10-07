<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::all();
        return inertia('Tables/Index', compact('tables'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'seats' => 'required|integer|min:1',
            'status' => 'required|in:available,unavailable',
        ]);
        Table::create($request->all());

        return redirect()->back()->with('success', 'Table created successfully.');
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'seats' => 'required|integer|min:1',
            'status' => 'required|in:available,unavailable',
        ]);

        $table->update($request->all());

        return redirect()->back()->with('success', 'Table updated successfully.');
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return redirect()->back()->with('success', 'Table deleted successfully.');
    }
}
