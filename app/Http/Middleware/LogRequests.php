<?php

namespace App\Http\Middleware;

use App\Logging\StructuredLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        // Log da requisição
        StructuredLogger::logUserAction('request_started', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'parameters' => $this->sanitizeParameters($request->all())
        ]);

        $response = $next($request);

        $duration = microtime(true) - $startTime;

        // Log da resposta
        StructuredLogger::logUserAction('request_completed', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status_code' => $response->getStatusCode(),
            'duration' => $duration
        ]);

        // Log de performance se for uma operação lenta
        if ($duration > 2.0) {
            StructuredLogger::logPerformance('http_request', $duration, [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'status_code' => $response->getStatusCode()
            ]);
        }

        return $response;
    }

    private function sanitizeParameters(array $parameters): array
    {
        $sensitiveKeys = ['password', 'password_confirmation', 'token', 'api_key', 'secret'];
        
        return collect($parameters)->map(function ($value, $key) use ($sensitiveKeys) {
            if (in_array(strtolower($key), $sensitiveKeys)) {
                return '***HIDDEN***';
            }
            
            if (is_array($value)) {
                return $this->sanitizeParameters($value);
            }
            
            return $value;
        })->toArray();
    }
}
