<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\User;
use App\Models\PadraoTipo;
use App\Models\Permissao;
use App\Models\Perfil;
use App\Models\PerfilPermissao;
use App\Observers\MenuObserver;
use App\Observers\PermissaoObserver;
use App\Observers\UsuarioObserver;
use App\Observers\PerfilObserver;
use App\Observers\PerfilPermissaoObserver;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Dispatcher $events): void
    {
        Menu::observe(MenuObserver::class);
        Permissao::observe(PermissaoObserver::class);
        User::observe(UsuarioObserver::class);
        Perfil::observe(PerfilObserver::class);
//        PerfilPermissao::observe(PerfilPermissaoObserver::class);

        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $situacaoHabilitado = PadraoTipo::where('descricao', 'Habilitado')->first();

            if (!$situacaoHabilitado) {
                return;
            }

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
                        'url' => URL::signedRoute($submenu->rota),
                        'icon' => $submenu->icone ?: 'far fa-circle',
                    ];
                }

                $event->menu->add([
                    'text' => $menu->descricao,
                    'icon' => $menu->icone,
                    'submenu' => $submenuItems,
                ]);
            }
        });
    }
}
