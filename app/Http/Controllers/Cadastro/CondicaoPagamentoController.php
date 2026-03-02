<?php

namespace App\Http\Controllers\Cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cadastro\CondicaoPagamento\StoreRequest;
use App\Http\Requests\Cadastro\CondicaoPagamento\UpdateRequest;
use App\Models\Cadastro\CondicaoPagamento;
use App\Models\Cadastro\CondicaoPagamentoHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CondicaoPagamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if (!Auth::user()->canAccess('cadastro.condicao_pagamento.index'), 403, 'Acesso não autorizado');

        $condicoesPagamento = CondicaoPagamento::with(['revenda', 'situacao'])->paginate();

        return view('cadastro.condicao_pagamento.index', compact('condicoesPagamento'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if (!Auth::user()->canAccess('cadastro.condicao_pagamento.create'), 403, 'Acesso não autorizado');

        $revendas = \App\Models\Cadastro\Revenda::all();
        $situacoes = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Situações');
        })->get();

        return view('cadastro.condicao_pagamento.create', compact('revendas', 'situacoes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        abort_if (!Auth::user()->canAccess('cadastro.condicao_pagamento.store'), 403, 'Acesso não autorizado');

        $condicaoPagamento = CondicaoPagamento::create($request->validated());

        if($condicaoPagamento) {
            return redirect()->signedRoute('cadastro.condicao_pagamento.index')->with('success', __('labels.payment_condition.success.created'));
        }
        return redirect()->signedRoute('cadastro.condicao_pagamento.index')->with('error', __('labels.payment_condition.error.not_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(CondicaoPagamento $condicaoPagamento)
    {
        abort_if (!Auth::user()->canAccess('cadastro.condicao_pagamento.show'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $condicaoPagamento->load(['revenda', 'situacao']);
        return view('cadastro.condicao_pagamento.show', compact('condicaoPagamento', 'bloquearCampos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CondicaoPagamento $condicaoPagamento)
    {
        abort_if (!Auth::user()->canAccess('cadastro.condicao_pagamento.edit'), 403, 'Acesso não autorizado');

        $condicaoPagamento->load(['revenda', 'situacao']);
        $revendas = \App\Models\Cadastro\Revenda::all();
        $situacoes = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Situações');
        })->get();

        return view('cadastro.condicao_pagamento.edit', compact('condicaoPagamento', 'revendas', 'situacoes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, CondicaoPagamento $condicaoPagamento)
    {
        abort_if (!Auth::user()->canAccess('cadastro.condicao_pagamento.update'), 403, 'Acesso não autorizado');

        if($condicaoPagamento->update($request->validated())){
            return redirect()->signedRoute('cadastro.condicao_pagamento.index')->with('success', __('labels.payment_condition.success.updated'));
        }
        return redirect()->signedRoute('cadastro.condicao_pagamento.index')->with('success', __('labels.payment_condition.error.not_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CondicaoPagamento $condicaoPagamento)
    {
        abort_if (!Auth::user()->canAccess('cadastro.condicao_pagamento.destroy'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;
        return view('cadastro.condicao_pagamento.destroy', compact('condicaoPagamento', 'bloquearCampos'));
    }

    /**
     * Delete the specified resource from storage permanently.
     */
    public function delete(CondicaoPagamento $condicaoPagamento)
    {
        abort_if (!Auth::user()->canAccess('cadastro.condicao_pagamento.destroy'), 403, 'Acesso não autorizado');

        if($condicaoPagamento->delete()) {
            return redirect()->signedRoute('cadastro.condicao_pagamento.index')->with('success', __('labels.payment_condition.success.deleted'));
        }

        return redirect()->signedRoute('cadastro.condicao_pagamento.index')->with('error', __('labels.payment_condition.error.not_deleted'));
    }

    /**
     * Display the history of the specified resource.
     */
    public function history(CondicaoPagamento $condicaoPagamento)
    {
        abort_if (!Auth::user()->canAccess('cadastro.condicao_pagamento.history'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $condicaoPagamento->load('historicos.user', 'historicos.tipoAlteracao');
        return view('cadastro.condicao_pagamento.history', compact('condicaoPagamento', 'bloquearCampos'));
    }

    /**
     * Show the details of a specific history record.
     */
    public function historyDetails(CondicaoPagamento $condicaoPagamento, $historicoId)
    {
        if (!Auth::user()->canAccess('cadastro.condicao_pagamento.history')) {
            abort(403);
        }

        $historico = CondicaoPagamentoHistorico::findOrFail($historicoId);

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
            'id' => __('labels.payment_condition.history.fields.id'),
            'descricao' => __('labels.payment_condition.history.fields.descricao'),
            'revenda_id' => __('labels.payment_condition.history.fields.revenda_id'),
            'diasEntreParcelas' => __('labels.payment_condition.history.fields.diasEntreParcelas'),
            'quantidadeParcelas' => __('labels.payment_condition.history.fields.quantidadeParcelas'),
            'situacao_id' => __('labels.payment_condition.history.fields.situacao_id'),
            'created_at' => __('labels.payment_condition.history.fields.created_at'),
            'updated_at' => __('labels.payment_condition.history.fields.updated_at'),
            'deleted_at' => __('labels.payment_condition.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
