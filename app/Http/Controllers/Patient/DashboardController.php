<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('patient.patientdashboard');
    }
}