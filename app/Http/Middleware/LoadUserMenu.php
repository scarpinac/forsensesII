<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use App\Models\Permissao;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoadUserMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está autenticado
        if (Auth::check()) {
            // Verifica se o menu já não está na sessão
            if (!session()->has('user_menu')) {
                $this->buildAndStoreMenu();
            }
        }

        return $next($request);
    }

    /**
     * Constrói a estrutura do menu e armazena na sessão.
     */
    protected function buildAndStoreMenu()
    {
        $user = Auth::user();

        // Se for admin, carrega todos os menus
        if ($user->admin) {
            $menus = Menu::with('permissao', 'situacao', 'submenus')
                ->whereNull('menuPai_id')
                ->orderBy('descricao')
                ->get();
        } else {
            // Se não for admin, carrega menus baseado nas permissões
            $userPermissions = collect(session()->get('permissoes'))->flatten();

            $menus = Menu::with('permissao', 'situacao', 'submenus')
                ->whereNull('menuPai_id')
                ->whereHas('permissao', function ($query) use ($userPermissions) {
                    $query->whereIn('descricao', $userPermissions);
                })
                ->orderBy('descricao')
                ->get();
        }

        // Formata o menu para a view do AdminLTE
        $formattedMenu = $this->formatMenuForAdminLTE($menus);

        // Armazena na sessão
        session(['user_menu' => $formattedMenu]);
    }

    /**
     * Formata a coleção de menus para a estrutura esperada pelo AdminLTE.
     */
    protected function formatMenuForAdminLTE($menus)
    {
        return $menus->map(function ($menu) {
            $item = [
                'text' => $menu->descricao,
                'url' => $menu->rota && $menu->rota !== '#' ? route($menu->rota) : '#',
                'icon' => $menu->icone ?? 'fas fa-circle',
            ];

            // Se o menu tem filhos, processa recursivamente
            if ($menu->submenus->isNotEmpty()) {
                $item['submenu'] = $this->formatMenuForAdminLTE($menu->submenus);
            }

            return $item;
        })->toArray();
    }
}
