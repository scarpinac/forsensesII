<?php

namespace App\Providers;

use App\Models\Cadastro\Acabamento;
use App\Models\Cadastro\Comissao;
use App\Models\Cadastro\CondicaoPagamento;
use App\Models\Cadastro\Cor;
use App\Models\Cadastro\Familia;
use App\Models\Cadastro\OrigemProduto;
use App\Models\Cadastro\Produto;
use App\Models\Cadastro\RegraDesconto;
use App\Models\Cadastro\Revenda;
use App\Models\Cadastro\TabelaPreco;
use App\Models\Cadastro\Tela;
use App\Models\Cadastro\Transportadora;
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
use App\Observers\AcabamentoObserver;
use App\Observers\ApiObserver;
use App\Observers\ComissaoObserver;
use App\Observers\CondicaoPagamentoObserver;
use App\Observers\CorObserver;
use App\Observers\FamiliaObserver;
use App\Observers\GeradorCadastroCampoObserver;
use App\Observers\GeradorCadastrosObserver;
use App\Observers\MenuObserver;
use App\Observers\NotificacaoObserver;
use App\Observers\OrigemProdutoObserver;
use App\Observers\PadraoObserver;
use App\Observers\PadraoTipoObserver;
use App\Observers\ParametroObserver;
use App\Observers\PerfilObserver;
use App\Observers\PermissaoObserver;
use App\Observers\ProdutoObserver;
use App\Observers\RegraDescontoObserver;
use App\Observers\RevendaObserver;
use App\Observers\TabelaPrecoObserver;
use App\Observers\TelaObserver;
use App\Observers\TransportadoraObserver;
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
        Comissao::observe(ComissaoObserver::class);
        Transportadora::observe(TransportadoraObserver::class);
        Revenda::observe(RevendaObserver::class);
        Cor::observe(CorObserver::class);
        CondicaoPagamento::observe(CondicaoPagamentoObserver::class);
        Acabamento::observe(AcabamentoObserver::class);
        Tela::observe(TelaObserver::class);
        Familia::observe(FamiliaObserver::class);
        Produto::observe(ProdutoObserver::class);
        OrigemProduto::observe(OrigemProdutoObserver::class);
        RegraDesconto::observe(RegraDescontoObserver::class);
        TabelaPreco::observe(TabelaPrecoObserver::class);
//        PerfilPermissao::observe(PerfilPermissaoObserver::class);

        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $menuService = app(MenuService::class);
            $menuService->buildUserMenu($event);
        });
    }
}
