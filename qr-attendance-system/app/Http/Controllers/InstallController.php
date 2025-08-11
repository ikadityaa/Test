<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class InstallController extends Controller
{
    public function index()
    {
        // Check if already installed
        if ($this->isInstalled()) {
            return redirect('/');
        }

        return view('install.index');
    }

    public function checkRequirements()
    {
        $requirements = [
            'PHP Version (>= 8.2)' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'BCMath Extension' => extension_loaded('bcmath'),
            'Ctype Extension' => extension_loaded('ctype'),
            'JSON Extension' => extension_loaded('json'),
            'Mbstring Extension' => extension_loaded('mbstring'),
            'OpenSSL Extension' => extension_loaded('openssl'),
            'PDO Extension' => extension_loaded('pdo'),
            'Tokenizer Extension' => extension_loaded('tokenizer'),
            'XML Extension' => extension_loaded('xml'),
            'GD Extension' => extension_loaded('gd'),
        ];

        $allMet = collect($requirements)->every(fn($met) => $met);

        return response()->json([
            'requirements' => $requirements,
            'all_met' => $allMet
        ]);
    }

    public function checkPermissions()
    {
        $permissions = [
            'storage/app' => is_writable(storage_path('app')),
            'storage/framework' => is_writable(storage_path('framework')),
            'storage/logs' => is_writable(storage_path('logs')),
            'bootstrap/cache' => is_writable(base_path('bootstrap/cache')),
            '.env' => is_writable(base_path('.env')),
        ];

        $allWritable = collect($permissions)->every(fn($writable) => $writable);

        return response()->json([
            'permissions' => $permissions,
            'all_writable' => $allWritable
        ]);
    }

    public function configure(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'db_connection' => 'required|in:mysql,sqlite',
            'db_host' => 'required_if:db_connection,mysql',
            'db_port' => 'required_if:db_connection,mysql',
            'db_database' => 'required_if:db_connection,mysql',
            'db_username' => 'required_if:db_connection,mysql',
            'db_password' => 'nullable',
        ]);

        try {
            // Update .env file
            $this->updateEnvFile($request->all());

            // Test database connection
            if (!$this->testDatabaseConnection($request->all())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Database connection failed. Please check your settings.'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Configuration saved successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Configuration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function install()
    {
        try {
            // Run migrations
            Artisan::call('migrate:fresh', ['--force' => true]);

            // Run seeders
            Artisan::call('db:seed', ['--force' => true]);

            // Clear caches
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');

            // Create installed file
            File::put(storage_path('installed'), date('Y-m-d H:i:s'));

            return response()->json([
                'success' => true,
                'message' => 'Installation completed successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Installation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function isInstalled()
    {
        return File::exists(storage_path('installed'));
    }

    private function updateEnvFile($data)
    {
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        $updates = [
            'APP_NAME' => '"' . $data['app_name'] . '"',
            'APP_URL' => $data['app_url'],
            'DB_CONNECTION' => $data['db_connection'],
        ];

        if ($data['db_connection'] === 'mysql') {
            $updates['DB_HOST'] = $data['db_host'];
            $updates['DB_PORT'] = $data['db_port'];
            $updates['DB_DATABASE'] = $data['db_database'];
            $updates['DB_USERNAME'] = $data['db_username'];
            $updates['DB_PASSWORD'] = $data['db_password'] ?? '';
        }

        foreach ($updates as $key => $value) {
            $envContent = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $envContent
            );
        }

        File::put($envPath, $envContent);
    }

    private function testDatabaseConnection($data)
    {
        try {
            $config = [
                'driver' => $data['db_connection'],
                'host' => $data['db_host'] ?? null,
                'port' => $data['db_port'] ?? null,
                'database' => $data['db_database'] ?? null,
                'username' => $data['db_username'] ?? null,
                'password' => $data['db_password'] ?? null,
            ];

            if ($data['db_connection'] === 'sqlite') {
                $config['database'] = database_path('database.sqlite');
            }

            DB::purge();
            DB::reconnect();
            
            DB::connection()->getPdo();
            return true;

        } catch (\Exception $e) {
            return false;
        }
    }
}
