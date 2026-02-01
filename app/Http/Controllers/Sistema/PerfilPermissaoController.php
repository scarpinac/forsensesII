<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Perfil;
use App\Models\PerfilPermissao;
use App\Models\Permissao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class PerfilPermissaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Perfil $perfil)
    {
        if (!Auth::user()->canAccess('sistema.perfil.permissao.index')) {
            abort(403);
        }

        $perfilPermissoes = $perfil->perfilPermissoes()->with('permissao')->get();
        $permissoes = Permissao::all();
        
        return view('sistema.perfil.permissao.index', compact('perfil', 'perfilPermissoes', 'permissoes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Perfil $perfil)
    {
        if (!Auth::user()->canAccess('sistema.perfil.permissao.create')) {
            abort(403);
        }

        $permissoes = Permissao::all();
        
        return view('sistema.perfil.permissao.create', compact('perfil', 'permissoes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Perfil $perfil)
    {
        if (!Auth::user()->canAccess('sistema.perfil.permissao.store')) {
            abort(403);
        }

        $request->validate([
            'permissao_id' => 'required|exists:permissao,id',
        ]);

        // Verificar se já existe a relação
        $exists = $perfil->perfilPermissoes()->where('permissao_id', $request->permissao_id)->exists();
        if ($exists) {
            return redirect()->signedRoute('sistema.perfil.permissao.index', $perfil->id)
                ->with('error', __('labels.perfil.permissao.error.already_exists'));
        }

        $perfilPermissao = PerfilPermissao::create([
            'perfil_id' => $perfil->id,
            'permissao_id' => $request->permissao_id,
        ]);

        if($perfilPermissao) {
            return redirect()->signedRoute('sistema.perfil.permissao.index', $perfil->id)
                ->with('success', __('labels.perfil.permissao.success.created'));
        }
        return redirect()->signedRoute('sistema.perfil.permissao.index', $perfil->id)
            ->with('error', __('labels.perfil.permissao.error.not_saved'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Perfil $perfil, PerfilPermissao $perfilPermissao)
    {
        if (!Auth::user()->canAccess('sistema.perfil.permissao.show')) {
            abort(403);
        }

        return view('sistema.perfil.permissao.show', compact('perfil', 'perfilPermissao'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perfil $perfil, PerfilPermissao $perfilPermissao)
    {
        if (!Auth::user()->canAccess('sistema.perfil.permissao.edit')) {
            abort(403);
        }

        $permissoes = Permissao::all();
        
        return view('sistema.perfil.permissao.edit', compact('perfil', 'perfilPermissao', 'permissoes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perfil $perfil, PerfilPermissao $perfilPermissao)
    {
        if (!Auth::user()->canAccess('sistema.perfil.permissao.update')) {
            abort(403);
        }

        $request->validate([
            'permissao_id' => 'required|exists:permissao,id',
        ]);

        // Verificar se já existe a relação (exceto o atual)
        $exists = $perfil->perfilPermissoes()
            ->where('permissao_id', $request->permissao_id)
            ->where('id', '!=', $perfilPermissao->id)
            ->exists();
            
        if ($exists) {
            return redirect()->signedRoute('sistema.perfil.permissao.index', $perfil->id)
                ->with('error', __('labels.perfil.permissao.error.already_exists'));
        }

        $updated = $perfilPermissao->update($request->all());

        if($updated) {
            return redirect()->signedRoute('sistema.perfil.permissao.index', $perfil->id)
                ->with('success', __('labels.perfil.permissao.success.updated'));
        }
        return redirect()->signedRoute('sistema.perfil.permissao.index', $perfil->id)
            ->with('error', __('labels.perfil.permissao.error.not_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Perfil $perfil, PerfilPermissao $perfilPermissao)
    {
        if (!Auth::user()->canAccess('sistema.perfil.permissao.delete')) {
            abort(403);
        }

        if($perfilPermissao->delete()) {
            return redirect()->signedRoute('sistema.perfil.permissao.index', $perfil->id)
                ->with('success', __('labels.perfil.permissao.success.deleted'));
        }
        return redirect()->signedRoute('sistema.perfil.permissao.index', $perfil->id)
            ->with('error', __('labels.perfil.permissao.error.not_deleted'));
    }

    /**
     * Display the history of the specified resource.
     */
    public function history(Perfil $perfil, PerfilPermissao $perfilPermissao)
    {
        if (!Auth::user()->canAccess('sistema.perfil.permissao.history')) {
            abort(403);
        }

        $perfilPermissao->load(['historicos.user', 'historicos.tipoAlteracao']);
        
        return view('sistema.perfil.permissao.history', compact('perfil', 'perfilPermissao'));
    }

    /**
     * Get the details of a specific history record.
     */
    public function historyDetails(Perfil $perfil, PerfilPermissao $perfilPermissao, $historico)
    {
        if (!Auth::user()->canAccess('sistema.perfil.permissao.history')) {
            abort(403);
        }

        $historico = $perfilPermissao->historicos()->with(['user', 'tipoAlteracao'])->findOrFail($historico);

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
            'id' => __('labels.perfil.permissao.history.fields.id'),
            'perfil_id' => __('labels.perfil.permissao.history.fields.perfil_id'),
            'permissao_id' => __('labels.perfil.permissao.history.fields.permissao_id'),
            'created_at' => __('labels.perfil.permissao.history.fields.created_at'),
            'updated_at' => __('labels.perfil.permissao.history.fields.updated_at'),
            'deleted_at' => __('labels.perfil.permissao.history.fields.deleted_at'),
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
