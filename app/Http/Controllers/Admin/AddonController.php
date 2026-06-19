<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use ZipArchive;

class AddonController extends Controller
{
    private function checkActivePaymentGateways()
    {
        $gatewayKeys = [
            'payment_payu_enable',
            'payment_paystack_enable',
            'payment_flutterwave_enable',
            'payment_mollie_enable',
            'payment_paddle_enable',
        ];

        $activeGateways = SystemSetting::whereIn('key', $gatewayKeys)
                                       ->where('value', '1')
                                       ->pluck('key')
                                       ->toArray();

        if (empty($activeGateways)) {
            return false;
        }

        $formattedNames = array_map(function($key) {
            return ucfirst(str_replace(['payment_', '_enable'], '', $key));
        }, $activeGateways);

        return $formattedNames;
    }

    public function index()
    {
        $addons = Addon::latest()->get();

        foreach ($addons as $addon) {
            if ($addon->slug === 'payment-gateways' && $addon->is_active) {
                $addon->is_locked = $this->checkActivePaymentGateways() !== false;
            } else {
                $addon->is_locked = false;
            }
        }

        return view('admin.extra.addons.index', compact('addons'));
    }

    public function store(Request $request)
    {
        if (!class_exists('ZipArchive')) {
            return back()->withErrors('ZipArchive extension is not enabled.')->withInput();
        }

        $request->validate([
            'addon_zip' => 'required|file|mimes:zip|max:10240',
        ]);

        $zip = new ZipArchive();
        $zipPath = $request->file('addon_zip')->getRealPath();

        if ($zip->open($zipPath) !== true) {
            return back()->withErrors('Could not open zip file.')->withInput();
        }

        $tempFolder = 'temp_' . time();
        $tempPath = base_path("addons/{$tempFolder}");

        $zip->extractTo($tempPath);
        $zip->close();

        $manifestPath = $tempPath . '/manifest.json';

        if (!file_exists($manifestPath)) {
            File::deleteDirectory($tempPath);
            return back()->withErrors('manifest.json not found.')->withInput();
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);
        $slug = $manifest['slug'];
        $finalPath = base_path("addons/{$slug}");

        if (Addon::where('slug', $slug)->exists()) {
            File::deleteDirectory($tempPath);
            return back()->withErrors('Addon already installed.')->withInput();
        }

        File::moveDirectory($tempPath, $finalPath);

        try {
            if (file_exists($finalPath . '/install.php')) {
                require $finalPath . '/install.php';
            }

            Addon::create([
                'name' => $manifest['name'],
                'slug' => $slug,
                'version' => $manifest['version'] ?? '1.0.0',
                'description' => $manifest['description'] ?? null,
                'icon' => $manifest['icon'] ?? 'fa-solid fa-puzzle-piece',
                'is_active' => true,
            ]);

            Artisan::call('optimize:clear');
        } catch (\Throwable $e) {
            File::deleteDirectory($finalPath);
            return back()->withErrors($e->getMessage());
        }

        return back()->with('success', 'Addon installed.');
    }

    public function toggle(Request $request)
    {
        $addon = Addon::findOrFail($request->id);

        if ($addon->slug === 'payment-gateways' && $addon->is_active) {
            $activeGateways = $this->checkActivePaymentGateways();
            if ($activeGateways) {
                $gatewayList = implode(', ', $activeGateways);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot deactivate. Please disable these gateways in Payment Settings first: ' . $gatewayList
                ], 422);
            }
        }
        
        $addon->update(['is_active' => !$addon->is_active]);

        Artisan::call('optimize:clear');

        return response()->json([
            'status' => 'success',
            'is_active' => $addon->is_active
        ]);
    }

    public function destroy($id)
    {
        $addon = Addon::findOrFail($id);

        if ($addon->slug === 'payment-gateways') {
            $activeGateways = $this->checkActivePaymentGateways();
            if ($activeGateways) {
                $gatewayList = implode(', ', $activeGateways);
                return back()->withErrors('Cannot uninstall. Please disable these gateways in Payment Settings first: ' . $gatewayList);
            }
        }
        
        if ($addon->is_active) {
            return back()->withErrors('Disable addon before uninstalling.');
        }

        $path = base_path("addons/{$addon->slug}");

        if (file_exists($path . '/uninstall.php')) {
            require $path . '/uninstall.php';
        }

        File::deleteDirectory($path);
        $addon->delete();
        Artisan::call('optimize:clear');

        return back()->with('success', 'Addon uninstalled.');
    }
}