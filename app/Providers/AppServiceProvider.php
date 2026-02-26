<?php

namespace App\Providers;

use App\Models\Sistema\Api;
use App\Models\Sistema\GeradorCadastroCampo;
use App\Models\Sistema\GeradorCadastros;
use App\Models\Sistema\Menu;
use App\Models\Sistema\Notificacao;
use App\Models\Sistema\Padrao;
use App\Models\Sistema\PadraoTipo;
use App\Models\Sistema\Parametro;
use App\Models\Sistema\Perfil;
use App\Models\Sistema\Permissao;
use App\Models\Sistema\User;
use App\Observers\ApiObserver;
use App\Observers\GeradorCadastroCampoObserver;
use App\Observers\GeradorCadastrosObserver;
use App\Observers\MenuObserver;
use App\Observers\NotificacaoObserver;
use App\Observers\PadraoObserver;
use App\Observers\PadraoTipoObserver;
use App\Observers\ParametroObserver;
use App\Observers\PerfilObserver;
use App\Observers\PermissaoObserver;
use App\Observers\UserObserver;
use App\Services\MenuService;
use Illuminate\Contracts\Events\Dispatcher;
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
        User::observe(UserObserver::class);
        Perfil::observe(PerfilObserver::class);
        Padrao::observe(PadraoObserver::class);
        PadraoTipo::observe(PadraoTipoObserver::class);
        Notificacao::observe(NotificacaoObserver::class);
        Api::observe(ApiObserver::class);
        Parametro::observe(ParametroObserver::class);
        GeradorCadastros::observe(GeradorCadastrosObserver::class);
        GeradorCadastroCampo::observe(GeradorCadastroCampoObserver::class);
//        PerfilPermissao::observe(PerfilPermissaoObserver::class);

        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $menuService = app(MenuService::class);
            $menuService->buildUserMenu($event);
        });
    }
}
