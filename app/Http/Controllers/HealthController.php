<?php

namespace App\Http\Controllers;

use App\Logging\StructuredLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HealthController extends Controller
{
    public function index(Request $request)
    {
        $startTime = microtime(true);
        
        $health = [
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env'),
            'checks' => [
                'database' => $this->checkDatabase(),
                'cache' => $this->checkCache(),
                'storage' => $this->checkStorage(),
                'memory' => $this->checkMemory(),
                'disk_space' => $this->checkDiskSpace(),
            ],
            'metrics' => [
                'users_count' => $this->getUsersCount(),
                'active_sessions' => $this->getActiveSessions(),
                'recent_errors' => $this->getRecentErrors(),
                'cache_hit_rate' => $this->getCacheHitRate(),
            ]
        ];

        // Verificar se algum check falhou
        foreach ($health['checks'] as $check => $result) {
            if ($result['status'] !== 'ok') {
                $health['status'] = 'degraded';
                StructuredLogger::logSystemEvent('health_check_failed', [
                    'check' => $check,
                    'result' => $result
                ], 'warning');
            }
        }

        $duration = microtime(true) - $startTime;
        StructuredLogger::logPerformance('health_check', $duration);

        $statusCode = $health['status'] === 'healthy' ? 200 : 503;
        
        return response()->json($health, $statusCode);
    }

    public function metrics(Request $request)
    {
        // Apenas para monitoramento interno
        if (!$request->has('token') || $request->get('token') !== config('app.metrics_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $metrics = [
            'timestamp' => now()->toISOString(),
            'system' => [
                'memory_usage' => memory_get_usage(true),
                'memory_peak' => memory_get_peak_usage(true),
                'cpu_load' => sys_getloadavg()[0] ?? 0,
            ],
            'application' => [
                'users_total' => $this->getUsersCount(),
                'users_active_today' => $this->getActiveUsersToday(),
                'sessions_active' => $this->getActiveSessions(),
                'cache_hit_rate' => $this->getCacheHitRate(),
                'database_connections' => $this->getDatabaseConnections(),
            ],
            'business' => [
                'cruds_generated_today' => $this->getCrudsGeneratedToday(),
                'permission_cache_hits' => $this->getPermissionCacheHits(),
                'recent_errors_count' => $this->getRecentErrors(),
            ]
        ];

        return response()->json($metrics);
    }

    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            return [
                'status' => 'ok',
                'message' => 'Database connection successful',
                'connection' => config('database.default')
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database connection failed',
                'error' => $e->getMessage()
            ];
        }
    }

    private function checkCache(): array
    {
        try {
            $testKey = 'health_check_' . time();
            Cache::put($testKey, 'test', 60);
            $value = Cache::get($testKey);
            Cache::forget($testKey);

            if ($value === 'test') {
                return [
                    'status' => 'ok',
                    'message' => 'Cache working properly',
                    'driver' => config('cache.default')
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Cache read/write test failed'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Cache error',
                'error' => $e->getMessage()
            ];
        }
    }

    private function checkStorage(): array
    {
        try {
            $testFile = 'health_check_' . time() . '.txt';
            Storage::put($testFile, 'test');
            $exists = Storage::exists($testFile);
            Storage::delete($testFile);

            if ($exists) {
                return [
                    'status' => 'ok',
                    'message' => 'Storage working properly',
                    'disk' => config('filesystems.default')
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Storage read/write test failed'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Storage error',
                'error' => $e->getMessage()
            ];
        }
    }

    private function checkMemory(): array
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseMemoryLimit(ini_get('memory_limit'));
        $usagePercent = ($memoryUsage / $memoryLimit) * 100;

        return [
            'status' => $usagePercent < 80 ? 'ok' : 'warning',
            'message' => "Memory usage: {$usagePercent}%",
            'usage_bytes' => $memoryUsage,
            'limit_bytes' => $memoryLimit,
            'usage_percent' => round($usagePercent, 2)
        ];
    }

    private function checkDiskSpace(): array
    {
        $freeBytes = disk_free_space('/');
        $totalBytes = disk_total_space('/');
        $usagePercent = (($totalBytes - $freeBytes) / $totalBytes) * 100;

        return [
            'status' => $usagePercent < 90 ? 'ok' : 'warning',
            'message' => "Disk usage: {$usagePercent}%",
            'free_bytes' => $freeBytes,
            'total_bytes' => $totalBytes,
            'usage_percent' => round($usagePercent, 2)
        ];
    }

    private function getUsersCount(): int
    {
        return Cache::remember('health_users_count', 300, function () {
            return \App\Models\User::count();
        });
    }

    private function getActiveSessions(): int
    {
        return Cache::remember('health_active_sessions', 60, function () {
            try {
                return DB::table('sessions')->count();
            } catch (\Exception $e) {
                return 0;
            }
        });
    }

    private function getRecentErrors(): int
    {
        return Cache::remember('health_recent_errors', 300, function () {
            // Isso depende da sua configuração de logging
            // Você pode implementar contagem de erros dos logs
            return 0;
        });
    }

    private function getCacheHitRate(): float
    {
        // Implementação básica - você pode melhorar com Redis stats
        return rand(85, 95); // Placeholder
    }

    private function getActiveUsersToday(): int
    {
        return Cache::remember('health_active_users_today', 300, function () {
            return \App\Models\User::whereDate('last_login_at', today())->count();
        });
    }

    private function getDatabaseConnections(): int
    {
        // Implementação depende do seu driver
        return 1; // Placeholder
    }

    private function getCrudsGeneratedToday(): int
    {
        return Cache::remember('health_cruds_today', 300, function () {
            return \App\Models\GeradorCadastros::whereDate('created_at', today())->count();
        });
    }

    private function getPermissionCacheHits(): int
    {
        // Implementar contador de hits do cache de permissões
        return Cache::get('permission_cache_hits', 0);
    }

    private function parseMemoryLimit(string $limit): int
    {
        $limit = strtolower($limit);
        $multiplier = 1;

        if (str_ends_with($limit, 'g')) {
            $multiplier = 1024 * 1024 * 1024;
        } elseif (str_ends_with($limit, 'm')) {
            $multiplier = 1024 * 1024;
        } elseif (str_ends_with($limit, 'k')) {
            $multiplier = 1024;
        }

        return (int) $limit * $multiplier;
    }
}
