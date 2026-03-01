<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ExampleController extends Controller
{
    /**
     * GET /api/example  — debug info for Railway troubleshooting
     */
    public function index()
    {
        $dbStatus = 'unknown';
        $dbError = null;

        try {
            DB::connection()->getPdo();
            $dbStatus = 'connected';
        } catch (\Exception $e) {
            $dbStatus = 'failed';
            $dbError = $e->getMessage();
        }

        return response()->json([
            'message' => 'Hello from Chado-pedia backend!',
            'status' => 'success',
            'debug' => [
                'php_version' => PHP_VERSION,
                'db_connection' => env('DB_CONNECTION'),
                'db_host' => env('MYSQL_HOST', env('DB_HOST', 'not set')),
                'db_port' => env('MYSQL_PORT', env('DB_PORT', 'not set')),
                'db_database' => env('MYSQL_DATABASE', env('MYSQL_NAME', env('DB_DATABASE', 'not set'))),
                'db_username' => env('MYSQL_USER', env('DB_USERNAME', 'not set')),
                'db_status' => $dbStatus,
                'db_error' => $dbError,
                'session_driver' => env('SESSION_DRIVER'),
                'cache_store' => env('CACHE_STORE'),
                'app_env' => env('APP_ENV'),
            ],
        ]);
    }
}
