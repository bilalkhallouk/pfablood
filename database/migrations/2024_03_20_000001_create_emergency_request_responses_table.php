<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('emergency_request_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('emergency_request_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'accept', 'decline'])->default('pending');
            $table->timestamp('response_time')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'emergency_request_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('emergency_request_responses');
    }
}; 