<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\MenuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\URL;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Popular permissões do usuário na sessão ANTES de regenerar
        $this->populateUserPermissions($request);

        $request->session()->regenerate();

        return redirect(URL::signedRoute('dashboard'));
    }

    /**
     * Populate user permissions in session
     */
    protected function populateUserPermissions(Request $request): void
    {
        $user = Auth::user();
        
        // Debug: Verificar se o método está sendo chamado
        \Log::info('populateUserPermissions called for user: ' . $user->id);
        
        if ($user->admin) {
            // Admin tem todas as permissões
            $allPermissions = \App\Models\Permissao::pluck('descricao')->toArray();
            \Log::info('User is admin, all permissions count: ' . count($allPermissions));
        } else {
            // Usuário comum: obter permissões através dos perfis
            \Log::info('User is not admin, checking profiles...');
            
            $perfis = $user->perfis()->get();
            \Log::info('User has ' . $perfis->count() . ' profiles');
            
            $allPermissions = $user->perfis()
                ->with(['perfilPermissoes.permissao'])
                ->get()
                ->flatMap(function ($perfil) {
                    return $perfil->perfilPermissoes->pluck('permissao.descricao');
                })
                ->unique()
                ->toArray();
                
            \Log::info('User permissions count: ' . count($allPermissions));
            \Log::info('User permissions: ' . json_encode($allPermissions));
        }

        // Armazena permissões na sessão
        $request->session()->put('permissoes', $allPermissions);
        
        \Log::info('Permissions stored in session: ' . json_encode($request->session()->get('permissoes')));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
