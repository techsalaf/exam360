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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_attempt_id')->constrained('exam_attempts')->cascadeOnDelete();
            
            $table->decimal('total_questions', 5, 0);
            $table->decimal('correct_answers', 5, 0);
            $table->decimal('total_score', 8, 2);
            $table->decimal('obtained_score', 8, 2);
            $table->decimal('percentage', 5, 2);
            
            $table->boolean('is_passed');
            $table->string('grade')->nullable(); // A, B, C, Pass, Fail
            
            // Added for Certificate Issuance feature
            $table->timestamp('certificate_issued_at')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};