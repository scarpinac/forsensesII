<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Permissao;
use App\Models\PermissaoHistorico;
use App\Models\Padrao;
use App\Http\Requests\Sistema\Permissao\StoreRequest;
use App\Http\Requests\Sistema\Permissao\UpdateRequest;
use Illuminate\Http\Request;

class PermissaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissoes = Permissao::paginate();
        return view('sistema.permissao.index', compact('permissoes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissao = new Permissao;

        return view('sistema.permissao.create', compact('permissao'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $permissao = Permissao::create($request->validated());

        if($permissao) {
            return redirect()->signedRoute('sistema.permissao.index')->with('success', __('labels.permission.success.created'));
        }
        return redirect()->signedRoute('sistema.permissao.index')->with('error', __('labels.permission.error.not_saved'));

    }

    /**
     * Display the specified resource.
     */
    public function show(Permissao $permissao)
    {
        $bloquearCampos = true;

        return view('sistema.permissao.show', compact('permissao', 'bloquearCampos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permissao $permissao)
    {
        return view('sistema.permissao.edit', compact('permissao'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Permissao $permissao)
    {
        if($permissao->update($request->validated())) {
            return redirect()->signedRoute('sistema.permissao.index')->with('success', __('labels.permission.success.updated'));
        }
        return redirect()->signedRoute('sistema.permissao.index')->with('error', __('labels.permission.error.not_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permissao $permissao)
    {
        $bloquearCampos = true;

        return view('sistema.permissao.destroy', compact('permissao', 'bloquearCampos'));
    }

    /**
     * Delete the specified resource from storage.
     */
    public function delete(Permissao $permissao)
    {
        if($permissao->delete()) {
            return redirect()->signedRoute('sistema.permissao.index')->with('success', __('labels.permission.success.deleted'));
        }
        return redirect()->signedRoute('sistema.permissao.index')->with('error', __('labels.permission.error.not_deleted'));
    }

    /**
     * Display the history of the specified resource.
     */
    public function history(Permissao $permissao)
    {
        $bloquearCampos = true;

        return view('sistema.permissao.history', compact('permissao', 'bloquearCampos'));
    }

    /**
     * Show the details of a specific history record.
     */
    public function historyDetails(Permissao $permissao, $historicoId)
    {
        $historico = PermissaoHistorico::findOrFail($historicoId);

        if ($historico->permissao_id !== $permissao->id) {
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
            'id' => __('labels.permission.history.fields.id'),
            'descricao' => __('labels.permission.history.fields.descricao'),
            'icone' => __('labels.permission.history.fields.icone'),
            'rota' => __('labels.permission.history.fields.rota'),
            'permissaoPai_id' => __('labels.permission.history.fields.permissaoPai_id'),
            'permissao_id' => __('labels.permission.history.fields.permissao_id'),
            'situacao_id' => __('labels.permission.history.fields.situacao_id'),
            'created_at' => __('labels.permission.history.fields.created_at'),
            'updated_at' => __('labels.permission.history.fields.updated_at'),
            'deleted_at' => __('labels.permission.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }

}
