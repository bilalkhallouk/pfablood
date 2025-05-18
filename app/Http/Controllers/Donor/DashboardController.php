<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('donor.donordashboard');
    }
}