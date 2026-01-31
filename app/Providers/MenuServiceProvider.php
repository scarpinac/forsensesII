<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\PadraoTipo;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('adminlte::page', function ($view) {
            $situacaoHabilitado = PadraoTipo::where('descricao', 'Habilitado')->first();

            $menus = collect();
            if ($situacaoHabilitado) {
                $menus = Menu::with(['submenus' => function ($query) use ($situacaoHabilitado) {
                        $query->where('situacao_id', $situacaoHabilitado->id)->orderBy('descricao', 'asc');
                    }])
                    ->whereNull('menuPai_id')
                    ->where('situacao_id', $situacaoHabilitado->id)
                    ->orderBy('descricao', 'asc')
                    ->get();
            }

            $view->with('menuSistema', $menus);
        });
    }
}
