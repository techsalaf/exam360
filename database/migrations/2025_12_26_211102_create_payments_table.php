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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // Core Relationships
            $table->foreignId('user_id')->constrained();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete(); 
            
            // Transaction Details
            $table->string('transaction_id')->unique();
            $table->string('type', 50)->default('general')->index();
            // Financials
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            
            // Gateway & Status
            $table->string('gateway'); 
            $table->string('status');
            $table->json('gateway_response')->nullable(); 
            
            // Subscription Validity Dates (New)
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};