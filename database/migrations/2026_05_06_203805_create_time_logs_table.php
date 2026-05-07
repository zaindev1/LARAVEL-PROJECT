<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('time_logs', function (Blueprint $table) {
            $table->id();
            // User ki ID store karne ke liye
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Login aur Logout ka exact time
            $table->timestamp('login_at')->nullable();
            $table->timestamp('logout_at')->nullable();
            
            // Poore din mein kitne seconds guzray
            $table->integer('total_seconds')->default(0);
            
            // Kaunse din ka record hai
            $table->date('log_date'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_logs');
    }
};