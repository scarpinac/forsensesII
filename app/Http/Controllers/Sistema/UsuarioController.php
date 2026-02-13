<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserHistorico;
use App\Models\Padrao;
use App\Models\Permissao;
use App\Http\Requests\Sistema\Usuario\StoreRequest;
use App\Http\Requests\Sistema\Usuario\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if (!Auth::user()->canAccess('sistema.usuario.index'), 403, 'Acesso não autorizado');

        $usuarios = User::paginate();
        return view('sistema.usuario.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if (!Auth::user()->canAccess('sistema.usuario.create'), 403, 'Acesso não autorizado');

        $usuario = new User;
        $situacaoPadrao = Padrao::where('descricao', 'Situação')->first();
        $situacoes = $situacaoPadrao ? $situacaoPadrao->tipos()->orderBy('descricao')->get() : collect();

        return view('sistema.usuario.create', compact('usuario', 'situacoes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $validated['admin'] = 0;

        $avatarFile = $request->file('avatar');
        unset($validated['avatar']);

        $usuario = User::create($validated);

        if ($avatarFile && $usuario) {
            $usuario->addMedia($avatarFile)
                ->toMediaCollection('avatar');
        }

        if($usuario) {
            return redirect()->signedRoute('sistema.usuario.index')->with('success', __('labels.user.success.created'));
        }
        return redirect()->signedRoute('sistema.usuario.index')->with('error', __('labels.user.error.not_saved'));

    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        abort_if (!Auth::user()->canAccess('sistema.usuario.show'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;

        return view('sistema.usuario.show', compact('usuario', 'bloquearCampos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        abort_if (!Auth::user()->canAccess('sistema.usuario.edit'), 403, 'Acesso não autorizado');

        $situacaoPadrao = Padrao::where('descricao', 'Situação')->first();
        $situacoes = $situacaoPadrao ? $situacaoPadrao->tipos()->orderBy('descricao')->get() : collect();

        return view('sistema.usuario.edit', compact('usuario', 'situacoes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $usuario)
    {
        $validated = $request->validated();

        $avatarFile = $request->file('avatar');
        unset($validated['avatar']);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $updated = $usuario->update($validated);

        if ($avatarFile && $updated) {
            $usuario->clearMediaCollection('avatar');

            $usuario->addMedia($avatarFile)
                ->toMediaCollection('avatar');
        }

        if($updated) {
            if(Auth::user()->canAccess('sistema.usuario.index')) {
                return redirect()->signedRoute('sistema.usuario.index')->with('success', __('labels.user.success.updated'));
            } else {
                return redirect()->signedRoute('dashboard')->with('success', __('labels.user.success.updated'));
            }
        }
        return redirect()->signedRoute('sistema.usuario.index')->with('error', __('labels.user.error.not_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        abort_if (!Auth::user()->canAccess('sistema.usuario.destroy'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;

        return view('sistema.usuario.destroy', compact('usuario', 'bloquearCampos'));
    }

    /**
     * Delete the specified resource from storage.
     */
    public function delete(User $usuario)
    {
        if($usuario->delete()) {
            return redirect()->signedRoute('sistema.usuario.index')->with('success', __('labels.user.success.deleted'));
        }
        return redirect()->signedRoute('sistema.usuario.index')->with('error', __('labels.user.error.not_deleted'));
    }

    /**
     * Display the history of the specified resource.
     */
    public function history(User $usuario)
    {
        abort_if (!Auth::user()->canAccess('sistema.usuario.history'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;

        return view('sistema.usuario.history', compact('usuario', 'bloquearCampos'));
    }

    /**
     * Show the details of a specific history record.
     */
    public function historyDetails(User $usuario, $historicoId)
    {
        $historico = UserHistorico::findOrFail($historicoId);

        if ($historico->usuario_id !== $usuario->id) {
            abort(403, 'Ação não autorizada.');
        }

        $historico->load(['user', 'tipoAlteracao']);

        $dadosAnteriores = $historico->dados_anteriores;
        $dadosNovos = $historico->dados_novos;

        if ($dadosAnteriores) {
            foreach ($dadosAnteriores as $key => $value) {
                if (in_array($key, ['created_at', 'updated_at', 'deleted_at']) && $value) {
                    try {
                        $dadosAnteriores[$key] = \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s');
                    } catch (\Exception $e) {
                        // Mantém o valor original se não conseguir parsear
                    }
                }
            }
        }

        if ($dadosNovos) {
            foreach ($dadosNovos as $key => $value) {
                if (in_array($key, ['created_at', 'updated_at', 'deleted_at']) && $value) {
                    try {
                        $dadosNovos[$key] = \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s');
                    } catch (\Exception $e) {
                        // Mantém o valor original se não conseguir parsear
                    }
                }
            }
        }

        // Mapeamento de campos para exibição amigável baseado no idioma atual
        $camposTabela = [
            'id' => __('labels.user.history.fields.id'),
            'name' => __('labels.user.history.fields.name'),
            'email' => __('labels.user.history.fields.email'),
            'admin' => __('labels.user.history.fields.admin'),
            'avatar' => __('labels.user.history.fields.avatar'),
            'created_at' => __('labels.user.history.fields.created_at'),
            'updated_at' => __('labels.user.history.fields.updated_at'),
            'deleted_at' => __('labels.user.history.fields.deleted_at'),
        ];

        // Remover campos que não devem ser exibidos
        $camposParaRemover = ['password', 'remember_token', 'media'];

        if ($dadosAnteriores) {
            $dadosAnteriores = array_diff_key($dadosAnteriores, array_flip($camposParaRemover));
        }

        if ($dadosNovos) {
            $dadosNovos = array_diff_key($dadosNovos, array_flip($camposParaRemover));
        }

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }

}
