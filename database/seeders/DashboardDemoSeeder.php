<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BloodStock;
use App\Models\DonationAppointment;
use App\Models\BloodRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DashboardDemoSeeder extends Seeder
{
    private $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
    private $firstNames = ['Mohamed', 'Ahmed', 'Fatima', 'Aisha', 'Omar', 'Youssef', 'Mariam', 'Sara', 'Hassan', 'Karim'];
    private $lastNames = ['Alami', 'Benali', 'Idrissi', 'Tazi', 'Fassi', 'Bennani', 'Chraibi', 'Lahlou', 'Berrada', 'Ziani'];

    public function run()
    {
        // Create donors (around 4000)
        $this->createDonors();

        // Create patients (around 2000)
        $this->createPatients();

        // Create blood stock
        $this->createBloodStock();

        // Create donation appointments
        $this->createDonationAppointments();

        // Create blood requests
        $this->createBloodRequests();
    }

    private function createDonors()
    {
        $count = 4129; // Match the number in your screenshot
        for ($i = 0; $i < $count; $i++) {
            User::create([
                'name' => $this->firstNames[array_rand($this->firstNames)] . ' ' . 
                         $this->lastNames[array_rand($this->lastNames)],
                'email' => 'donor' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'donor',
                'blood_type' => $this->bloodTypes[array_rand($this->bloodTypes)],
                'phone' => '06' . rand(10000000, 99999999),
                'created_at' => Carbon::now()->subDays(rand(1, 365))
            ]);
        }
    }

    private function createPatients()
    {
        $count = 2253; // Match the number in your screenshot
        for ($i = 0; $i < $count; $i++) {
            User::create([
                'name' => $this->firstNames[array_rand($this->firstNames)] . ' ' . 
                         $this->lastNames[array_rand($this->lastNames)],
                'email' => 'patient' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'patient',
                'blood_type' => $this->bloodTypes[array_rand($this->bloodTypes)],
                'phone' => '06' . rand(10000000, 99999999),
                'created_at' => Carbon::now()->subDays(rand(1, 365))
            ]);
        }
    }

    private function createBloodStock()
    {
        foreach ($this->bloodTypes as $bloodType) {
            BloodStock::create([
                'blood_type' => $bloodType,
                'units_available' => rand(5, 50),
                'minimum_threshold' => 10,
                'center_id' => 1, // Assuming you have at least one center
                'last_updated' => Carbon::now()
            ]);
        }
    }

    private function createDonationAppointments()
    {
        // Create 187 donations this month (as shown in your screenshot)
        $donors = User::where('role', 'donor')->pluck('id');
        
        for ($i = 0; $i < 187; $i++) {
            DonationAppointment::create([
                'user_id' => $donors->random(),
                'center_id' => 1, // Assuming you have at least one center
                'appointment_date' => Carbon::now()->subDays(rand(0, 30)),
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(rand(0, 30))
            ]);
        }

        // Create some additional historical donations
        for ($i = 0; $i < 500; $i++) {
            DonationAppointment::create([
                'user_id' => $donors->random(),
                'center_id' => 1,
                'appointment_date' => Carbon::now()->subDays(rand(31, 180)),
                'status' => array_random(['completed', 'cancelled', 'missed']),
                'created_at' => Carbon::now()->subDays(rand(31, 180))
            ]);
        }
    }

    private function createBloodRequests()
    {
        $patients = User::where('role', 'patient')->pluck('id');
        $statuses = ['pending', 'approved', 'completed', 'rejected'];
        
        // Create recent blood requests (last 30 days)
        for ($i = 0; $i < 50; $i++) {
            BloodRequest::create([
                'user_id' => $patients->random(),
                'blood_type' => $this->bloodTypes[array_rand($this->bloodTypes)],
                'units_needed' => rand(1, 5),
                'status' => $statuses[array_rand($statuses)],
                'urgency' => array_random(['normal', 'urgent', 'emergency']),
                'created_at' => Carbon::now()->subDays(rand(0, 30))
            ]);
        }
    }
} 