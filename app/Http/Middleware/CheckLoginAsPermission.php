<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckLoginAsPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            abort(403, 'Acesso não autorizado');
        }

        // Verifica se o usuário tem permissão para fazer login como outros usuários
        if (!Auth::user()->canAccess('sistema.usuario.loginAs')) {
            abort(403, 'Acesso não autorizado');
        }

        // Impede que usuários admin façam login como eles mesmos
        $route = $request->route();
        if ($route && $route->hasParameter('usuario')) {
            $targetUser = $route->parameter('usuario');
            if ($targetUser && $targetUser->id === Auth::user()->id) {
                abort(403, 'Você não pode fazer login como você mesmo');
            }
        }

        return $next($request);
    }
}
