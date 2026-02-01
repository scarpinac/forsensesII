<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Padrao;
use App\Models\PadraoHistorico;
use App\Models\PadraoTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PadraoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if (!Auth::user()->canAccess('sistema.padrao.index'), 403, 'Acesso não autorizado');

        $padroes = Padrao::paginate();
        return view('sistema.padrao.index', compact('padroes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if (!Auth::user()->canAccess('sistema.padrao.create'), 403, 'Acesso não autorizado');

        return view('sistema.padrao.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
        ]);

        $padrao = Padrao::create($request->all());

        if($padrao) {
            return redirect()->signedRoute('sistema.padrao.index')->with('success', __('labels.padrao.success.created'));
        }
        return redirect()->signedRoute('sistema.padrao.index')->with('error', __('labels.padrao.error.not_saved'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Padrao $padrao)
    {
        abort_if (!Auth::user()->canAccess('sistema.padrao.show'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;
        return view('sistema.padrao.show', compact('padrao', 'bloquearCampos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Padrao $padrao)
    {
        abort_if (!Auth::user()->canAccess('sistema.padrao.edit'), 403, 'Acesso não autorizado');

        return view('sistema.padrao.edit', compact('padrao'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Padrao $padrao)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
        ]);

        if($padrao->update($request->all())) {
            return redirect()->signedRoute('sistema.padrao.index')->with('success', __('labels.padrao.success.updated'));
        }
        return redirect()->signedRoute('sistema.padrao.index')->with('error', __('labels.padrao.error.not_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Padrao $padrao)
    {
        abort_if (!Auth::user()->canAccess('sistema.padrao.destroy'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;
        return view('sistema.padrao.destroy', compact('padrao', 'bloquearCampos'));
    }

    /**
     * Delete the specified resource from storage.
     */
    public function delete(Padrao $padrao)
    {
        if($padrao->delete()) {
            return redirect()->signedRoute('sistema.padrao.index')->with('success', __('labels.padrao.success.deleted'));
        }
        return redirect()->signedRoute('sistema.padrao.index')->with('error', __('labels.padrao.error.not_deleted'));
    }

    /**
     * Display the history of the specified resource.
     */
    public function history(Padrao $padrao)
    {
        abort_if (!Auth::user()->canAccess('sistema.padrao.history'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;
        return view('sistema.padrao.history', compact('padrao', 'bloquearCampos'));
    }

    /**
     * Show the details of a specific history record.
     */
    public function historyDetails(Padrao $padrao, $historicoId)
    {
        $historico = PadraoHistorico::findOrFail($historicoId);

        if ($historico->padrao_id !== $padrao->id) {
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
            'id' => __('labels.padrao.history.fields.id'),
            'descricao' => __('labels.padrao.history.fields.descricao'),
            'created_at' => __('labels.padrao.history.fields.created_at'),
            'updated_at' => __('labels.padrao.history.fields.updated_at'),
            'deleted_at' => __('labels.padrao.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
