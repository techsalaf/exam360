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
    Schema::create('exam_answers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('exam_attempt_id')->constrained('exam_attempts')->cascadeOnDelete();
        $table->foreignId('question_id')->constrained()->cascadeOnDelete();
        $table->text('user_answer')->nullable(); // Could be 'A', 'True', or text
        $table->boolean('is_correct')->default(false);
        $table->decimal('marks_awarded', 8, 2)->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_answers');
    }
};
