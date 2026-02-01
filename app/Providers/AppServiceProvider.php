<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\User;
use App\Models\PadraoTipo;
use App\Models\Permissao;
use App\Models\Perfil;
use App\Models\PerfilPermissao;
use App\Models\Padrao;
use App\Observers\MenuObserver;
use App\Observers\PermissaoObserver;
use App\Observers\UsuarioObserver;
use App\Observers\PerfilObserver;
use App\Observers\PerfilPermissaoObserver;
use App\Observers\PadraoObserver;
use App\Observers\PadraoTipoObserver;
use App\Services\MenuService;
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
        $this->app->singleton(MenuService::class);
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
        Padrao::observe(PadraoObserver::class);
        PadraoTipo::observe(PadraoTipoObserver::class);
//        PerfilPermissao::observe(PerfilPermissaoObserver::class);

        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $menuService = app(MenuService::class);
            $menuService->buildUserMenu($event);
        });
    }
}
