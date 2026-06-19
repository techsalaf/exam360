<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('login_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('login_histories', 'created_at')) {
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('login_histories', function (Blueprint $table) {
            // Remove columns on rollback
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
};
