<?php

namespace App\Logging;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class StructuredLogger
{
    public static function logUserAction(string $action, array $context = [], string $level = 'info'): void
    {
        $user = auth()->user();
        
        $logData = [
            'event_type' => 'user_action',
            'action' => $action,
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'timestamp' => now()->toISOString(),
            'context' => $context
        ];

        Log::log($level, "User action: {$action}", $logData);
    }

    public static function logSystemEvent(string $event, array $context = [], string $level = 'info'): void
    {
        $logData = [
            'event_type' => 'system_event',
            'event' => $event,
            'timestamp' => now()->toISOString(),
            'context' => $context
        ];

        Log::log($level, "System event: {$event}", $logData);
    }

    public static function logSecurityEvent(string $event, array $context = [], string $level = 'warning'): void
    {
        $user = auth()->user();
        
        $logData = [
            'event_type' => 'security_event',
            'event' => $event,
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'timestamp' => now()->toISOString(),
            'context' => $context
        ];

        Log::log($level, "Security event: {$event}", $logData);
    }

    public static function logPerformance(string $operation, float $duration, array $context = []): void
    {
        $logData = [
            'event_type' => 'performance',
            'operation' => $operation,
            'duration_ms' => round($duration * 1000, 2),
            'timestamp' => now()->toISOString(),
            'context' => $context
        ];

        $level = $duration > 5.0 ? 'warning' : 'info';
        Log::log($level, "Performance: {$operation} took {$duration}s", $logData);
    }

    public static function logError(\Throwable $exception, array $context = []): void
    {
        $user = auth()->user();
        
        $logData = [
            'event_type' => 'error',
            'exception_class' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'ip_address' => Request::ip(),
            'timestamp' => now()->toISOString(),
            'context' => $context
        ];

        Log::error("Exception: {$exception->getMessage()}", $logData);
    }

    public static function logDatabaseOperation(string $operation, string $table, array $context = []): void
    {
        $user = auth()->user();
        
        $logData = [
            'event_type' => 'database_operation',
            'operation' => $operation,
            'table' => $table,
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'timestamp' => now()->toISOString(),
            'context' => $context
        ];

        Log::info("Database: {$operation} on {$table}", $logData);
    }
}
