<?php

namespace App\Http\Controllers;

use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CenterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function nearby()
    {
        $centers = Center::all();
        return view('centers.nearby', compact('centers'));
    }

    public function search(Request $request)
    {
        $query = Center::query();

        if ($request->has('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->has(['latitude', 'longitude'])) {
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            // Calculate distance using Haversine formula
            $query->select('*')
                ->selectRaw('
                    (6371 * acos(
                        cos(radians(?)) * cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) * sin(radians(latitude))
                    )) AS distance
                ', [$latitude, $longitude, $latitude])
                ->orderBy('distance');
        }

        $centers = $query->get();

        if ($request->wantsJson()) {
            return response()->json(['centers' => $centers]);
        }

        return view('centers.index', compact('centers'));
    }

    public function index()
    {
        $centers = Center::all();
        return view('centers.index', compact('centers'));
    }
} 