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
        Schema::create('exam_user', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys with Cascading Delete
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            
            // Purchase & Status Information
            $table->string('status')->default('active'); // active, expired, refunded
            $table->decimal('price', 10, 2)->default(0.00); // Price paid at purchase
            
            // Payment Details
            $table->string('payment_method')->nullable(); // e.g., 'stripe', 'paypal', 'manual'
            $table->string('transaction_id')->nullable();
            
            // Access Control
            $table->timestamp('expires_at')->nullable(); // If the exam access is time-limited
            
            $table->timestamps();

            // Prevent duplicate active enrollments for the same exam
            $table->unique(['user_id', 'exam_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_user');
    }
};