<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_exam_answers', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('student_exam_session_id')->constrained('student_exam_sessions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            
            // The Answer Data
            $table->string('selected_option_id')->nullable(); 
            
            // Grading & UI Flags
            $table->boolean('is_correct')->default(false);
            $table->decimal('marks_awarded', 8, 2)->default(0);
            $table->boolean('is_marked_for_review')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_exam_answers');
    }
};