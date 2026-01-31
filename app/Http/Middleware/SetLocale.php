<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se há um idioma na sessão
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        // Se não, usa o idioma padrão do config
        else {
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
