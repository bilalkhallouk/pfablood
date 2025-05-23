<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            AdminSeeder::class,
            CenterSeeder::class,
            DashboardDemoSeeder::class
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.admin',
            'password' => Hash::make('admin@admin.admin'),
            'role' => 'admin',
        ]);
    }
}
