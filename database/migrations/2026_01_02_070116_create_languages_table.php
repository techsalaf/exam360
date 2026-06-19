<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // e.g., 'en', 'es', 'fr'
            $table->string('flag')->nullable(); // e.g., 'us', 'es' for icons
            
            $table->boolean('is_rtl')->default(false);
            
            // Separate visibility controls
            $table->boolean('is_active_front')->default(true);
            $table->boolean('is_active_admin')->default(true);
            
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Insert Default Language (English) to prevent system errors
        DB::table('languages')->insert([
            'name' => 'English',
            'code' => 'en',
            'flag' => 'us',
            'is_rtl' => false,
            'is_active_front' => true,
            'is_active_admin' => true,
            'is_default' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};