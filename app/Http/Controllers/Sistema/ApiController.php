<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Api;
use App\Models\ApiHistorico;
use App\Models\Padrao;
use App\Http\Requests\Sistema\Api\StoreRequest;
use App\Http\Requests\Sistema\Api\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if (!Auth::user()->canAccess('sistema.api.index'), 403, 'Acesso não autorizado');

        $apis = Api::paginate();
        return view('sistema.api.index', compact('apis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if (!Auth::user()->canAccess('sistema.api.create'), 403, 'Acesso não autorizado');

        $api = new Api;
        $situacaoPadrao = Padrao::where('descricao', 'Situação')->first();
        $situacoes = $situacaoPadrao ? $situacaoPadrao->tipos()->orderBy('descricao')->get() : collect();
        $apiPadrao = Padrao::where('descricao', 'Api')->first();
        $apiTypes = $apiPadrao ? $apiPadrao->tipos()->orderBy('descricao')->get() : collect();

        return view('sistema.api.create', compact('api', 'situacoes', 'apiTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $api = Api::create($request->validated());

        if($api) {
            return redirect()->signedRoute('sistema.api.index')->with('success', __('labels.api.success.created'));
        }
        return redirect()->signedRoute('sistema.api.index')->with('error', __('labels.api.error.not_saved'));

    }

    /**
     * Display the specified resource.
     */
    public function show(Api $api)
    {
        abort_if (!Auth::user()->canAccess('sistema.api.show'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;

        return view('sistema.api.show', compact('api', 'bloquearCampos' ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Api $api)
    {
        abort_if (!Auth::user()->canAccess('sistema.api.edit'), 403, 'Acesso não autorizado');

        $situacaoPadrao = Padrao::where('descricao', 'Situação')->first();
        $situacoes = $situacaoPadrao ? $situacaoPadrao->tipos()->orderBy('descricao')->get() : collect();
        $apiPadrao = Padrao::where('descricao', 'Api')->first();
        $apiTypes = $apiPadrao ? $apiPadrao->tipos()->orderBy('descricao')->get() : collect();

        return view('sistema.api.edit', compact('api', 'situacoes', 'apiTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Api $api)
    {
        if($api->update($request->validated())) {
            return redirect()->signedRoute('sistema.api.index')->with('success', __('labels.api.success.updated'));
        }
        return redirect()->signedRoute('sistema.api.index')->with('error', __('labels.api.error.not_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Api $api)
    {
        abort_if (!Auth::user()->canAccess('sistema.api.destroy'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;

        return view('sistema.api.destroy', compact('api', 'bloquearCampos'));
    }

    /**
     * Delete the specified resource from storage.
     */
    public function delete(Api $api)
    {
        if($api->delete()) {
            return redirect()->signedRoute('sistema.api.index')->with('success', __('labels.api.success.deleted'));
        }
        return redirect()->signedRoute('sistema.api.index')->with('error', __('labels.api.error.not_deleted'));
    }

    /**
     * Display the history of the specified resource.
     */
    public function history(Api $api)
    {
        abort_if (!Auth::user()->canAccess('sistema.api.history'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;

        return view('sistema.api.history', compact('api', 'bloquearCampos'));
    }

    /**
     * Show the details of a specific history record.
     */
    public function historyDetails(Api $api, $historicoId)
    {
        if (!Auth::user()->canAccess('sistema.api.history')) {
            abort(403);
        }

        $historico = ApiHistorico::findOrFail($historicoId);

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
            'id' => __('labels.api.history.fields.id'),
            'descricao' => __('labels.api.history.fields.descricao'),
            'icone' => __('labels.api.history.fields.icone'),
            'rota' => __('labels.api.history.fields.rota'),
            'menuPai_id' => __('labels.api.history.fields.menuPai_id'),
            'permissao_id' => __('labels.api.history.fields.permissao_id'),
            'situacao_id' => __('labels.api.history.fields.situacao_id'),
            'created_at' => __('labels.api.history.fields.created_at'),
            'updated_at' => __('labels.api.history.fields.updated_at'),
            'deleted_at' => __('labels.api.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
