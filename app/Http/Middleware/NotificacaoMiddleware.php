<?php

namespace App\Http\Middleware;

use App\Services\NotificacaoService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NotificacaoMiddleware
{
    protected NotificacaoService $notificacaoService;

    public function __construct(NotificacaoService $notificacaoService)
    {
        $this->notificacaoService = $notificacaoService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Compartilhar notificações com todas as views
        if (Auth::check()) {
            $user = Auth::user();
            
            // Obter contagem de notificações não lidas
            $unreadNotificationsCount = $this->notificacaoService->getContagemNaoLidas($user);
            
            // Obter notificações recentes para o dropdown
            $recentNotifications = $this->notificacaoService->getNotificacoesRecentes($user, 5);
            
            // Compartilhar com as views
            view()->share('unreadNotificationsCount', $unreadNotificationsCount);
            view()->share('recentNotifications', $recentNotifications);
        }

        return $next($request);
    }
}
