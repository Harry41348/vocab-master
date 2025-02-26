<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class HealthCheckController extends BaseApiController
{
    public function check()
    {
        $healthStatus = [
            'status' => 'ok',
            'checks' => [],
        ];

        // Check Database Connection
        try {
            DB::connection()->getPdo();
            $healthStatus['checks']['database'] = 'connected';
        } catch (\Exception $e) {
            $healthStatus['status'] = 'error';
            $healthStatus['checks']['database'] = 'not connected';
        }

        // Check Cache Connection
        try {
            Cache::put('health_check_cache', 'working', 10);
            $cacheStatus = Cache::get('health_check_cache') === 'working' ? 'connected' : 'not connected';
            $healthStatus['checks']['cache'] = $cacheStatus;
        } catch (\Exception $e) {
            $healthStatus['status'] = 'error';
            $healthStatus['checks']['cache'] = 'not connected';
        }

        // Check Redis Connection
        try {
            Redis::ping();
            $healthStatus['checks']['redis'] = 'connected';
        } catch (\Exception $e) {
            $healthStatus['status'] = 'error';
            $healthStatus['checks']['redis'] = 'not connected';
        }

        // Return the health status as JSON
        return response()->json($healthStatus);
    }
}
