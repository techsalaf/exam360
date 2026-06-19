<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable(); 
            $table->string('banner')->nullable();

            // Relationships
            // Ensure you have categories and plans tables, or remove these constraints if testing in isolation
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();

            // Exam Details
            $table->integer('duration_minutes');
            $table->decimal('pass_percentage', 5, 2)->default(50);
            $table->integer('total_marks')->nullable();

            // Schedule
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamp('result_date')->nullable();

            // Status & Pricing
            $table->boolean('is_active')->default(true);
            $table->boolean('is_paid')->default(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('allow_retake')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};