<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            
            // Exam State
            $table->string('status')->default('ongoing'); // ongoing, completed, expired, paused, terminated
            $table->integer('progress_percentage')->default(0);
            
            // Counters for Progress Bar & Scoring
            $table->integer('total_questions')->default(0);
            $table->integer('completed_questions')->default(0);
            $table->integer('correct_answers')->nullable();
            $table->decimal('score', 10, 2)->nullable();
            
            // Timers
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            
            // Certificate
            $table->timestamp('certificate_issued_at')->nullable();
            
            // Analytics / Security (Live Monitoring)
            $table->string('risk_level')->default('low');
            $table->integer('risk_score')->default(0);
            $table->json('flagged_events')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_exam_sessions');
    }
};