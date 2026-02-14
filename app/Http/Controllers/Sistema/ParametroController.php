<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Parametro;
use App\Models\ParametroHistorico;
use App\Models\Padrao;
use App\Models\Permissao;
use App\Http\Requests\Sistema\Parametro\StoreRequest;
use App\Http\Requests\Sistema\Parametro\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParametroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if (!Auth::user()->canAccess('sistema.parametro.index'), 403, 'Acesso não autorizado');

        $parametros = Parametro::paginate();
        return view('sistema.parametro.index', compact('parametros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if (!Auth::user()->canAccess('sistema.parametro.create'), 403, 'Acesso não autorizado');

        $parametro = new Parametro;
        $tiposPadrao = Padrao::where('descricao', 'Tipos de valores para os parâmetros do sistema')->first();
        $tipos = $tiposPadrao ? $tiposPadrao->tipos()->orderBy('descricao')->get() : collect();

        return view('sistema.parametro.create', compact('parametro', 'tipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $parametro = Parametro::create($request->validated());

        if($parametro) {
            return redirect()->signedRoute('sistema.parametro.index')->with('success', __('labels.parametro.success.created'));
        }
        return redirect()->signedRoute('sistema.parametro.index')->with('error', __('labels.parametro.error.not_saved'));

    }

    /**
     * Display the specified resource.
     */
    public function show(Parametro $parametro)
    {
        abort_if (!Auth::user()->canAccess('sistema.parametro.show'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;

        return view('sistema.parametro.show', compact('parametro', 'bloquearCampos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parametro $parametro)
    {
        abort_if (!Auth::user()->canAccess('sistema.parametro.edit'), 403, 'Acesso não autorizado');

        $tiposPadrao = Padrao::where('descricao', 'Tipos de valores para os parâmetros do sistema')->first();
        $tipos = $tiposPadrao ? $tiposPadrao->tipos()->orderBy('descricao')->get() : collect();

        return view('sistema.parametro.edit', compact('parametro', 'tipos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Parametro $parametro)
    {
        if($parametro->update($request->validated())) {
            return redirect()->signedRoute('sistema.parametro.index')->with('success', __('labels.parametro.success.updated'));
        }
        return redirect()->signedRoute('sistema.parametro.index')->with('error', __('labels.parametro.error.not_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parametro $parametro)
    {
        abort_if (!Auth::user()->canAccess('sistema.parametro.destroy'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;

        return view('sistema.parametro.destroy', compact('parametro', 'bloquearCampos'));
    }

    /**
     * Delete the specified resource from storage.
     */
    public function delete(Parametro $parametro)
    {
        if($parametro->delete()) {
            return redirect()->signedRoute('sistema.parametro.index')->with('success', __('labels.parametro.success.deleted'));
        }
        return redirect()->signedRoute('sistema.parametro.index')->with('error', __('labels.parametro.error.not_deleted'));
    }

    /**
     * Display the history of the specified resource.
     */
    public function history(Parametro $parametro)
    {
        abort_if (!Auth::user()->canAccess('sistema.parametro.history'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;

        return view('sistema.parametro.history', compact('parametro', 'bloquearCampos'));
    }

    /**
     * Show the details of a specific history record.
     */
    public function historyDetails(Parametro $parametro, $historicoId)
    {
        if (!Auth::user()->canAccess('sistema.parametro.history')) {
            abort(403);
        }

        $historico = ParametroHistorico::findOrFail($historicoId);

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
            'id' => __('labels.parametro.history.fields.id'),
            'descricao' => __('labels.parametro.history.fields.descricao'),
            'icone' => __('labels.parametro.history.fields.icone'),
            'rota' => __('labels.parametro.history.fields.rota'),
            'parametroPai_id' => __('labels.parametro.history.fields.parametroPai_id'),
            'permissao_id' => __('labels.parametro.history.fields.permissao_id'),
            'situacao_id' => __('labels.parametro.history.fields.situacao_id'),
            'created_at' => __('labels.parametro.history.fields.created_at'),
            'updated_at' => __('labels.parametro.history.fields.updated_at'),
            'deleted_at' => __('labels.parametro.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
