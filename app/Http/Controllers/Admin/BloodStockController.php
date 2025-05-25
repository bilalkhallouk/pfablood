<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodStock;
use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BloodStockController extends Controller
{
    public function index(Request $request)
    {
        $query = BloodStock::with('center');

        // Apply filters
        if ($request->filled('center_id')) {
            $query->where('center_id', $request->center_id);
        }

        if ($request->filled('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'critical':
                    $query->whereRaw('(units < min_threshold * 0.5)');
                    break;
                case 'low':
                    $query->whereRaw('(units < min_threshold AND units >= min_threshold * 0.5)');
                    break;
                case 'normal':
                    $query->whereRaw('(units >= min_threshold)');
                    break;
            }
        }

        if ($request->filled('min_stock')) {
            $query->where('units', '>=', $request->min_stock);
        }

        // Apply sorting
        $sortField = $request->sort_field ?? 'created_at';
        $sortDirection = $request->sort_direction ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        // Get paginated results
        $stocks = $query->paginate(10)->withQueryString();
        $centers = Center::all();

        // Get statistics for charts
        $stockDistribution = BloodStock::select('blood_type', DB::raw('SUM(units) as total_units'))
            ->groupBy('blood_type')
            ->pluck('total_units', 'blood_type')
            ->toArray();

        $stockStatus = [
            'normal' => BloodStock::whereRaw('units >= min_threshold')->count(),
            'low' => BloodStock::whereRaw('units < min_threshold AND units >= min_threshold * 0.5')->count(),
            'critical' => BloodStock::whereRaw('units < min_threshold * 0.5')->count(),
        ];

        return view('admin.blood_stocks.index', compact(
            'stocks',
            'centers',
            'stockDistribution',
            'stockStatus'
        ));
    }

    public function edit($id)
    {
        $stock = BloodStock::with('center')->findOrFail($id);
        return view('admin.blood_stocks.edit', compact('stock'));
    }

    public function update(Request $request, $id)
    {
        $stock = BloodStock::findOrFail($id);
        
        $validated = $request->validate([
            'units' => 'required|integer|min:0',
            'min_threshold' => 'required|integer|min:0',
        ]);

        $stock->units = $validated['units'];
        $stock->min_threshold = $validated['min_threshold'];
        $stock->save();

        // Check if stock is low and send notification
        if ($stock->units < $stock->min_threshold) {
            // You can implement notification logic here
            // For example, using Laravel's notification system
        }

        return redirect()
            ->route('admin.blood-stocks.index')
            ->with('success', 'Stock mis à jour avec succès.');
    }

    public function create()
    {
        $centers = Center::all();
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        return view('admin.blood_stocks.create', compact('centers', 'bloodTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'center_id' => 'required|exists:centers,id',
            'blood_type' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units' => 'required|integer|min:0',
            'min_threshold' => 'required|integer|min:0',
        ]);

        $stock = BloodStock::create($validated);

        // Check if initial stock is low
        if ($stock->units < $stock->min_threshold) {
            // You can implement notification logic here
        }

        return redirect()
            ->route('admin.blood-stocks.index')
            ->with('success', 'Stock ajouté avec succès.');
    }

    public function destroy($id)
    {
        $stock = BloodStock::findOrFail($id);
        $stock->delete();

        return redirect()
            ->route('admin.blood-stocks.index')
            ->with('success', 'Stock supprimé avec succès.');
    }
}
