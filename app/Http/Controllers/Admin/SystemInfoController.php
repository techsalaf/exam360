<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use App\Models\SystemSetting;

class SystemInfoController extends Controller
{

    public function application()
    {

        $dbSize = 'Unknown';
        try {
            $dbName = DB::connection()->getDatabaseName();
            $result = DB::select("SELECT round(sum(data_length + index_length) / 1024 / 1024, 2) as size FROM information_schema.TABLES WHERE table_schema = ?", [$dbName]);
            $dbSize = ($result[0]->size ?? 0) . ' MB';
        } catch (\Exception $e) {
            $dbSize = 'N/A';
        }

        $permissions = [
            'storage/framework' => File::isWritable(storage_path('framework')),
            'storage/logs'      => File::isWritable(storage_path('logs')),
            'bootstrap/cache'   => File::isWritable(base_path('bootstrap/cache')),
            '.env'              => File::isWritable(base_path('.env')),
        ];

        $dbSettings = SystemSetting::pluck('value', 'key')->toArray();

        $getSetting = fn($key, $configKey) => $dbSettings[$key] ?? config($configKey);

        $appInfo = [
            'name'     => $getSetting('app_name', 'app.name'),

            'env'      => $getSetting('app_env', 'app.env'),

            'debug'    => isset($dbSettings['app_debug']) 
                          ? filter_var($dbSettings['app_debug'], FILTER_VALIDATE_BOOLEAN) 
                          : config('app.debug'),
            
            'url'      => config('app.url'),

            'timezone' => $getSetting('default_timezone', 'app.timezone'),
            'locale'   => $getSetting('default_locale', 'app.locale'),

            'laravel'  => app()->version(),
        
            'db_conn'  => config('database.default'),
            'db_size'  => $dbSize,
        ];

        return view('admin.extra.application', compact('appInfo', 'permissions'));
    }

    public function server()
    {
        $serverInfo = [
            'software'      => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'ip'            => $_SERVER['SERVER_ADDR'] ?? '127.0.0.1',
            'protocol'      => $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1',
            'php'           => phpversion(),
            'memory_limit'  => ini_get('memory_limit'),
            'upload_max'    => ini_get('upload_max_filesize'),
            'post_max'      => ini_get('post_max_size'),
            'max_execution' => ini_get('max_execution_time') . 's',
        ];

        $extensions = get_loaded_extensions();
        sort($extensions);

        return view('admin.extra.server', compact('serverInfo', 'extensions'));
    }

    public function cache()
    {
        $drivers = [
            'cache'   => config('cache.default'),
            'session' => config('session.driver'),
            'queue'   => config('queue.default'),
            'mail'    => config('mail.default'),
        ];

        return view('admin.extra.cache', compact('drivers'));
    }

    public function update()
    {
        $version = '1.2.0'; 
        $lastChecked = now(); 
        
        return view('admin.extra.update', compact('version', 'lastChecked'));
    }

    public function clearCache(Request $request)
    {
        $type = $request->input('type');

        try {
            switch ($type) {
                case 'app':
                    Artisan::call('cache:clear');
                    break;
                case 'route':
                    Artisan::call('route:clear');
                    break;
                case 'config':
                    Artisan::call('config:clear');
                    break;
                case 'view':
                    Artisan::call('view:clear');
                    break;
                case 'optimize':
                    Artisan::call('optimize:clear');
                    break;
                default:
                    return back()->with('error', 'Invalid cache type selected.');
            }
            
            return back()->with('success', ucfirst($type) . ' cache cleared successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }
}