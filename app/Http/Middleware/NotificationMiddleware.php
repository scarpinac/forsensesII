<?php

namespace App\Http\Middleware;

use App\Services\NotificationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class NotificationMiddleware
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Adicionar contagem de notificações não lidas para todas as views
        if (auth()->check()) {
            $user = auth()->user();
            $unreadCount = $this->notificationService->getUnreadCount($user);

            // Compartilhar com todas as views
            View::share('unreadNotificationsCount', $unreadCount);

            // Também compartilhar as notificações recentes para o dropdown
            $recentNotifications = $this->notificationService->getUnreadNotifications($user)
                ->take(10)
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => \Illuminate\Support\Str::limit($notification->message, 50),
                        'type' => $notification->type,
                        'icon' => $notification->icon,
                        'url' => $notification->url,
                        'created_at' => $notification->created_at->diffForHumans(),
                    ];
                });

            View::share('recentNotifications', $recentNotifications);
        }

        return $next($request);
    }
}
