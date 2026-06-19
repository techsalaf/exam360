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
    Schema::create('ai_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable(); // Nullable (System tasks)
        $table->string('action'); // e.g., "generate_questions"
        $table->integer('prompt_tokens')->default(0);
        $table->integer('completion_tokens')->default(0);
        $table->string('model')->default('gpt-3.5-turbo');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_logs');
    }
};
