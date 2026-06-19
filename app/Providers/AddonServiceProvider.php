<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use App\Models\Addon;

class AddonServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        try {
            if (!Schema::hasTable('addons')) {
                return;
            }

            $addons = Addon::where('is_active', true)->get();

            View::composer('*', function ($view) use ($addons) {
                $view->with('examAddons', $addons);
            });

            foreach ($addons as $addon) {
                // 1. Detect Addon Path
                $addonPath = null;
                $possiblePaths = [
                    base_path("addons/{$addon->slug}"),
                    base_path("addons/" . Str::studly($addon->slug)),
                    base_path("addons/" . Str::camel($addon->slug))
                ];

                foreach ($possiblePaths as $path) {
                    if (is_dir($path)) {
                        $addonPath = $path;
                        break;
                    }
                }

                if (!$addonPath) continue;

                // 2. Run Migrations (Specific Fixes)
                $runMigration = false;
                if ($addon->slug === 'ielts-module' && !Schema::hasTable('ielts_tests')) {
                    $runMigration = true;
                }
                if ($addon->slug === 'rich-media-questions' && !Schema::hasColumn('questions', 'media_type')) {
                    $runMigration = true;
                }

                if ($runMigration) {
                    $migrationPath = "addons/" . basename($addonPath) . "/database/migrations";
                    if (is_dir(base_path($migrationPath))) {
                        Artisan::call('migrate', ['--path' => $migrationPath, '--force' => true]);
                        Artisan::call('optimize:clear');
                    }
                }

                // 3. Load Manifest
                $manifestFile = "{$addonPath}/manifest.json";
                if (!file_exists($manifestFile)) continue;

                $manifest = json_decode(file_get_contents($manifestFile), true);
                $providers = [];

                if (isset($manifest['providers']) && is_array($manifest['providers'])) {
                    $providers = $manifest['providers'];
                } elseif (isset($manifest['provider']) && is_string($manifest['provider'])) {
                    $providers = [$manifest['provider']];
                }

                // 4. Smart Autoloader (Handles Mixed src/root structures)
                foreach ($providers as $providerClass) {
                    if (!class_exists($providerClass)) {
                        
                        // Determine Root Namespace
                        $parts = explode('\\', $providerClass);
                        $namespacePrefix = "";
                        
                        // If namespace is 'Addons\RichMedia\...' prefix is first 2 parts
                        if ($parts[0] === 'Addons' && count($parts) > 1) {
                            $namespacePrefix = $parts[0] . '\\' . $parts[1];
                        } else {
                            // If namespace is 'IeltsModule\...' prefix is first 1 part
                            $namespacePrefix = $parts[0];
                        }

                        spl_autoload_register(function ($class) use ($namespacePrefix, $addonPath) {
                            if (str_starts_with($class, $namespacePrefix)) {
                                $relative = substr($class, strlen($namespacePrefix));
                                $filePath = str_replace('\\', '/', $relative) . '.php';

                                // Priority 1: Check src folder (Standard for Controllers/Models)
                                if (file_exists($addonPath . '/src' . $filePath)) {
                                    require_once $addonPath . '/src' . $filePath;
                                    return true;
                                }

                                // Priority 2: Check root folder (Standard for Providers in your structure)
                                if (file_exists($addonPath . $filePath)) {
                                    require_once $addonPath . $filePath;
                                    return true;
                                }
                            }
                            return false;
                        });
                    }

                    if (class_exists($providerClass)) {
                        $this->app->register($providerClass);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('AddonServiceProvider Error: ' . $e->getMessage());
        }
    }
}