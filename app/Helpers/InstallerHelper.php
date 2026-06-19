<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\PageSeeder;
use Database\Seeders\CMSSeeder;
use Database\Seeders\HomepageSeeder;

class InstallerHelper
{
    public function getAppUrl()
    {
        $url = config('app.url');
        
        if (isset($_SERVER['HTTP_HOST'])) {
            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
            return $protocol . "://" . $_SERVER['HTTP_HOST'];
        }
        
        return $url;
    }

    public function checkRequirements()
    {
        $minPhp = '8.1.0';
        $extensions = [
            'bcmath', 'ctype', 'json', 'mbstring', 'openssl', 'pdo', 
            'tokenizer', 'xml', 'curl', 'fileinfo', 'gd'
        ];

        $results = [];
        $currentPhp = phpversion();

        $results[] = [
            'name' => 'PHP Version',
            'required' => '>= ' . $minPhp,
            'current' => $currentPhp,
            'required_status' => version_compare($currentPhp, $minPhp, '>='),
        ];

        foreach ($extensions as $ext) {
            $enabled = extension_loaded($ext);
            $results[] = [
                'name' => ucfirst($ext),
                'required' => 'Enabled',
                'current' => $enabled ? 'Enabled' : 'Disabled',
                'required_status' => $enabled,
            ];
        }

        return $results;
    }

    public function checkPermissions()
    {
        $folders = [
            'storage/app' => '775',
            'storage/app/public' => '775',
            'storage/framework' => '775',
            'storage/logs' => '775',
            'bootstrap/cache' => '775',
            'public/storage' => '775', 
        ];

        $results = [];

        foreach ($folders as $folder => $perm) {
            $path = base_path($folder);
            
            if (!File::exists($path) && $folder !== 'public/storage') {
                try {
                    File::makeDirectory($path, 0775, true);
                } catch (\Exception $e) {}
            }

            $isWritable = File::exists($path) ? File::isWritable($path) : is_writable(dirname($path));

            $results[] = [
                'folder' => $folder,
                'permission' => $perm,
                'currentPermission' => File::exists($path) ? substr(sprintf('%o', fileperms($path)), -4) : '---',
                'isWritable' => $isWritable,
            ];
        }

        return $results;
    }

    public function testDatabaseConnection($data)
    {
        try {
            $dsn = "mysql:host={$data['db_host']};port={$data['db_port']};dbname={$data['db_name']}";
            $options = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION];
            new \PDO($dsn, $data['db_username'], $data['db_password'], $options);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function writeEnvFile($dbConfig, $appConfig)
    {
        $envPath = base_path('.env');
        
        if (!File::exists($envPath)) {
            if (File::exists(base_path('.env.example'))) {
                File::copy(base_path('.env.example'), $envPath);
            } else {
                touch($envPath);
            }
        }

        $content = File::get($envPath);
        $host = parse_url($appConfig['app_url'], PHP_URL_HOST) ?? 'localhost';
        $cleanUrl = rtrim($appConfig['app_url'], '/');

        $vars = [
            'APP_NAME' => '"' . $appConfig['app_name'] . '"',
            'APP_URL' => $cleanUrl,
            'APP_DEBUG' => 'false',
            'APP_ENV' => 'production',
            'FILESYSTEM_DISK' => 'public',
            'DB_HOST' => $dbConfig['db_host'],
            'DB_PORT' => $dbConfig['db_port'],
            'DB_DATABASE' => $dbConfig['db_name'],
            'DB_USERNAME' => $dbConfig['db_username'],
            'DB_PASSWORD' => $dbConfig['db_password'],
            'TIME_ZONE' => $appConfig['timezone'] ?? 'UTC',
            'MAIL_MAILER' => 'smtp',
            'MAIL_HOST' => 'mail.' . $host,
            'MAIL_PORT' => '465',
            'MAIL_USERNAME' => 'noreply@' . $host,
            'MAIL_PASSWORD' => 'null',
            'MAIL_ENCRYPTION' => 'tls',
            'MAIL_FROM_ADDRESS' => 'noreply@' . $host,
            'MAIL_FROM_NAME' => '"' . $appConfig['app_name'] . '"',
        ];

        foreach ($vars as $key => $val) {
            if (preg_match("/^{$key}=/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$val}", $content);
            } else {
                $content .= "\n{$key}={$val}";
            }
        }

        File::put($envPath, $content);
    }

    public function runMigrationsAndSeeding(array $config)
    {
        Artisan::call('migrate:fresh', ['--force' => true]);

        $seeders = [
            RolesAndPermissionsSeeder::class,
            PageSeeder::class,
            CMSSeeder::class,
        ];

        foreach ($seeders as $class) {
            if (class_exists($class)) {
                Artisan::call('db:seed', ['--class' => $class, '--force' => true]);
            }
        }

        if (class_exists(HomepageSeeder::class)) {
            (new HomepageSeeder())->run($config);
        }

        if (!empty($config['seed_demo_data'])) {
            Artisan::call('db:seed', ['--force' => true]);
        }

        $this->createAdminAccount($config);
        
        Artisan::call('key:generate', ['--force' => true]);
        Artisan::call('config:clear');
        Artisan::call('view:clear');
    }

    private function createAdminAccount($data)
    {
        $user = User::updateOrCreate(
            ['email' => $data['admin_email']],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => bcrypt($data['admin_password']),
                'email_verified_at' => now(),
                'status' => 'active',
                'is_banned' => false,
            ]
        );

        if (method_exists($user, 'assignRole')) {
            if (!Role::where('name', 'Super Admin')->exists()) {
                Role::create(['name' => 'Super Admin']);
            }
            $user->assignRole('Super Admin');
        }
    }

    /**
     * Mirrors storage/app/public to public/storage.
     * Removes reliance on symlinks entirely.
     */
    public function setupPublicStorage()
    {
        $source = storage_path('app/public');
        $destination = public_path('storage');
        
        // 1. Remove existing symlink or file if exists
        if (File::exists($destination) || is_link($destination)) {
            if (is_link($destination)) {
                @unlink($destination);
            } else {
                File::deleteDirectory($destination);
            }
        }

        // 2. Create physical directory
        if (!File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true, true);
        }

        // 3. Copy contents recursively
        if (File::exists($source)) {
            File::copyDirectory($source, $destination);
        }

        return 'success';
    }

    public function createLockFile()
    {
        file_put_contents(storage_path('app/installed.lock'), 'Installed on ' . date('Y-m-d H:i:s'));
    }
}