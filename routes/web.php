<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Sistema\MenuController;
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

            // Rotas que precisam ser definidas antes da rota 'show' para evitar conflitos
            Route::get('/{menu}/edit', [MenuController::class, 'edit'])->name('edit');
            Route::put('/{menu}', [MenuController::class, 'update'])->name('update');
            Route::get('/{menu}/destroy', [MenuController::class, 'destroy'])->name('destroy');
            Route::delete('/{menu}', [MenuController::class, 'delete'])->name('delete');
            Route::get('/{menu}/history', [MenuController::class, 'history'])->name('history');
            Route::get('/{menu}/history/{historico}/details', [MenuController::class, 'historyDetails'])->name('history.details');

            // A rota 'show' por ser apenas GET, deve vir por último para não capturar as outras
            Route::get('/{menu}', [MenuController::class, 'show'])->name('show');
        });

        Route::get('/padrao', function () { return 'Página Padrão'; })->name('padrao.index');
        Route::get('/permissao', function () { return 'Página Permissão'; })->name('permissao.index');
        Route::get('/usuario', function () { return 'Página Usuário'; })->name('usuario.index');

    });
});

require __DIR__.'/auth.php';
