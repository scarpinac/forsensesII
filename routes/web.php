<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Sistema\MenuController;
use App\Http\Controllers\Sistema\PermissaoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::post('/language', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'signed'])->name('dashboard');

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

        Route::get('/padrao', function () { return 'Página Padrão'; })->name('padrao.index');
        Route::get('/usuario', function () { return 'Página Usuário'; })->name('usuario.index');

    });
});

require __DIR__.'/auth.php';
