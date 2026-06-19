<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class PublicStorageMirror
{
    public static function sync(string $relativePath): void
    {
        $source = storage_path('app/public/' . $relativePath);
        $destination = public_path('storage/' . $relativePath);

        if (!File::exists($source)) {
            return;
        }

        $directory = dirname($destination);
        
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        File::copy($source, $destination);
    }

    public static function delete(string $relativePath): void
    {
        $destination = public_path('storage/' . $relativePath);

        if (File::exists($destination)) {
            File::delete($destination);
        }
    }

    public static function syncAll(): void
    {
        $source = storage_path('app/public');
        $destination = public_path('storage');

        if (!File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true, true);
        }

        if (File::exists($source)) {
            File::copyDirectory($source, $destination);
        }
    }
}