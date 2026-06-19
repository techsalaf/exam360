<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('addons')) {
            Schema::create('addons', function (Blueprint $table) {
                $table->id();

                $table->string('name');
                $table->string('slug')->unique();
                $table->string('version')->nullable();
                $table->text('description')->nullable();

                $table->string('route_name', 191)->nullable();
                $table->string('icon', 191)
                      ->nullable()
                      ->default('fa-solid fa-puzzle-piece');
                $table->string('menu_location', 191)
                      ->nullable()
                      ->default('extra');

                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);

                $table->timestamps();
            });
        } else {
            Schema::table('addons', function (Blueprint $table) {
                if (!Schema::hasColumn('addons', 'route_name')) {
                    $table->string('route_name', 191)->nullable()->after('version');
                }

                if (!Schema::hasColumn('addons', 'icon')) {
                    $table->string('icon', 191)
                          ->nullable()
                          ->default('fa-solid fa-puzzle-piece')
                          ->after('route_name');
                }

                if (!Schema::hasColumn('addons', 'menu_location')) {
                    $table->string('menu_location', 191)
                          ->nullable()
                          ->default('extra')
                          ->after('icon');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('addons');
    }
};