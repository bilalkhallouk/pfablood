<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.admin',
            'password' => Hash::make('admin'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
} 