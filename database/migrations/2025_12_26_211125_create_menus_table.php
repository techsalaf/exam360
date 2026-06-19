<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('menus')) {
            Schema::create('menus', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('location')->unique(); // e.g., 'header', 'footer'
                $table->json('items')->nullable(); // Stores menu structure
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};