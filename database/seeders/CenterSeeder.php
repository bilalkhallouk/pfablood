<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Center;

class CenterSeeder extends Seeder
{
    public function run()
    {
        $centers = [
            [
                'name' => 'Centre Régional de Transfusion Sanguine de Casablanca',
                'address' => '12 Rue Al Fourat, Casablanca',
                'city' => 'Casablanca',
                'phone' => '0522234567',
                'email' => 'crts.casa@bloodpfa.com',
                'latitude' => 33.5731,
                'longitude' => -7.5898,
                'is_active' => true,
                'operating_hours' => json_encode([
                    'Monday' => ['open' => '08:00', 'close' => '18:00'],
                    'Tuesday' => ['open' => '08:00', 'close' => '18:00'],
                    'Wednesday' => ['open' => '08:00', 'close' => '18:00'],
                    'Thursday' => ['open' => '08:00', 'close' => '18:00'],
                    'Friday' => ['open' => '08:00', 'close' => '18:00'],
                    'Saturday' => ['open' => '09:00', 'close' => '16:00'],
                    'Sunday' => ['open' => '09:00', 'close' => '14:00']
                ]),
                'available_blood_types' => json_encode(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
                'description' => 'Principal centre de transfusion sanguine à Casablanca',
                'website' => 'https://crts-casa.ma',
                'emergency_contact' => '0522999999'
            ],
            [
                'name' => 'Centre National de Transfusion Sanguine Rabat',
                'address' => 'Avenue Hassan II, Rabat',
                'city' => 'Rabat',
                'phone' => '0537567890',
                'email' => 'cnts.rabat@bloodpfa.com',
                'latitude' => 34.0209,
                'longitude' => -6.8416,
                'is_active' => true,
                'operating_hours' => json_encode([
                    'Monday' => ['open' => '08:00', 'close' => '18:00'],
                    'Tuesday' => ['open' => '08:00', 'close' => '18:00'],
                    'Wednesday' => ['open' => '08:00', 'close' => '18:00'],
                    'Thursday' => ['open' => '08:00', 'close' => '18:00'],
                    'Friday' => ['open' => '08:00', 'close' => '18:00'],
                    'Saturday' => ['open' => '09:00', 'close' => '16:00'],
                    'Sunday' => ['open' => '09:00', 'close' => '14:00']
                ]),
                'available_blood_types' => json_encode(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
                'description' => 'Centre national de référence pour la transfusion sanguine',
                'website' => 'https://cnts-rabat.ma',
                'emergency_contact' => '0537999999'
            ],
            [
                'name' => 'Centre de Transfusion Sanguine Marrakech',
                'address' => 'Boulevard Mohammed VI, Marrakech',
                'city' => 'Marrakech',
                'phone' => '0524123456',
                'email' => 'cts.marrakech@bloodpfa.com',
                'latitude' => 31.6295,
                'longitude' => -7.9811,
                'is_active' => true,
                'operating_hours' => json_encode([
                    'Monday' => ['open' => '08:00', 'close' => '18:00'],
                    'Tuesday' => ['open' => '08:00', 'close' => '18:00'],
                    'Wednesday' => ['open' => '08:00', 'close' => '18:00'],
                    'Thursday' => ['open' => '08:00', 'close' => '18:00'],
                    'Friday' => ['open' => '08:00', 'close' => '18:00'],
                    'Saturday' => ['open' => '09:00', 'close' => '16:00'],
                    'Sunday' => ['open' => '09:00', 'close' => '14:00']
                ]),
                'available_blood_types' => json_encode(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
                'description' => 'Centre moderne de transfusion sanguine à Marrakech',
                'website' => 'https://cts-marrakech.ma',
                'emergency_contact' => '0524999999'
            ],
        ];

        foreach ($centers as $center) {
            Center::create($center);
        }
    }
} 