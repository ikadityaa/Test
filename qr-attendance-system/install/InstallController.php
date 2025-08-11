<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class InstallController extends Controller
{
    private $error = '';
    private $debug = '';

    public function index(Request $request)
    {
        $step = $request->input('step', 1);
        $passed_steps = [
            1 => false,
            2 => false,
            3 => false,
        ];

        if ($request->isMethod('post')) {
            if ($request->input('step') == 2) {
                $validator = Validator::make($request->all(), [
                    'hostname' => 'required',
                    'database' => 'required',
                    'username' => 'required',
                    'password' => 'nullable',
                ]);

                if ($validator->fails()) {
                    $this->error = $validator->errors()->first();
                } else {
                    $passed_steps[1] = true;
                    
                    // Test database connection
                    try {
                        $connection = mysqli_connect(
                            $request->input('hostname'),
                            $request->input('username'),
                            $request->input('password'),
                            $request->input('database')
                        );

                        if (!$connection) {
                            $this->error = "Error: Unable to connect to MySQL Database.";
                        } else {
                            $this->debug = "Success: Connection to " . $request->input('database') . " database is done successfully.";
                            
                            if ($this->writeDatabaseConfig($request)) {
                                $step = 3;
                                $passed_steps[2] = true;
                            }
                            mysqli_close($connection);
                        }
                    } catch (\Exception $e) {
                        $this->error = "Database connection failed: " . $e->getMessage();
                    }
                }
            } elseif ($request->input('step') == 3) {
                $validator = Validator::make($request->all(), [
                    'admin_email' => 'required|email',
                    'admin_password' => 'required|min:6',
                    'admin_passwordr' => 'required|same:admin_password',
                ]);

                if ($validator->fails()) {
                    $this->error = $validator->errors()->first();
                } else {
                    $passed_steps[1] = true;
                    $passed_steps[2] = true;
                    $step = 3;
                }

                if ($this->error === '' && $request->input('step') == 3) {
                    if ($this->runMigrationsAndSeeders($request)) {
                        $passed_steps[1] = true;
                        $passed_steps[2] = true;
                        $passed_steps[3] = true;
                        $step = 4;
                    }
                }
            } elseif ($request->input('requirements_success')) {
                $step = 2;
                $passed_steps[1] = true;
            }
        }

        return view('install.index', compact('step', 'passed_steps', 'error', 'debug'));
    }

    public function deleteInstallDir()
    {
        $installPath = base_path('install');
        
        if (File::exists($installPath)) {
            File::deleteDirectory($installPath);
            return redirect('/admin');
        }
        
        return redirect('/');
    }

    private function writeDatabaseConfig(Request $request)
    {
        $hostname = $request->input('hostname');
        $database = $request->input('database');
        $username = $request->input('username');
        $password = $request->input('password');

        $envContent = "APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:" . base64_encode(random_bytes(32)) . "
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST={$hostname}
DB_PORT=3306
DB_DATABASE={$database}
DB_USERNAME={$username}
DB_PASSWORD={$password}

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=\"hello@example.com\"
MAIL_FROM_NAME=\"\${APP_NAME}\"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME=\"\${APP_NAME}\"
VITE_PUSHER_APP_KEY=\"\${PUSHER_APP_KEY}\"
VITE_PUSHER_HOST=\"\${PUSHER_HOST}\"
VITE_PUSHER_PORT=\"\${PUSHER_PORT}\"
VITE_PUSHER_SCHEME=\"\${PUSHER_SCHEME}\"
VITE_PUSHER_APP_CLUSTER=\"\${PUSHER_APP_CLUSTER}\"

INSTALLED=true";

        try {
            File::put(base_path('.env'), $envContent);
            
            // Clear config cache
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            
            return true;
        } catch (\Exception $e) {
            $this->error = "Failed to write .env file: " . $e->getMessage();
            return false;
        }
    }

    private function runMigrationsAndSeeders(Request $request)
    {
        try {
            // Run migrations
            Artisan::call('migrate:fresh', ['--force' => true]);
            
            // Create admin user
            $this->createAdminUser($request);
            
            // Mark as installed
            $this->markAsInstalled();
            
            return true;
        } catch (\Exception $e) {
            $this->error = "Installation failed: " . $e->getMessage();
            return false;
        }
    }

    private function createAdminUser(Request $request)
    {
        // Create users table if it doesn't exist
        if (!Schema::hasTable('users')) {
            Artisan::call('migrate', ['--force' => true]);
        }

        // Create admin user
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => $request->input('admin_email'),
            'password' => Hash::make($request->input('admin_password')),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create roles table and assign admin role
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('guard_name')->default('web');
                $table->timestamps();
            });
        }

        // Insert admin role
        DB::table('roles')->insert([
            'name' => 'Super Admin',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create role_user pivot table
        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', function ($table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('role_id');
                $table->timestamps();
            });
        }

        // Assign admin role to user
        DB::table('role_user')->insert([
            'user_id' => 1,
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function markAsInstalled()
    {
        // Create a marker file
        File::put(base_path('installed'), date('Y-m-d H:i:s'));
        
        // Update .env file to mark as installed
        $envPath = base_path('.env');
        $envContent = File::get($envPath);
        $envContent = str_replace('INSTALLED=false', 'INSTALLED=true', $envContent);
        File::put($envPath, $envContent);
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
            'Fileinfo Extension' => extension_loaded('fileinfo'),
            'GD Extension' => extension_loaded('gd'),
            'Curl Extension' => extension_loaded('curl'),
            'Zip Extension' => extension_loaded('zip'),
            'SQLite3 Extension' => extension_loaded('sqlite3'),
        ];

        $permissions = [
            'storage/app' => is_writable(storage_path('app')),
            'storage/framework' => is_writable(storage_path('framework')),
            'storage/logs' => is_writable(storage_path('logs')),
            'bootstrap/cache' => is_writable(base_path('bootstrap/cache')),
            '.env file' => is_writable(base_path('.env')) || !file_exists(base_path('.env')),
        ];

        return view('install.requirements', compact('requirements', 'permissions'));
    }
}