<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Core Details
            $table->string('ip_address')->nullable();
            $table->string('login_method', 50)->default('password'); // Password, Google, OTP
            $table->string('status', 20)->default('success')->index(); // success, failed, suspicious
            
            // Geo & Device
            $table->string('city', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('device_type', 20)->nullable(); // Desktop, Mobile, Tablet
            $table->string('browser', 50)->nullable();
            $table->string('os', 50)->nullable();
            
            // Enterprise Fields
            $table->string('session_id')->nullable()->unique();
            $table->string('network_type', 50)->nullable(); // Residential, VPN, Mobile Network
            $table->boolean('mfa_used')->default(false); // MFA Status
            $table->timestamp('logout_at')->nullable(); // For Session Duration calculation
            
            // Timestamp and User Agent
            $table->timestamp('login_at')->useCurrent();
            $table->text('user_agent')->nullable();

            // We must add timestamps to allow Laravel's ORM to track creation/updates
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_histories');
    }
};