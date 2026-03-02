<?php

use App\Http\Controllers\Cadastro\AcabamentoController;
use App\Http\Controllers\Cadastro\ClienteController;
use App\Http\Controllers\Cadastro\ComissaoController;
use App\Http\Controllers\Cadastro\CondicaoPagamentoController;
use App\Http\Controllers\Cadastro\CorController;
use App\Http\Controllers\Cadastro\FamiliaController;
use App\Http\Controllers\Cadastro\OrigemProdutoController;
use App\Http\Controllers\Cadastro\ProdutoController;
use App\Http\Controllers\Cadastro\RegraDescontoController;
use App\Http\Controllers\Cadastro\RevendaController;
use App\Http\Controllers\Cadastro\TabelaPrecoController;
use App\Http\Controllers\Cadastro\TelaController;
use App\Http\Controllers\Cadastro\TransportadoraController;
use App\Http\Controllers\DashboardController;
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
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    if( Auth::check() )
        return redirect()->signedRoute('dashboard');

    return redirect()->route('login');
})->name('root');

Route::get('/dash', function () {
    if( Auth::check() )
        return redirect()->signedRoute('dashboard');

    return view('auth.login');
})->middleware('auth')->name('dash');

//# ROTAS DO GERADOR DE CADASTROS
//Route::prefix('gerador')->name('gerador.')->middleware(['throttle:gerador'])->group(function () {
//    Route::get('/', [GeradorCadastrosController::class, 'index'])->name('index');
//    Route::post('/generate', [GeradorCadastrosController::class, 'generate'])->name('generate');
//    Route::get('/modulos', [GeradorCadastrosController::class, 'getModulosDisponiveis'])->name('modulos');
//    Route::get('/tabelas', [GeradorCadastrosController::class, 'getTabelasDisponiveis'])->name('tabelas');
//});

Route::post('/language', [LanguageController::class, 'switch'])->middleware('throttle:10,1')->name('language.switch');

Route::middleware(['auth', 'signed'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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

            Route::get('/voltar', [UsuarioController::class, 'voltarUsuario'])->name('voltar');

            Route::get('/{usuario}/edit', [UsuarioController::class, 'edit'])->name('edit');
            Route::get('/{usuario}', [UsuarioController::class, 'show'])->name('show');
            Route::put('/{usuario}', [UsuarioController::class, 'update'])->name('update');
            Route::get('/{usuario}/destroy', [UsuarioController::class, 'destroy'])->name('destroy');
            Route::delete('/{usuario}', [UsuarioController::class, 'delete'])->name('delete');
            Route::get('/{usuario}/history', [UsuarioController::class, 'history'])->name('history');
            Route::get('/{usuario}/history/{historico}/details', [UsuarioController::class, 'historyDetails'])->name('history.details');

            Route::get('/{usuario}/login', [UsuarioController::class, 'logarComo'])->name('login')->middleware('login.as');

        });


        # ROTAS DA API
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

            Route::post('/marcar-como-lida', [NotificacaoController::class, 'marcarComoLida'])->name('marcar-como-lida');

            Route::get('/{notificacao}', [NotificacaoController::class, 'show'])->name('show');
            Route::get('/{notificacao}/edit', [NotificacaoController::class, 'edit'])->name('edit');
            Route::put('/{notificacao}', [NotificacaoController::class, 'update'])->name('update');
            Route::get('/{notificacao}/destroy', [NotificacaoController::class, 'destroy'])->name('destroy');
            Route::delete('/{notificacao}', [NotificacaoController::class, 'delete'])->name('delete');

            Route::get('/{notificacao}/history', [NotificacaoController::class, 'history'])->name('history');
            Route::get('/{notificacao}/history/{historico}/details', [NotificacaoController::class, 'historyDetails'])->name('history.details');

             Route::get('/{notificacao}/detalhes', [NotificacaoController::class, 'getNotificationDetails'])->name('detalhes');

        });


        # ROTAS DE GERACOES
        Route::prefix('gerador')->name('gerador.')->group(function () {
            Route::get('/', [GeradorCadastrosController::class, 'index'])->name('index');
            Route::get('/create', [GeradorCadastrosController::class, 'create'])->name('create');
            Route::post('/', [GeradorCadastrosController::class, 'store'])->name('store');
            Route::get('/{gerador}', [GeradorCadastrosController::class, 'show'])->name('show');
            Route::get('/{gerador}/edit', [GeradorCadastrosController::class, 'edit'])->name('edit');
            Route::put('/{gerador}', [GeradorCadastrosController::class, 'update'])->name('update');
            Route::get('/{gerador}/destroy', [GeradorCadastrosController::class, 'destroy'])->name('destroy');
//            Route::delete('/{gerador}', [GeradorCadastrosController::class, 'delete'])->name('delete');
//            Route::get('/{gerador}/history', [GeradorCadastrosController::class, 'history'])->name('history');
//            Route::get('/{gerador}/history/{historico}/details', [GeradorCadastrosController::class, 'historyDetails'])->name('history.details');
        });

    });

    Route::prefix('cadastro')->name('cadastro.')->group(function () {
        # ROTAS DE COMISSÃO
        Route::prefix('comissao')->name('comissao.')->group(function () {
            Route::get('/', [ComissaoController::class, 'index'])->name('index');
            Route::get('/create', [ComissaoController::class, 'create'])->name('create');
            Route::post('/', [ComissaoController::class, 'store'])->name('store');

            Route::get('/{comissao}/edit', [ComissaoController::class, 'edit'])->name('edit');
            Route::put('/{comissao}', [ComissaoController::class, 'update'])->name('update');
            Route::get('/{comissao}/destroy', [ComissaoController::class, 'destroy'])->name('destroy');
            Route::delete('/{comissao}', [ComissaoController::class, 'delete'])->name('delete');
            Route::get('/{comissao}/history', [ComissaoController::class, 'history'])->name('history');
            Route::get('/{comissao}/history/{historico}/details', [ComissaoController::class, 'historyDetails'])->name('history.details');

            Route::get('/{comissao}', [ComissaoController::class, 'show'])->name('show');
        });

        # ROTAS DE CLIENTE
        Route::prefix('cliente')->name('cliente.')->group(function () {
            Route::get('/', [ClienteController::class, 'index'])->name('index');
            Route::get('/create', [ClienteController::class, 'create'])->name('create');
            Route::post('/', [ClienteController::class, 'store'])->name('store');

            Route::get('/{cliente}/edit', [ClienteController::class, 'edit'])->name('edit');
            Route::put('/{cliente}', [ClienteController::class, 'update'])->name('update');
            Route::get('/{cliente}/destroy', [ClienteController::class, 'destroy'])->name('destroy');
            Route::delete('/{cliente}', [ClienteController::class, 'delete'])->name('delete');
            Route::get('/{cliente}/history', [ClienteController::class, 'history'])->name('history');
            Route::get('/{cliente}/history/{historico}/details', [ClienteController::class, 'historyDetails'])->name('history.details');

            Route::get('/{cliente}', [ClienteController::class, 'show'])->name('show');
        });

        # ROTAS DE TRANSPORTADORA
        Route::prefix('transportadora')->name('transportadora.')->group(function () {
            Route::get('/', [TransportadoraController::class, 'index'])->name('index');
            Route::get('/create', [TransportadoraController::class, 'create'])->name('create');
            Route::post('/', [TransportadoraController::class, 'store'])->name('store');

            Route::get('/{transportadora}/edit', [TransportadoraController::class, 'edit'])->name('edit');
            Route::put('/{transportadora}', [TransportadoraController::class, 'update'])->name('update');
            Route::get('/{transportadora}/destroy', [TransportadoraController::class, 'destroy'])->name('destroy');
            Route::delete('/{transportadora}', [TransportadoraController::class, 'delete'])->name('delete');
            Route::get('/{transportadora}/history', [TransportadoraController::class, 'history'])->name('history');
            Route::get('/{transportadora}/history/{historico}/details', [TransportadoraController::class, 'historyDetails'])->name('history.details');

            Route::get('/{transportadora}', [TransportadoraController::class, 'show'])->name('show');
        });

        # ROTAS DE REVENDA
        Route::prefix('revenda')->name('revenda.')->group(function () {
            Route::get('/', [RevendaController::class, 'index'])->name('index');
            Route::get('/create', [RevendaController::class, 'create'])->name('create');
            Route::post('/', [RevendaController::class, 'store'])->name('store');

            Route::get('/{revenda}/edit', [RevendaController::class, 'edit'])->name('edit');
            Route::put('/{revenda}', [RevendaController::class, 'update'])->name('update');
            Route::get('/{revenda}/destroy', [RevendaController::class, 'destroy'])->name('destroy');
            Route::delete('/{revenda}', [RevendaController::class, 'delete'])->name('delete');
            Route::get('/{revenda}/history', [RevendaController::class, 'history'])->name('history');
            Route::get('/{revenda}/history/{historico}/details', [RevendaController::class, 'historyDetails'])->name('history.details');

            Route::get('/{revenda}', [RevendaController::class, 'show'])->name('show');
        });

        # ROTAS DE COR
        Route::prefix('cor')->name('cor.')->group(function () {
            Route::get('/', [CorController::class, 'index'])->name('index');
            Route::get('/create', [CorController::class, 'create'])->name('create');
            Route::post('/', [CorController::class, 'store'])->name('store');

            Route::get('/{cor}/edit', [CorController::class, 'edit'])->name('edit');
            Route::put('/{cor}', [CorController::class, 'update'])->name('update');
            Route::get('/{cor}/destroy', [CorController::class, 'destroy'])->name('destroy');
            Route::delete('/{cor}', [CorController::class, 'delete'])->name('delete');
            Route::get('/{cor}/history', [CorController::class, 'history'])->name('history');
            Route::get('/{cor}/history/{historico}/details', [CorController::class, 'historyDetails'])->name('history.details');

            Route::get('/{cor}', [CorController::class, 'show'])->name('show');
        });

        # ROTAS DE CONDIÇÃO DE PAGAMENTO
        Route::prefix('condicao_pagamento')->name('condicao_pagamento.')->group(function () {
            Route::get('/', [CondicaoPagamentoController::class, 'index'])->name('index');
            Route::get('/create', [CondicaoPagamentoController::class, 'create'])->name('create');
            Route::post('/', [CondicaoPagamentoController::class, 'store'])->name('store');

            Route::get('/{condicao_pagamento}/edit', [CondicaoPagamentoController::class, 'edit'])->name('edit');
            Route::put('/{condicao_pagamento}', [CondicaoPagamentoController::class, 'update'])->name('update');
            Route::get('/{condicao_pagamento}/destroy', [CondicaoPagamentoController::class, 'destroy'])->name('destroy');
            Route::delete('/{condicao_pagamento}', [CondicaoPagamentoController::class, 'delete'])->name('delete');
            Route::get('/{condicao_pagamento}/history', [CondicaoPagamentoController::class, 'history'])->name('history');
            Route::get('/{condicao_pagamento}/history/{historico}/details', [CondicaoPagamentoController::class, 'historyDetails'])->name('history.details');

            Route::get('/{condicao_pagamento}', [CondicaoPagamentoController::class, 'show'])->name('show');
        });

        # ROTAS DE ACABAMENTO
        Route::prefix('acabamento')->name('acabamento.')->group(function () {
            Route::get('/', [AcabamentoController::class, 'index'])->name('index');
            Route::get('/create', [AcabamentoController::class, 'create'])->name('create');
            Route::post('/', [AcabamentoController::class, 'store'])->name('store');

            Route::get('/{acabamento}/edit', [AcabamentoController::class, 'edit'])->name('edit');
            Route::put('/{acabamento}', [AcabamentoController::class, 'update'])->name('update');
            Route::get('/{acabamento}/destroy', [AcabamentoController::class, 'destroy'])->name('destroy');
            Route::delete('/{acabamento}', [AcabamentoController::class, 'delete'])->name('delete');
            Route::get('/{acabamento}/history', [AcabamentoController::class, 'history'])->name('history');
            Route::get('/{acabamento}/history/{historico}/details', [AcabamentoController::class, 'historyDetails'])->name('history.details');
            Route::get('/{acabamento}', [AcabamentoController::class, 'show'])->name('show');
        });

        # ROTAS DE ORIGEM DO PRODUTO
        Route::prefix('origem_produto')->name('origem_produto.')->group(function () {
            Route::get('/', [OrigemProdutoController::class, 'index'])->name('index');
            Route::get('/create', [OrigemProdutoController::class, 'create'])->name('create');
            Route::post('/', [OrigemProdutoController::class, 'store'])->name('store');

            Route::get('/{origem_produto}/edit', [OrigemProdutoController::class, 'edit'])->name('edit');
            Route::put('/{origem_produto}', [OrigemProdutoController::class, 'update'])->name('update');
            Route::get('/{origem_produto}/destroy', [OrigemProdutoController::class, 'destroy'])->name('destroy');
            Route::delete('/{origem_produto}', [OrigemProdutoController::class, 'delete'])->name('delete');
            Route::get('/{origem_produto}/history', [OrigemProdutoController::class, 'history'])->name('history');
            Route::get('/{origem_produto}/history/{historico}/details', [OrigemProdutoController::class, 'historyDetails'])->name('history.details');

            Route::get('/{origem_produto}', [OrigemProdutoController::class, 'show'])->name('show');
        });

        # ROTAS DE FAMILIA
        Route::prefix('familia')->name('familia.')->group(function () {
            Route::get('/', [FamiliaController::class, 'index'])->name('index');
            Route::get('/create', [FamiliaController::class, 'create'])->name('create');
            Route::post('/', [FamiliaController::class, 'store'])->name('store');

            Route::get('/{familia}/edit', [FamiliaController::class, 'edit'])->name('edit');
            Route::put('/{familia}', [FamiliaController::class, 'update'])->name('update');
            Route::get('/{familia}/destroy', [FamiliaController::class, 'destroy'])->name('destroy');
            Route::delete('/{familia}', [FamiliaController::class, 'delete'])->name('delete');
            Route::get('/{familia}/history', [FamiliaController::class, 'history'])->name('history');
            Route::get('/{familia}/history/{historico}/details', [FamiliaController::class, 'historyDetails'])->name('history.details');

            Route::get('/{familia}', [FamiliaController::class, 'show'])->name('show');
        });

        # ROTAS DE TELA
        Route::prefix('tela')->name('tela.')->group(function () {
            Route::get('/', [TelaController::class, 'index'])->name('index');
            Route::get('/create', [TelaController::class, 'create'])->name('create');
            Route::post('/', [TelaController::class, 'store'])->name('store');

            Route::get('/{tela}/edit', [TelaController::class, 'edit'])->name('edit');
            Route::put('/{tela}', [TelaController::class, 'update'])->name('update');
            Route::get('/{tela}/destroy', [TelaController::class, 'destroy'])->name('destroy');
            Route::delete('/{tela}', [TelaController::class, 'delete'])->name('delete');
            Route::get('/{tela}/history', [TelaController::class, 'history'])->name('history');
            Route::get('/{tela}/history/{historico}/details', [TelaController::class, 'historyDetails'])->name('history.details');

            Route::get('/{tela}', [TelaController::class, 'show'])->name('show');
        });

        # ROTAS DE PRODUTO
        Route::prefix('produto')->name('produto.')->group(function () {
            Route::get('/', [ProdutoController::class, 'index'])->name('index');
            Route::get('/create', [ProdutoController::class, 'create'])->name('create');
            Route::post('/', [ProdutoController::class, 'store'])->name('store');

            Route::get('/{produto}/edit', [ProdutoController::class, 'edit'])->name('edit');
            Route::put('/{produto}', [ProdutoController::class, 'update'])->name('update');
            Route::get('/{produto}/destroy', [ProdutoController::class, 'destroy'])->name('destroy');
            Route::delete('/{produto}', [ProdutoController::class, 'delete'])->name('delete');
            Route::get('/{produto}/history', [ProdutoController::class, 'history'])->name('history');
            Route::get('/{produto}/history/{historico}/details', [ProdutoController::class, 'historyDetails'])->name('history.details');

            Route::get('/{produto}', [ProdutoController::class, 'show'])->name('show');
        });

        # ROTAS DE REGRAS DE DESCONTO
        Route::prefix('regra_desconto')->name('regra_desconto.')->group(function () {
            Route::get('/', [RegraDescontoController::class, 'index'])->name('index');
            Route::get('/create', [RegraDescontoController::class, 'create'])->name('create');
            Route::post('/', [RegraDescontoController::class, 'store'])->name('store');

            Route::get('/{regra_desconto}/edit', [RegraDescontoController::class, 'edit'])->name('edit');
            Route::put('/{regra_desconto}', [RegraDescontoController::class, 'update'])->name('update');
            Route::get('/{regra_desconto}/destroy', [RegraDescontoController::class, 'destroy'])->name('destroy');
            Route::delete('/{regra_desconto}', [RegraDescontoController::class, 'delete'])->name('delete');
            Route::get('/{regra_desconto}/history', [RegraDescontoController::class, 'history'])->name('history');
            Route::get('/{regra_desconto}/history/{historico}/details', [RegraDescontoController::class, 'historyDetails'])->name('history.details');

            Route::get('/{regra_desconto}', [RegraDescontoController::class, 'show'])->name('show');
        });

        # ROTAS DE TABELA DE PREÇO
        Route::prefix('tabela_preco')->name('tabela_preco.')->group(function () {
            Route::get('/', [TabelaPrecoController::class, 'index'])->name('index');
            Route::get('/create', [TabelaPrecoController::class, 'create'])->name('create');
            Route::post('/', [TabelaPrecoController::class, 'store'])->name('store');

            Route::get('/{tabela_preco}/edit', [TabelaPrecoController::class, 'edit'])->name('edit');
            Route::put('/{tabela_preco}', [TabelaPrecoController::class, 'update'])->name('update');
            Route::get('/{tabela_preco}/destroy', [TabelaPrecoController::class, 'destroy'])->name('destroy');
            Route::delete('/{tabela_preco}', [TabelaPrecoController::class, 'delete'])->name('delete');
            Route::get('/{tabela_preco}/history', [TabelaPrecoController::class, 'history'])->name('history');
            Route::get('/{tabela_preco}/history/{historico}/details', [TabelaPrecoController::class, 'historyDetails'])->name('history.details');

            Route::get('/{tabela_preco}', [TabelaPrecoController::class, 'show'])->name('show');
        });

    });
});

require __DIR__.'/auth.php';
