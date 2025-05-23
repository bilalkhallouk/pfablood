<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blood_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('blood_type');
            $table->integer('units')->default(0);
            $table->integer('min_threshold')->default(10);
            $table->integer('max_threshold')->default(100);
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamps();

            $table->unique('blood_type');
        });

        // Insert initial blood types
        DB::table('blood_stocks')->insert([
            ['blood_type' => 'A+', 'units' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['blood_type' => 'A-', 'units' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['blood_type' => 'B+', 'units' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['blood_type' => 'B-', 'units' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['blood_type' => 'AB+', 'units' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['blood_type' => 'AB-', 'units' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['blood_type' => 'O+', 'units' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['blood_type' => 'O-', 'units' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('blood_stocks');
    }
}; 