<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;
use Exception;

class UpdateController extends Controller
{
    protected $versionFile = 'version.json';

    public function index()
    {
        $currentVersion = $this->getCurrentVersion();

        return view('admin.extra.update', [
            'version' => $currentVersion,
            'lastChecked' => now(),
        ]);
    }

    public function upload(Request $request)
    {
        // 1. Basic Validation
        $request->validate([
            'update_file' => 'required|file|mimes:zip|max:204800', // Increased to 200MB
        ]);

        // 2. Prepare Paths
        $tempDir = storage_path('app/temp_update');
        if (File::exists($tempDir)) {
            File::deleteDirectory($tempDir);
        }
        File::makeDirectory($tempDir, 0777, true);

        $zipFile = $request->file('update_file');
        $zipName = 'update_' . time() . '.zip';
        $fullZipPath = $tempDir . '/' . $zipName;

        // 3. Move file directly (Avoids 'disk' configuration issues)
        $zipFile->move($tempDir, $zipName);

        $zip = new ZipArchive();

        try {
            // 4. Maintenance Mode
            Artisan::call('down', ['--refresh' => 15]);

            // 5. Open ZIP
            $resource = $zip->open($fullZipPath);
            if ($resource !== true) {
                // Common zip errors return integers, we want to know why
                throw new Exception('ZipArchive Error Code: ' . $resource . '. Could not open the package.');
            }

            // 6. Extract to temp folder first for validation
            $extractPath = $tempDir . '/extracted';
            File::makeDirectory($extractPath, 0777, true);
            $zip->extractTo($extractPath);
            $zip->close();

            // 7. Version Safety Check
            $this->validateVersion($extractPath);

            // 8. Copy Files to System (Overwrite)
            File::copyDirectory($extractPath, base_path());

            // 9. Run Migrations
            Artisan::call('migrate', ['--force' => true]);

            // 10. Deep Clean Cache
            $this->clearAllCache();

            // 11. Cleanup and Bring Site Up
            $this->finalCleanup($tempDir);
            $this->bringSiteUp();

            return back()->with('success', 'System updated to version ' . $this->getCurrentVersion());

        } catch (Exception $e) {
            // Emergency Recovery
            $this->bringSiteUp();
            $this->finalCleanup($tempDir);

            return back()->with('error', 'Update Failed: ' . $e->getMessage());
        }
    }

    private function getCurrentVersion()
    {
        $path = base_path($this->versionFile);
        if (File::exists($path)) {
            $config = json_decode(File::get($path), true);
            return $config['version'] ?? '1.0.0';
        }
        return '1.0.0';
    }

    private function validateVersion($extractPath)
    {
        $newVersionFile = $extractPath . '/' . $this->versionFile;
        if (File::exists($newVersionFile)) {
            $newConfig = json_decode(File::get($newVersionFile), true);
            $newVersion = $newConfig['version'] ?? '0.0.0';
            
            if (version_compare($newVersion, $this->getCurrentVersion(), '<')) {
                throw new Exception('The uploaded package is an older version (' . $newVersion . '). You cannot downgrade.');
            }
        }
    }

    private function clearAllCache()
    {
        try {
            Artisan::call('optimize:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
        } catch (Exception $e) {
            // Fail silently
        }
    }

    private function bringSiteUp()
    {
        Artisan::call('up');
        $downFile = storage_path('framework/down');
        if (File::exists($downFile)) {
            File::delete($downFile);
        }
    }

    private function finalCleanup($tempDir)
    {
        if (File::exists($tempDir)) {
            File::deleteDirectory($tempDir);
        }
    }
}