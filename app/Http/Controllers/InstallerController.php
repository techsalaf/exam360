<?php

namespace App\Http\Controllers;

use App\Helpers\InstallerHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class InstallerController extends Controller
{
    protected $helper;

    public function __construct(InstallerHelper $helper)
    {
        $this->helper = $helper;

        if (file_exists(storage_path('app/installed.lock'))) {
            return redirect('/login')->send();
        }
    }

    public function welcome()
    {
        return view('installer.welcome');
    }

    public function requirements()
    {
        $requirements = $this->helper->checkRequirements();
        $isPassed = !in_array(false, array_column($requirements, 'required_status'));

        return view('installer.requirements', compact('requirements', 'isPassed'));
    }

    public function permissions()
    {
        $permissions = $this->helper->checkPermissions();
        $isPassed = !in_array(false, array_column($permissions, 'isWritable'));

        return view('installer.permissions', compact('permissions', 'isPassed'));
    }

    public function database(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'db_host'     => 'required|string',
                'db_name'     => 'required|string',
                'db_username' => 'required|string',
                'db_password' => 'nullable|string',
                'db_port'     => 'nullable|integer',
            ]);

            if (!$this->helper->testDatabaseConnection($data)) {
                return back()->withInput()->withErrors(['db_name' => 'Connection failed. Please check your credentials.']);
            }

            $request->session()->put('db_config', $data);
            return redirect()->route('install.application');
        }

        return view('installer.database');
    }

    public function application(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'app_name'       => 'required|string|max:50',
                'app_url'        => 'required|url',
                'admin_email'    => 'required|email',
                'admin_password' => 'required|min:6',
                'timezone'       => 'required|string',
                'currency'       => 'required|string',
                'seed_demo_data' => 'nullable',
                'db_db_host'     => 'nullable|string',
            ]);

            if ($request->session()->has('db_config')) {
                $dbConfig = $request->session()->get('db_config');
            } elseif ($request->filled('db_db_host')) {
                $dbConfig = [
                    'db_host'     => $request->input('db_db_host'),
                    'db_name'     => $request->input('db_db_name'),
                    'db_username' => $request->input('db_db_username'),
                    'db_password' => $request->input('db_db_password'),
                    'db_port'     => $request->input('db_db_port', 3306),
                ];
            } else {
                return redirect()->route('install.database')->withErrors(['session' => 'Session expired, please re-enter database details.']);
            }

            try {
                $this->helper->writeEnvFile($dbConfig, $data);
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['msg' => 'Unable to write .env file. Please check file permissions.']);
            }

            try {
                Artisan::call('config:clear');
                Artisan::call('cache:clear');
            } catch (\Throwable $e) {}

            $data['seed_demo_data'] = $request->has('seed_demo_data');
            $appConfig = $data;

            set_time_limit(300);
            
            try {
                config(['database.default' => 'mysql']);
                config([
                    'database.connections.mysql.host'     => $dbConfig['db_host'],
                    'database.connections.mysql.database' => $dbConfig['db_name'],
                    'database.connections.mysql.username' => $dbConfig['db_username'],
                    'database.connections.mysql.password' => $dbConfig['db_password'],
                    'database.connections.mysql.port'     => $dbConfig['db_port'] ?? 3306,
                    'app.timezone' => $appConfig['timezone'],
                    'app.url'      => $appConfig['app_url'],
                ]);

                DB::purge('mysql');
                DB::reconnect('mysql');

                $this->helper->runMigrationsAndSeeding($appConfig);
                
                // MIRRORING EXECUTION
                $this->helper->setupPublicStorage();
                
                $this->helper->createLockFile();
                
                $request->session()->forget(['db_config', 'app_config']);

            } catch (\Throwable $e) {
                return view('installer.finish', ['error' => $e->getMessage()]);
            }

            return view('installer.finish', ['appConfig' => $appConfig, 'symlinkSuccess' => 'success']);
        }

        $defaultAppUrl = $this->helper->getAppUrl();
        return view('installer.application', compact('defaultAppUrl'));
    }

    public function finish()
    {
        return redirect()->route('login');
    }
}