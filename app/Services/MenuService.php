<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\PadraoTipo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class MenuService
{
    /**
     * Monta o menu do usuário baseado em suas permissões
     */
    public function buildUserMenu(BuildingMenu $event): void
    {
        $situacaoHabilitado = PadraoTipo::where('descricao', 'Habilitado')->first();

        if (!$situacaoHabilitado) {
            return;
        }

        // Verificar se o usuário é admin
        $user = Auth::user();
        if ($user && $user->admin) {
            $this->buildFullMenu($event, $situacaoHabilitado);
            return;
        }

        // Verificar se o usuário tem perfil
        if (!$user || !$user->perfis()->exists()) {
            return; // Usuário sem perfil e não admin não vê menu
        }

        // Obter permissões do usuário através dos perfis
        $userPermissions = $this->getUserPermissions($user);

        // Montar menu baseado nas permissões
        $this->buildMenuByPermissions($event, $situacaoHabilitado, $userPermissions);
    }

    /**
     * Monta o menu completo para administradores
     */
    private function buildFullMenu(BuildingMenu $event, PadraoTipo $situacaoHabilitado): void
    {
        $menus = Menu::with(['submenus' => function ($query) use ($situacaoHabilitado) {
                $query->where('situacao_id', $situacaoHabilitado->id)->orderBy('descricao', 'asc');
            }])
            ->whereNull('menuPai_id')
            ->where('situacao_id', $situacaoHabilitado->id)
            ->orderBy('descricao', 'asc')
            ->get();

        foreach ($menus as $menu) {
            $submenuItems = [];
            foreach ($menu->submenus as $submenu) {
                $submenuItems[] = [
                    'text' => $submenu->descricao,
                    'url' => $submenu->rota && $submenu->rota != "#" ? URL::signedRoute($submenu->rota) : '#',
                    'icon' => $submenu->icone ?: 'far fa-circle',
                ];
            }

            $event->menu->add([
                'text' => $menu->descricao,
                'icon' => $menu->icone,
                'submenu' => $submenuItems,
            ]);
        }
    }

    /**
     * Monta o menu baseado nas permissões do usuário
     */
    private function buildMenuByPermissions(BuildingMenu $event, PadraoTipo $situacaoHabilitado, array $userPermissions): void
    {
        // Obter todos os menus principais
        $menus = Menu::with(['submenus' => function ($query) use ($situacaoHabilitado) {
                $query->where('situacao_id', $situacaoHabilitado->id)->orderBy('descricao', 'asc');
            }])
            ->whereNull('menuPai_id')
            ->where('situacao_id', $situacaoHabilitado->id)
            ->orderBy('descricao', 'asc')
            ->get();

        foreach ($menus as $menu) {
            $submenuItems = [];
            $hasAccessibleSubmenu = false;

            foreach ($menu->submenus as $submenu) {
                // Verificar se o usuário tem permissão para este submenu
                if ($this->hasPermissionForRoute($userPermissions, $submenu->rota)) {
                    $submenuItems[] = [
                        'text' => $submenu->descricao,
                        'url' => URL::signedRoute($submenu->rota),
                        'icon' => $submenu->icone ?: 'far fa-circle',
                    ];
                    $hasAccessibleSubmenu = true;
                }
            }

            // Adicionar menu pai apenas se tiver submenus acessíveis
            if ($hasAccessibleSubmenu) {
                $event->menu->add([
                    'text' => $menu->descricao,
                    'icon' => $menu->icone,
                    'submenu' => $submenuItems,
                ]);
            }
        }
    }

    /**
     * Obtém todas as permissões do usuário através de seus perfis
     */
    private function getUserPermissions($user): array
    {
        // Obter permissões através dos perfis do usuário
        return $user->perfis()
            ->with(['perfilPermissoes.permissao'])
            ->get()
            ->flatMap(function ($perfil) {
                return $perfil->perfilPermissoes->pluck('permissao.descricao');
            })
            ->unique()
            ->toArray();
    }

    /**
     * Verifica se o usuário tem permissão para uma rota específica
     */
    private function hasPermissionForRoute(array $userPermissions, string $route): bool
    {
        // Mapeamento de rotas para permissões correspondentes
        $routePermissionMap = [
            // Sistema
            'sistema.menu.index' => 'sistema.menu.index',
            'sistema.menu.create' => 'sistema.menu.create',
            'sistema.menu.edit' => 'sistema.menu.edit',
            'sistema.menu.destroy' => 'sistema.menu.destroy',
            'sistema.menu.history' => 'sistema.menu.history',

            // Permissões
            'sistema.permissao.index' => 'sistema.permissao.index',
            'sistema.permissao.create' => 'sistema.permissao.create',
            'sistema.permissao.edit' => 'sistema.permissao.edit',
            'sistema.permissao.destroy' => 'sistema.permissao.destroy',
            'sistema.permissao.history' => 'sistema.permissao.history',

            // Usuários
            'sistema.usuario.index' => 'sistema.usuario.index',
            'sistema.usuario.create' => 'sistema.usuario.create',
            'sistema.usuario.edit' => 'sistema.usuario.edit',
            'sistema.usuario.destroy' => 'sistema.usuario.destroy',
            'sistema.usuario.history' => 'sistema.usuario.history',

            // Perfis
            'sistema.perfil.index' => 'sistema.perfil.index',
            'sistema.perfil.create' => 'sistema.perfil.create',
            'sistema.perfil.edit' => 'sistema.perfil.edit',
            'sistema.perfil.destroy' => 'sistema.perfil.destroy',
            'sistema.perfil.history' => 'sistema.perfil.history',
            'sistema.perfil.associate' => 'sistema.perfil.associate',
            'sistema.perfil.associate.update' => 'sistema.perfil.associate.update',
        ];

        // Verificar se a rota está no mapeamento
        if (isset($routePermissionMap[$route])) {
            return in_array($routePermissionMap[$route], $userPermissions);
        }

        // Para rotas não mapeadas, usar a própria rota como permissão
        return in_array($route, $userPermissions);
    }
}
