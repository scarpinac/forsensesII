<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Padrao;
use App\Models\PadraoTipo;
use App\Models\PadraoTipoHistorico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PadraoTipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Padrao $padrao)
    {
        abort_if (!Auth::user()->canAccess('sistema.padrao.padraoTipo.index'), 403, 'Acesso não autorizado');

        $padraoTipos = $padrao->tipos()->paginate();
        return view('sistema.padrao.padraoTipo.index', compact('padrao', 'padraoTipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Padrao $padrao)
    {
        abort_if (!Auth::user()->canAccess('sistema.padrao.padraoTipo.create'), 403, 'Acesso não autorizado');

        return view('sistema.padrao.padraoTipo.create', compact('padrao'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Padrao $padrao)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
        ]);

        $padraoTipo = $padrao->tipos()->create($request->all());

        if($padraoTipo) {
            return redirect()->signedRoute('sistema.padrao.padraoTipo.index', $padrao)->with('success', __('labels.padraoTipo.success.created'));
        }
        return redirect()->signedRoute('sistema.padrao.padraoTipo.index', $padrao)->with('error', __('labels.padraoTipo.error.not_saved'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Padrao $padrao, PadraoTipo $padraoTipo)
    {
        abort_if (!Auth::user()->canAccess('sistema.padrao.padraoTipo.show'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;
        return view('sistema.padrao.padraoTipo.show', compact('padrao', 'padraoTipo', 'bloquearCampos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Padrao $padrao, PadraoTipo $padraoTipo)
    {
        abort_if (!Auth::user()->canAccess('sistema.padrao.padraoTipo.edit'), 403, 'Acesso não autorizado');

        return view('sistema.padrao.padraoTipo.edit', compact('padrao', 'padraoTipo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Padrao $padrao, PadraoTipo $padraoTipo)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
        ]);

        if($padraoTipo->update($request->all())) {
            return redirect()->signedRoute('sistema.padrao.padraoTipo.index', $padrao)->with('success', __('labels.padraoTipo.success.updated'));
        }
        return redirect()->signedRoute('sistema.padrao.padraoTipo.index', $padrao)->with('error', __('labels.padraoTipo.error.not_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Padrao $padrao, PadraoTipo $padraoTipo)
    {
        abort_if (!Auth::user()->canAccess('sistema.padrao.padraoTipo.destroy'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;
        return view('sistema.padrao.padraoTipo.destroy', compact('padrao', 'padraoTipo', 'bloquearCampos'));
    }

    /**
     * Delete the specified resource from storage.
     */
    public function delete(Padrao $padrao, PadraoTipo $padraoTipo)
    {
        if($padraoTipo->delete()) {
            return redirect()->signedRoute('sistema.padrao.padraoTipo.index', $padrao)->with('success', __('labels.padraoTipo.success.deleted'));
        }
        return redirect()->signedRoute('sistema.padrao.padraoTipo.index', $padrao)->with('error', __('labels.padraoTipo.error.not_deleted'));
    }

    /**
     * Display the history of the specified resource.
     */
    public function history(Padrao $padrao, PadraoTipo $padraoTipo)
    {
        abort_if (!Auth::user()->canAccess('sistema.padrao.padraoTipo.history'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;
        return view('sistema.padrao.padraoTipo.history', compact('padrao', 'padraoTipo', 'bloquearCampos'));
    }

    /**
     * Show the details of a specific history record.
     */
    public function historyDetails(Padrao $padrao, PadraoTipo $padraoTipo, $historicoId)
    {
        $historico = PadraoTipoHistorico::findOrFail($historicoId);

        if ($historico->padrao_tipo_id !== $padraoTipo->id) {
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
            'id' => __('labels.padraoTipo.history.fields.id'),
            'descricao' => __('labels.padraoTipo.history.fields.descricao'),
            'padrao_id' => __('labels.padraoTipo.history.fields.padrao_id'),
            'created_at' => __('labels.padraoTipo.history.fields.created_at'),
            'updated_at' => __('labels.padraoTipo.history.fields.updated_at'),
            'deleted_at' => __('labels.padraoTipo.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
