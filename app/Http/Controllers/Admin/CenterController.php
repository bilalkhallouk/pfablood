<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Center;

class CenterController extends Controller
{
    public function index()
    {
        $centers = \App\Models\Center::with('bloodStocks')->get();
        return view('admin.centers.index', compact('centers'));
    }

    public function create()
    {
        return view('admin.centers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);
        Center::create($validated);
        return redirect()->route('admin.centers')->with('success', 'Centre ajouté avec succès.');
    }

    public function edit($id)
    {
        $center = Center::findOrFail($id);
        return view('admin.centers.edit', compact('center'));
    }

    public function update(Request $request, $id)
    {
        $center = Center::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);
        $center->update($validated);
        return redirect()->route('admin.centers')->with('success', 'Centre mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $center = Center::findOrFail($id);
        $center->delete();
        return redirect()->route('admin.centers')->with('success', 'Centre supprimé avec succès.');
    }
}
