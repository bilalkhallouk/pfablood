<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('blood_type');
            $table->integer('units_needed');
            $table->enum('urgency', ['low', 'medium', 'high'])->default('low');
            $table->string('prescription_file')->nullable();
            $table->enum('status', ['pending', 'approved', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blood_requests');
    }
}; 