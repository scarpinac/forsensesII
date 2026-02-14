<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeracaoController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Sistema\ApiController;
use App\Http\Controllers\Sistema\MenuController;
use App\Http\Controllers\Sistema\ParametroController;
use App\Http\Controllers\Sistema\PermissaoController;
use App\Http\Controllers\Sistema\UsuarioController;
use App\Http\Controllers\Sistema\PerfilController;
use App\Http\Controllers\Sistema\PerfilPermissaoController;
use App\Http\Controllers\Sistema\PadraoController;
use App\Http\Controllers\Sistema\PadraoTipoController;
use App\Http\Controllers\Sistema\NotificacaoController;
use App\Http\Controllers\Sistema\GeradorCadastrosController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});


# ROTAS DO GERADOR DE CADASTROS
Route::prefix('gerador')->name('gerador.')->group(function () {
    Route::get('/', [GeradorCadastrosController::class, 'index'])->name('index');
    Route::post('/generate', [GeradorCadastrosController::class, 'generate'])->name('generate');
    Route::get('/modulos', [GeradorCadastrosController::class, 'getModulosDisponiveis'])->name('modulos');
    Route::get('/tabelas', [GeradorCadastrosController::class, 'getTabelasDisponiveis'])->name('tabelas');
});

Route::post('/language', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'signed'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas do Sistema
    Route::prefix('sistema')->name('sistema.')->group(function () {
        # ROTAS DO MENU
        Route::prefix('menu')->name('menu.')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('index');
            Route::get('/create', [MenuController::class, 'create'])->name('create');
            Route::post('/', [MenuController::class, 'store'])->name('store');

            Route::get('/{menu}/edit', [MenuController::class, 'edit'])->name('edit');
            Route::put('/{menu}', [MenuController::class, 'update'])->name('update');
            Route::get('/{menu}/destroy', [MenuController::class, 'destroy'])->name('destroy');
            Route::delete('/{menu}', [MenuController::class, 'delete'])->name('delete');
            Route::get('/{menu}/history', [MenuController::class, 'history'])->name('history');
            Route::get('/{menu}/history/{historico}/details', [MenuController::class, 'historyDetails'])->name('history.details');

            Route::get('/{menu}', [MenuController::class, 'show'])->name('show');
        });

        # ROTAS DA PERMISSÃO
        Route::prefix('permissao')->name('permissao.')->group(function () {
            Route::get('/', [PermissaoController::class, 'index'])->name('index');
            Route::get('/create', [PermissaoController::class, 'create'])->name('create');
            Route::post('/', [PermissaoController::class, 'store'])->name('store');

            Route::get('/{permissao}/edit', [PermissaoController::class, 'edit'])->name('edit');
            Route::put('/{permissao}', [PermissaoController::class, 'update'])->name('update');
            Route::get('/{permissao}/destroy', [PermissaoController::class, 'destroy'])->name('destroy');
            Route::delete('/{permissao}', [PermissaoController::class, 'delete'])->name('delete');
            Route::get('/{permissao}/history', [PermissaoController::class, 'history'])->name('history');
            Route::get('/{permissao}/history/{historico}/details', [PermissaoController::class, 'historyDetails'])->name('history.details');

            Route::get('/{permissao}', [PermissaoController::class, 'show'])->name('show');
        });

        # ROTAS DO USUÁRIO
        Route::prefix('usuario')->name('usuario.')->group(function () {
            Route::get('/', [UsuarioController::class, 'index'])->name('index');
            Route::get('/create', [UsuarioController::class, 'create'])->name('create');
            Route::post('/', [UsuarioController::class, 'store'])->name('store');

            Route::get('/{usuario}/edit', [UsuarioController::class, 'edit'])->name('edit');
            Route::put('/{usuario}', [UsuarioController::class, 'update'])->name('update');
            Route::get('/{usuario}/destroy', [UsuarioController::class, 'destroy'])->name('destroy');
            Route::delete('/{usuario}', [UsuarioController::class, 'delete'])->name('delete');
            Route::get('/{usuario}/history', [UsuarioController::class, 'history'])->name('history');
            Route::get('/{usuario}/history/{historico}/details', [UsuarioController::class, 'historyDetails'])->name('history.details');

            Route::get('/{usuario}', [UsuarioController::class, 'show'])->name('show');
        });

        # ROTAS DO USUÁRIO
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/', [ApiController::class, 'index'])->name('index');
            Route::get('/create', [ApiController::class, 'create'])->name('create');
            Route::post('/', [ApiController::class, 'store'])->name('store');

            Route::get('/{api}/edit', [ApiController::class, 'edit'])->name('edit');
            Route::put('/{api}', [ApiController::class, 'update'])->name('update');
            Route::get('/{api}/destroy', [ApiController::class, 'destroy'])->name('destroy');
            Route::delete('/{api}', [ApiController::class, 'delete'])->name('delete');
            Route::get('/{api}/history', [ApiController::class, 'history'])->name('history');
            Route::get('/{api}/history/{historico}/details', [ApiController::class, 'historyDetails'])->name('history.details');

            Route::get('/{api}', [ApiController::class, 'show'])->name('show');
        });

        # ROTAS DO USUÁRIO
        Route::prefix('parametro')->name('parametro.')->group(function () {
            Route::get('/', [ParametroController::class, 'index'])->name('index');
            Route::get('/create', [ParametroController::class, 'create'])->name('create');
            Route::post('/', [ParametroController::class, 'store'])->name('store');

            Route::get('/{parametro}/edit', [ParametroController::class, 'edit'])->name('edit');
            Route::put('/{parametro}', [ParametroController::class, 'update'])->name('update');
            Route::get('/{parametro}/destroy', [ParametroController::class, 'destroy'])->name('destroy');
            Route::delete('/{parametro}', [ParametroController::class, 'delete'])->name('delete');
            Route::get('/{parametro}/history', [ParametroController::class, 'history'])->name('history');
            Route::get('/{parametro}/history/{historico}/details', [ParametroController::class, 'historyDetails'])->name('history.details');

            Route::get('/{parametro}', [ParametroController::class, 'show'])->name('show');
        });

        # ROTAS DO PERFIL
        Route::prefix('perfil')->name('perfil.')->group(function () {
            Route::get('/', [PerfilController::class, 'index'])->name('index');
            Route::get('/create', [PerfilController::class, 'create'])->name('create');
            Route::post('/', [PerfilController::class, 'store'])->name('store');

            Route::get('/{perfil}/edit', [PerfilController::class, 'edit'])->name('edit');
            Route::put('/{perfil}', [PerfilController::class, 'update'])->name('update');
            Route::get('/{perfil}/destroy', [PerfilController::class, 'destroy'])->name('destroy');
            Route::delete('/{perfil}', [PerfilController::class, 'delete'])->name('delete');
            Route::get('/{perfil}/history', [PerfilController::class, 'history'])->name('history');
            Route::get('/{perfil}/history/{historico}/details', [PerfilController::class, 'historyDetails'])->name('history.details');
            Route::get('/{perfil}/associate', [PerfilController::class, 'associate'])->name('associate');
            Route::put('/{perfil}/associate', [PerfilController::class, 'associateUpdate'])->name('associate.update');

            Route::get('/{perfil}', [PerfilController::class, 'show'])->name('show');

            # ROTAS DO PERFIL PERMISSÃO (aninhadas dentro de perfil)
            Route::prefix('{perfil}/permissao')->name('permissao.')->group(function () {
                Route::get('/', [PerfilPermissaoController::class, 'index'])->name('index');
                Route::get('/create', [PerfilPermissaoController::class, 'create'])->name('create');
                Route::post('/', [PerfilPermissaoController::class, 'store'])->name('store');

                Route::get('/{perfilPermissao}/edit', [PerfilPermissaoController::class, 'edit'])->name('edit');
                Route::put('/{perfilPermissao}', [PerfilPermissaoController::class, 'update'])->name('update');
                Route::get('/{perfilPermissao}/destroy', [PerfilPermissaoController::class, 'destroy'])->name('destroy');
                Route::delete('/{perfilPermissao}', [PerfilPermissaoController::class, 'delete'])->name('delete');
                Route::get('/{perfilPermissao}/history', [PerfilPermissaoController::class, 'history'])->name('history');
                Route::get('/{perfilPermissao}/history/{historico}/details', [PerfilPermissaoController::class, 'historyDetails'])->name('history.details');

                Route::get('/{perfilPermissao}', [PerfilPermissaoController::class, 'show'])->name('show');
            });
        });

        # ROTAS DO PADRÃO
        Route::prefix('padrao')->name('padrao.')->group(function () {
            Route::get('/', [PadraoController::class, 'index'])->name('index');
            Route::get('/create', [PadraoController::class, 'create'])->name('create');
            Route::post('/', [PadraoController::class, 'store'])->name('store');

            Route::get('/{padrao}/edit', [PadraoController::class, 'edit'])->name('edit');
            Route::put('/{padrao}', [PadraoController::class, 'update'])->name('update');
            Route::get('/{padrao}/destroy', [PadraoController::class, 'destroy'])->name('destroy');
            Route::delete('/{padrao}', [PadraoController::class, 'delete'])->name('delete');
            Route::get('/{padrao}/history', [PadraoController::class, 'history'])->name('history');
            Route::get('/{padrao}/history/{historico}/details', [PadraoController::class, 'historyDetails'])->name('history.details');

            Route::get('/{padrao}', [PadraoController::class, 'show'])->name('show');

            # ROTAS DO PADRÃO TIPO (aninhadas dentro de padrão)
            Route::prefix('{padrao}/padraoTipo')->name('padraoTipo.')->group(function () {
                Route::get('/', [PadraoTipoController::class, 'index'])->name('index');
                Route::get('/create', [PadraoTipoController::class, 'create'])->name('create');
                Route::post('/', [PadraoTipoController::class, 'store'])->name('store');

                Route::get('/{padraoTipo}/edit', [PadraoTipoController::class, 'edit'])->name('edit');
                Route::put('/{padraoTipo}', [PadraoTipoController::class, 'update'])->name('update');
                Route::get('/{padraoTipo}/destroy', [PadraoTipoController::class, 'destroy'])->name('destroy');
                Route::delete('/{padraoTipo}', [PadraoTipoController::class, 'delete'])->name('delete');
                Route::get('/{padraoTipo}/history', [PadraoTipoController::class, 'history'])->name('history');
                Route::get('/{padraoTipo}/history/{historico}/details', [PadraoTipoController::class, 'historyDetails'])->name('history.details');

                Route::get('/{padraoTipo}', [PadraoTipoController::class, 'show'])->name('show');
            });
        });


        // Rotas de Notificações
        Route::prefix('notificacao')->name('notificacao.')->group(function () {
            Route::get('/', [NotificacaoController::class, 'index'])->name('index');
            Route::get('/create', [NotificacaoController::class, 'create'])->name('create');
            Route::post('/', [NotificacaoController::class, 'store'])->name('store');
            Route::get('/{notificacao}', [NotificacaoController::class, 'show'])->name('show');
            Route::get('/{notificacao}/edit', [NotificacaoController::class, 'edit'])->name('edit');
            Route::put('/{notificacao}', [NotificacaoController::class, 'update'])->name('update');
            Route::get('/{notificacao}/destroy', [NotificacaoController::class, 'destroy'])->name('destroy');
            Route::delete('/{notificacao}', [NotificacaoController::class, 'delete'])->name('delete');

            Route::get('/{notificacao}/history', [NotificacaoController::class, 'history'])->name('history');
            Route::get('/{notificacao}/history/{historico}/details', [NotificacaoController::class, 'historyDetails'])->name('history.details');

            Route::post('/marcar-como-lida', [NotificacaoController::class, 'marcarComoLida'])->name('marcar-como-lida');
            Route::get('/{notificacao}/detalhes', [NotificacaoController::class, 'getNotificationDetails'])->name('detalhes');

        });


        # ROTAS DE GERACOES
        Route::prefix('geracoes')->name('geracoes.')->group(function () {
            Route::get('/', [GeracaoController::class, 'index'])->name('index');
            Route::get('/create', [GeracaoController::class, 'create'])->name('create');
            Route::post('/', [GeracaoController::class, 'store'])->name('store');
            Route::get('/{geracao}', [GeracaoController::class, 'show'])->name('show');
            Route::get('/{geracao}/edit', [GeracaoController::class, 'edit'])->name('edit');
            Route::put('/{geracao}', [GeracaoController::class, 'update'])->name('update');
            Route::get('/{geracao}/destroy', [GeracaoController::class, 'destroy'])->name('destroy');
            Route::delete('/{geracao}', [GeracaoController::class, 'delete'])->name('delete');
            Route::get('/{geracao}/history', [GeracaoController::class, 'history'])->name('history');
            Route::get('/{geracao}/history/{historico}/details', [GeracaoController::class, 'historyDetails'])->name('history.details');
        });

    });

});

require __DIR__.'/auth.php';
