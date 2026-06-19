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
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'plan_id')) {
                $table->unsignedBigInteger('plan_id')->nullable()->after('user_id');
                $table->foreign('plan_id')->references('id')->on('plans')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('payments', 'type')) {
                $table->string('type')->default('exam_access')->after('plan_id'); 
            }
            
            if (!Schema::hasColumn('payments', 'start_date')) {
                 $table->timestamp('start_date')->nullable();
            }
            
            if (!Schema::hasColumn('payments', 'end_date')) {
                $table->timestamp('end_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'plan_id')) {
                $table->dropForeign(['plan_id']);
                $table->dropColumn('plan_id');
            }
            if (Schema::hasColumn('payments', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('payments', 'start_date')) {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('payments', 'end_date')) {
                $table->dropColumn('end_date');
            }
        });
    }
};